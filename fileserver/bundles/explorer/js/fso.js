//Constructor
class fsoObj extends RemoteObject
{
	constructor(data, listener)
	{
		if(listener===null)
		{
			listener = FsoListener.getInstance();
		}
		super(data,listener);
	}

	static getRoot(listener)
	{
		var root = new fsoObj({name:'/',link:encodeURIComponent('/')},listener);
		return root;
	}

	explore()
	{
		var self = this;
		this.jsonRemoteCall("api/explore.php",{path:this.data.link},
			function(data)
			{
				self.data.childs={};

				// Paint folders first
				for(var i in data.dirs)
				{
					self.data.childs[data.dirs[i].name] = new fsoDir(data.dirs[i],self.listener);
				}

				for(var i in data.files)
				{
					self.data.childs[data.files[i].name] = new fsoFile(data.files[i],self.listener);
				}

				self.data.free = data.free;
				self.data.total = data.total;
				self.data.link = data.link;
				self.data.name = data.name;
				
				self.listener.onRefresh(self);
			}
		);
	}

	delete()
	{
		var self = this;
		this.jsonRemoteCall("api/delete.php",{path:this.data.link}, 
			function(data)
			{
				self.listener.onOk(self);
			}
			
		);
	}

}

class fsoDir extends fsoObj
{
	/*
	upload(files)
	{
		var data = new FormData();
		data.append('path', this.link);
		for(var i=0; i<files.length; i++)
		{
			data.append('files[]',files[i],files[i].name);
		}
		
		var me=this;
		var xhttp = new XMLHttpRequest();
		
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4) {
				if(this.status == 200) 
					me.listener.onOk(me);
				else if(this.status>=400)
					me.listener.onError(me,this.responseText ? this.responseText : this.statusText);
			}
		};

		xhttp.upload.onprogress=function(ev)
		{
			console.log(ev.toString());
			if(me.listener.progressCallBack)
			{
				if (!ev.lengthComputable) 
				{
					me.listener.progressCallBack(me,-1,-1);
				}
				else
				{
					me.listener.progressCallBack(me,ev.loaded,ev.total);
				}
			}
		};
		xhttp.open("POST", "api/upload.php", true);
		xhttp.send(data);
	}
	*/

}

class fsoFile extends fsoObj
{
	explore()
	{
		window.location.assign("api/download.php?path="+this.data.link);
	}
}

class FsoListener extends RemoteListener
{
    static getInstance()
    {
        if(FsoListener.instance == null)
			FsoListener.instance = new FsoListener();

        return FsoListener.instance;
	}
	
	onProgress(sender,fraction,total)
	{
		console.log("Progress "+sender.name+fraction+"/"+total);
	}

	onError(sender,message)
	{
		alert("Error:"+message);
		sender.explore();
	}

	onOk(sender)
	{
		console.log("Ok:"+sender.name);
		sender.explore();
	}

	onRefresh(sender)
	{
		console.log("Refresh:"+sender.data.name);
	}
}
FsoListener.instance = null;


