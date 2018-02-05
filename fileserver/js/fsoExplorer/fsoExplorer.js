//Constructor
function fsoExplorer(tag)
{
	this.tag=tag;
	this.fso=new fso(tag,this);
	this.dirdata=[];
	this.onRenderListeners=[];
	this.path='';
}

//Method definitions
fsoExplorer.prototype={

	constructor:fsoExplorer,

	//Occurs when a succesfull call returns
	okCallBack:function(data,tag)
	{
		if(data.function=='explore')
			this.render(data,tag);
		else if (data.function=='delete' || data.function=='upload')
			this.fso.refresh();
		else
			alert(data.error);
			
		this.progressBar.hidden=true;
	},

	progressCallBack:function(pos,total)
	{		
		if(pos!=-1)
		{
			this.progressBar.value=pos/total*100;
		}
		else
		{
			this.progressBar.value = (this.progressBar.value + 5) % 100;
		}
	},

	render_obj:function(data,isFile)
	{
		// List entry
		var elem=document.createElement('LI');
		if(isFile)
			elem.classList.add('fsoexplorer-file');
		else
			elem.classList.add('fsoexplorer-dir');

		elem.id=data.name;		

		// Puts icon
		var img=document.createElement('SPAN');
		img.className='fsoexplorer-icon';
		if(isFile)
		{
			if(data.extension!='')
				img.classList.add(data.extension);
		}

		// Puts link in icon
		if(isFile)
			img.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].download('"+data.link+"')");
		else
			img.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].goto('"+data.link+"')");

		elem.appendChild(img);

		// Puts label
		var label=document.createElement('SPAN');
		label.className='fsoexplorer-label';
		label.appendChild(document.createTextNode(data.name));
		// Puts link in icon
		if(isFile)
			label.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].download('"+data.link+"')");
		else
			label.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].goto('"+data.link+"')");

		elem.appendChild(label);

		//Puts toolbar
		if(data.name!='..')
		{
			var tools=document.createElement('SPAN');
			tools.classList.add('fsoexplorer-toolbar');
			elem.appendChild(tools);

			// Puts delbutton
			var del=document.createElement('SPAN');
			del.classList.add('fsoexplorer-icon');
			del.classList.add('fsoexplorer-del');
			del.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].erase('"+data.link+"')");
			tools.appendChild(del);
		}

		for(var i in this.onRenderListeners)
			this.onRenderListeners[i].onElementRender(this,elem,data,isFile);

		return elem;
	},


	//Renders directory data
	render:function(data,tag)
	{
		this.dirdata=[];
		this.path=data.path;

		//Finds tag
		var container=document.getElementById(tag);
		if(container==null)
			alert("No se encontro "+tag);
		
		//Clears old data
		while (container.firstChild) {
			container.removeChild(container.firstChild);
		}

		//Puts main toolbar
		this.toolbar=document.createElement('DIV');
		this.toolbar.className='fsoexplorer-toolbar';
		
		var label=document.createElement('H1');
		var txt=decodeURIComponent(data.path);
		if(txt=='')
			txt='/';		

		label.appendChild(document.createTextNode(txt));

		this.progressBar=document.createElement('progress');
		this.progressBar.hidden=true;
		this.progressBar.id=tag+'-progress';
		this.progressBar.value=0;
		this.progressBar.max=100;
		this.toolbar.appendChild(this.progressBar);

		this.toolbar.appendChild(label);

		for(var i in this.onRenderListeners)
			this.onRenderListeners[i].onBeginRender(this,this.toolbar,data);		

		container.appendChild(this.toolbar);

		//Paint dirs
		var lst=document.createElement('UL');
		lst.className='fsoexplorer-list';
		for(var d in data.dirs)
		{
			try
			{
				lst.appendChild(this.render_obj(data.dirs[d],false));
				this.dirdata[d]=data.dirs[d];
			}
			catch
			{
				data.dirs.splice(d,1) 
			}
		}
		container.appendChild(lst);

		//Paint files
		var lst=document.createElement('UL');
		lst.className='fsoexplorer-list';
		for(var d in data.files)
		{
			lst.appendChild(this.render_obj(data.files[d],true));
			this.dirdata[d]=data.files[d];
		}
		container.appendChild(lst);

		for(var i in this.onRenderListeners)
			this.onRenderListeners[i].onFinishRender(this);
	},

	//Moves to directory
	goto:function(path)
	{
		this.fso.goto(path);
	},

	//Downloads a file
	download:function(path)
	{
		this.fso.download(path);
	},

	//Erases a file or directory
	erase:function(path)
	{
		if(confirm("¿Borrar "+path+"?"))
			this.fso.erase(path);
	},

	//Occurs when a request fails
	fail:function(data)
	{
		alert(data);
	},

	appendRenderListener:function(obj)
	{
		this.onRenderListeners.push(obj);
	}
}



//fsoExplorer initialization
fsoExplorer.setup=function()
{
	cancel=function(ev)
	{
		ev.preventDefault();
		ev.dataTransfer.dropEffect = 'copy';
		//ev.stopPropagation();
		return false;
	}

	upload=function(e,src)
	{
		e.preventDefault();
		e.stopPropagation();

		var dt = e.dataTransfer;
		var files = dt.files;

		src.progressBar.hidden=false;
		src.progressBar.value=1;


		src.fso.upload(files, src.path);
	}

	fsoExplorer.controllers=[];

	var explorers=document.getElementsByClassName("fso-explorer");
	for( var i=0; i<explorers.length; i++ )
	{
		var controller = new fsoExplorer(explorers[i].id);
		fsoExplorer.controllers[explorers[i].id]=controller;

		var initial=explorers[i].getAttribute('initial-dir');
		if(initial==null || initial=='')
		{
			initial='/';
		}

		explorers[i].addEventListener("dragover", cancel);
    	explorers[i].addEventListener("dragenter", cancel);
		explorers[i].addEventListener("drop", function(ev){upload(ev,controller)} );
		controller.goto(initial);

	}

	document.addEventListener('dragover',cancel);
}

//Calls init
window.addEventListener('load',fsoExplorer.setup);


