var util = {};

util.call = function(file,args,listener)
{        
    var me=this;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(data) {
        if (this.readyState == 4 && this.status == 200) {

            if(typeof listener != "undefined")
            {
                listener.ok(this.responseText);
            }                
        }
    };
    xhttp.onerror = function(data)
    {
        if(typeof listener != "undefined")
        {
            listener.error(this.responseText);
        }                
    }
    xhttp.open("POST", file, true);

    var data = new FormData();
    
    if(typeof args!=="undefined")
    {
        for(var key in args)
        {
            data.append(key,data[key]);
        }
    }

    xhttp.send(data);
}

class Listener
{
    constructor(cok,cerror)
    {
        this.cok=cok;
        this.cerror=cerror;
    }

    ok(data)
    {
        if(this.cok instanceof Decorator)
            this.cok.process(data);
        else if (this.cok!=null)
            this.cok(data);
    }

    error(data)
    {
        if(this.cerror instanceof Decorator)
            this.cerror.process(data);
        else if (this.cerror!=null)
            this.cerror(data);
    }
}

class JSONListener extends Listener
{
    constructor(cok,cerror)
    {
        this.cok=cok;
        this.cerror=cerror;
    }

    ok(data)
    {
        if(this.cok instanceof Decorator)
            this.cok.process(JSON.parse(data));
        else if (this.cok!=null)
            this.cok(JSON.parse(data));
    }

    error(data)
    {
        if(this.cerror instanceof Decorator)
            this.cerror.process(JSON.parse(data));
        else if (this.cerror!=null)
            this.cerror(JSON.parse(data));
    }
}

class Decorator
{
    process(data)
    {
        return null;
    }
}

class ElementDecorator extends Decorator
{
    constructor(tag)
    {
        if(typeof tag == 'string')
            this.tag=document.getElementById(form);
        else
            this.tag=tag; 

        if(this.tag===null)
            throw "tag is null";
    }

    process(data)
    {
        while(this.tag.firstChild){
            this.tag.removeChild(this.tag.firstChild);
        }
        this.tag.appendChild(document.createTextNode(data));
        return null;
    }
}

class FormDecorator extends ElementDecorator
{
    constructor(form,extra)
    {
        super(form);

        this.extra=extra;
        if(typeof this.extra.decorators ==='undefined')
        {
            this.extra.decorators={};
        }
    }

    process(data)
    {
        for(k in data)
        {
            if(typeof this.extra.decorators[k]==='undefined')
                this.extra[k]=new ElementDecorator(k);

            this.extra[k].decorators.process(data[k]);
        }
    }
}
