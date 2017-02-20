function fso (tag,listener)
{
	this.tag=tag;
	this.listener=listener;
	this.stack=[];
	this.index=-1;
}

fso.prototype={
	constructor:fso,
	
	goto:function(path)
	{
		this.stack.splice(this.index+1,this.stack.length-this.index,path)    
		this.index=this.stack.length-1;
		this.explore(path);
	},
	
	download:function(path)
	{
		window.location.assign("mod/fso/api/download.php?path="+path);
	},
	
	
	back:function()
	{
		if(this.index>0)
		{
		   this.index--;       
		}
		this.explore(this.stack[this.index]);
	},

	next:function()
	{
		if(this.index<(this.stack.length-1))
		{
		   this.index++;       
		}
		this.explore(this.stack[this.index]);
	},

	refresh:function()
	{    
		this.explore(this.stack[this.index]);
	},

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
		xhttp.open("POST", "mod/fso/api/explore.php", true);
		xhttp.send(data);
			
	},

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
		xhttp.open("POST", "mod/fso/api/delete.php", true);
		xhttp.send(data);
			
	}
}

