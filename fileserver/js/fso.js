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

		for(var i in data.dirs)	{
			this.childDirs[data.dirs[i].name] = new fsoDir(data.dirs[i]);
		}

		for(var i in data.files) {
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

	upload(files,progressCallBack=null)
	{
		return new Promise((resolve, reject) => {
			var data = new FormData();
			data.append('path', this.link);

			for(var i=0; i<files.length; i++) {
				data.append('files[]',files[i],files[i].name);
			}
			
			var xhr = new XMLHttpRequest();	
			if(progressCallBack != null) {
				xhr.upload.onprogress = function(ev) {
					console.log('progress');
					if (!ev.lengthComputable) {
						progressCallBack(-1,-1);
					} else {
						progressCallBack(ev.loaded,ev.total);
					}
				};
			}

			xhr.onload = () => {
				if (xhr.status >= 200 && xhr.status < 300) {
					resolve(JSON.parse(xhr.responseText));
				} else {
					reject(xhr.responseText ? xhr.responseText : xhr.statusText);
				}
			};

			xhr.onerror = () => reject(xhr.statusText);
			xhr.open("POST", App.baseUrl+"api/fso/upload.php", true);
			xhr.send(data);
		});
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

