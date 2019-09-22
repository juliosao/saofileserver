//Constructor
class fsoObj
{
	constructor(data)
	{
		this.link = data.link;
		this.name = data.name;
	}

	async delete()
	{
		await App.jsonRemoteCall("api/delete.php",{path:this.link});
	}

}

class fsoDir extends fsoObj
{
	constructor(data)
	{
		super(data);
		this.childDirs={};
		this.childFiles={};
	}

	static async get(path)
	{
		var link = encodeURIComponent(path);
		var name = path.split('/').slice(-1)[0];
		var dir = new fsoDir({link:link, name:name});
		await dir.explore();
		return dir;
	}

	async explore()
	{
		var data = await App.jsonRemoteCall("api/explore.php",{path:this.link});
		this.childDirs={};
		this.childFiles={};

		for(var i in data.dirs)
		{
			this.childDirs[data.dirs[i].name] = new fsoDir(data.dirs[i]);
		}

		for(var i in data.files)
		{
			this.childFiles[data.files[i].name] = new fsoFile(data.files[i]);
		}

		this.free = data.free;
		this.total = data.total;
		this.link = data.link;
		this.name = data.name;
		return this;
	}

	async upload(files)
	{
		var data = new FormData();
		data.append('path', this.link);
		for(var i=0; i<files.length; i++)
		{
			data.append('files[]',files[i],files[i].name);
		}
		
		await fetch("api/upload.php",
			{
				method: 'POST',
				body: data
			}
		);
		/*
		var me=this;
		var xhttp = new XMLHttpRequest();
		
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4) {
				if(this.status == 200) 
					listener.onOk(me);
				else if(this.status>=400)
					listener.onError(me,this.responseText ? this.responseText : this.statusText);
			}
		};

		xhttp.upload.onprogress=function(ev)
		{
			console.log(ev.toString());
			if(listener.progressCallBack)
			{
				if (!ev.lengthComputable) 
				{
					listener.progressCallBack(me,-1,-1);
				}
				else
				{
					me.listener.progressCallBack(me,ev.loaded,ev.total);
				}
			}
		};
		xhttp.open("POST", "api/upload.php", true);
		xhttp.send(data);
		*/
	}

}

class fsoFile extends fsoObj
{
	constructor(data)
	{
		super(data);
		this.extension=data.extension;
		this.size=data.size;
		this.mime=data.mime;
	}

	explore()
	{
		var link = document.createElement('a');
		link.setAttribute('href', "api/download.php?path="+this.link);
		link.setAttribute('download', 'Filename.jpg');
		link.setAttribute('target', '_blank');
		link.style.display = 'none';
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}
}

class uploadListener
{
	progressCallBack(src,loaded,total){};
}
