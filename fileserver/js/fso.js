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
		await App.jsonRemoteCall("api/fso/delete.php",{path:this.link});
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
		var link = path;
		var name = path.split('/').slice(-1)[0];
		var dir = new fsoDir({link:link, name:name});
		await dir.explore();
		return dir;
	}

	async explore()
	{
		var data = await App.jsonRemoteCall("api/fso/explore.php",{'path':this.link});
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

	async mkdir(newDir)
	{
		await App.jsonRemoteCall("api/fso/mkdir.php",{'path':this.link,'name': encodeURIComponent(newDir)});
	}

	async upload(files,progressCallBack)
	{
		var data = new FormData();
		data.append('path', this.link);
		for(var i=0; i<files.length; i++)
		{
			data.append('files[]',files[i],files[i].name);
		}
		
		var me=this;
		var xhttp = new XMLHttpRequest();
		
		/*
		if(typeof onFinishCallBack !== 'undefined')
		{
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4) {
					if(this.status == 200) 
						onFinishCallBack(true,JSON.parse(this.responseText));
					else if(this.status>=400)
						onFinishCallBack(false,this.responseText ? this.responseText : this.statusText);
				}
			};
		}
		*/

		if(typeof progressCallBack !== 'undefined')
		{
			xhttp.upload.onprogress=function(ev)
			{
				if (!ev.lengthComputable) 
				{
					progressCallBack(-1,-1);
				}
				else
				{
					progressCallBack(ev.loaded,ev.total);
				}
				
			};
		}

		xhttp.open("POST", App.baseUrl+"api/fso/upload.php", false);
		xhttp.send(data);

		if(xhttp.status == 200) 
			return JSON.parse(xhttp.responseText)
		else if (xhttp.status >= 200)
			throw (xhttp.responseText ? xhttp.responseText : xhttp.statusText);

		
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
		link.setAttribute('href', App.baseUrl+"api/fso/download.php?path="+encodeURIComponent(this.link));
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
