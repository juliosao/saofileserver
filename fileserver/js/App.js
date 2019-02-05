class RemoteObject
{
    // Generic constructor
    constructor(data,listener)
    {
        this.data = data;

        if(typeof listener == 'undefined')
            this.listener = RemoteListener.getInstance();
        else
            this.listener = listener;
    }

    loadData(data)
    {
        this.data=data;
    }

    parseParameters(dataSrc)
    {
        var data = null;
        if(typeof data != 'undefined')
        {
            var data = new FormData();
            for(var prop in dataSrc)
            {
                data.append(prop,dataSrc[prop]);
            }
        }
        return data;
    }

    doCallBack(data,func)
    {
        if(typeof func == 'string')
            this.listener[func](data);
        else if(typeof func  == 'function')
            func(data);
        else
            throw 'Invalid function callback';
    }

    // Generic remote function call
    plainRemoteCall(url, data, onOk)
    {
        var objData = this.parseParameters(data);
        var self = this;
        var xhttp = new XMLHttpRequest();
        
        xhttp.onreadystatechange = function()
        {
            if (this.readyState == 4)
            {
                if(this.status == 200 && typeof onOk != 'undefined')
                {
                    self.doCallBack(this.responseText, onOk);
                }
                else if(this.status==401 && typeof App.loginUrl!== undefined)
                {
                    window.location.href=App.loginUrl;
                }
                else if(this.status>=400)
                {
                    self.listener.onError(self,this.responseText ? this.responseText : this.statusText);
                }
			}
        };
		
		xhttp.open("POST", url, true);
        xhttp.send(objData);
    }

    // Function used for call server expecting a json response
    jsonRemoteCall(url, dataSrc, onOk)
    {
        var data = this.parseParameters(dataSrc);
        var self = this;
        var xhttp = new XMLHttpRequest();
        
        xhttp.onreadystatechange = function()
        {
            if (this.readyState == 4)
            {
                if(this.status == 200 && typeof onOk != 'undefined')
                {
                    self.doCallBack(JSON.parse(this.responseText),onOk);
                }
                else if(this.status==401 && typeof App.loginUrl!== undefined)
                {
                    window.location.href=App.loginUrl;
                }
                else if(this.status>=400)
                {
                    self.listener.onError(self,this.responseText ? this.responseText : this.statusText);
                }
			}
        };
		
		xhttp.open("POST", url, true);
        xhttp.send(data);
    }
}

// Class implementing generic callbacks
class RemoteListener
{    
    static getInstance()
    {
        if(RemoteListener.instance == null)
            RemoteListener.instance = new RemoteListener();

        return RemoteListener.instance;
    }

    onError(sender,data)
    {
        console.log(data);
        alert(data);
    }
}
RemoteListener.instance = null;

// This void class is a container for some usefull data
class App
{

}