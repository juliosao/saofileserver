//Constructor
function fso (tag,listener)
{
	this.tag=tag;
	this.listener=listener;
	this.stack=[];
	this.index=-1;
}

//Method definitions
fso.prototype={
	constructor:fso,
	
	//Moves to directory
	goto:function(path)
	{
		this.stack.splice(this.index+1,this.stack.length-this.index,path)    
		this.index=this.stack.length-1;
		this.explore(path);
	},
	
	//Downloads a file
	download:function(path)
	{
		window.location.assign("../../api/fso/download.php?path="+path);
	},

	//Uploads a file
	upload:function(files,path)
	{
		var data = new FormData();
		data.append('path', path);
		for(var i=0; i<files.length; i++)
		{
			data.append('files[]',files[i],files[i].name);
		}
		
		var me=this;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(data) {
			if (this.readyState == 4 && this.status == 200) {
				me.listener.okCallBack(JSON.parse(this.responseText),me.tag);
			}
		};
		xhttp.open("POST", "../../api/fso/upload.php", true);
		xhttp.send(data);
	},
	
	//Goes back
	back:function()
	{
		if(this.index>0)
		{
		   this.index--;       
		}
		this.explore(this.stack[this.index]);
	},

	//Goes next path (after using back)
	next:function()
	{
		if(this.index<(this.stack.length-1))
		{
		   this.index++;       
		}
		this.explore(this.stack[this.index]);
	},

	//Refresh directory data
	refresh:function()
	{    
		this.explore(this.stack[this.index]);
	},

	//Gets directory data
	explore:function(path)
	{
		var data = new FormData();
		data.append('path', path);
		
		var me=this;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(data) {
			if (this.readyState == 4 && this.status == 200) {
				me.listener.okCallBack(JSON.parse(this.responseText),me.tag);
			}
		};
		xhttp.open("POST", "../../api/fso/explore.php", true);
		xhttp.send(data);
			
	},

	//Erases file or directory
	erase:function(path)
	{
		var data = new FormData();
		
		if(typeof path=='string')			
			data.append('path[]', path);
		else if(typeof path=='object')
		{
			for(i in path)
			{
				data.append('path[]', path[i]);	
			}
		}
		
		var me=this;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(data) {
			if (this.readyState == 4 && this.status == 200) {
				me.listener.okCallBack(JSON.parse(this.responseText),me.tag);
			}
		};
		xhttp.open("POST", "../../api/fso/delete.php", true);
		xhttp.send(data);
			
	}
}

