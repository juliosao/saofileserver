class HttpFunction
{
    constructor(url)
    {
        this.url=url;
        this.onOk=null;
        this.onError=null;
        this.json=false;
    }

    onCompleted(status,data)
    {
        switch(status)
        {
            case 200:
                if(this.onOk!=null)
                    this.onOk(data);
                break;

            default:
                if(this.onError!=null)
                    this.onError(this.status,data);
                break;
        } 
    }

    call(args)
    {
        var me=this;
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function(data) 
        {            
            if(this.readyState != 4)
                return;

            if(this.status>=400)
            {
                if(this.responseText.length>0)
                    me.onCompleted(this.status, this.responseText);
                else
                    me.onCompleted(this.status, this.statusText);
            }
            else
                me.onCompleted(this.status, this.responseText);
        }

        xhttp.open("POST", this.url, true);

        var data = new FormData();
    
        if(typeof args!=="undefined" && args!=null)
        {
            for(var key in args)
            {
                data.append(key,data[key]);
            }
        }

        xhttp.send(data);
    }
}
