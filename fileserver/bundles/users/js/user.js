class User extends RemoteObject
{
	constructor(data, listener)
	{
		if(listener===null)
		{
			listener = UserListener.getInstance();
		}
		super(data,listener);
    }
    
    static parse(data)
    {
        data=JSON.parse(data);
        return new User(data.id,data.name,data.mail);
    }

    static load(id,callback)
    {       
        super.call("../../../api/user/load.php",{'id':id},function(data){
                callback(new User(data))
            });
    }

    static list()
    {
        
    }

    save(callback)
    {
        var data = new FormData();
		data.append('id', this.id);
        data.append('mail', this.mail);

        if( typeof this.pw != 'undefined' && this.pw != "")
            data.append('pw',this.pw);

        if( typeof this.pw2 != 'undefined' && this.pw2 != "")
            data.append('pw2',this.pw2);
        		
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(data) {
			if (this.readyState == 4 && this.status == 200) {
                var res=JSON.parse(this.responseText)
                if(res.ok)
                    callback(new User(res.id,res.mail));
			}
        };
        
		xhttp.open("POST", "../../../api/user/save.php", true);
		xhttp.send(data);
    }


}



class UserListener extends RemoteListener
{
    static getInstance()
    {
        if(UserListener.instance == null)
            UserListener.instance = new UserListener();

        return UserListener.instance;
	}
	
	onProgress(sender,fraction,total)
	{
		console.log("Progress "+sender.name+fraction+"/"+total);
	}

	onError(sender,message)
	{
		alert("Error:"+message);
	}

	onOk(sender)
	{
		console.log("Ok:"+sender.name);
	}

	onRefresh(sender)
	{
		console.log("Refresh:"+sender.data.name);
	}
}