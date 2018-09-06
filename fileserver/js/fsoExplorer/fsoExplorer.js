//Constructor
function fsoExplorer(tag)
{
	this.tag=tag;
	this.fso=new fso(tag,this);
	this.dirdata=[];
	this.onRenderListeners=[];
	this.path='';

	this.units=['bytes','Kb','Mb','Gb','Tb'];
	this.baseUnit=Math.log(1000);
}


//Method definitions
fsoExplorer.prototype={

	constructor:fsoExplorer,

	toUnits:function(size)
	{		
		var idx=Math.trunc(Math.log(size) / this.baseUnit);
		idx = idx >= this.units.length ? this.units.length-1 : idx;
		size =  Math.round( 100 * size / Math.pow(1000,idx))/100;

		return ""+size+" "+this.units[idx];
	},

	//Occurs when a succesfull call returns
	okCallBack:function(data,tag)
	{
		if(data.function=='explore')
			this.render(data,tag);
		else if (data.function=='delete' || data.function=='upload')
		{
			if( typeof(data.failed)!=='undefined' && data.failed.length>0 )
			{
				alert(data.error+'\n'+data.failed.join('\n'));
			}
			this.fso.refresh();
		}
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
		var elem=document.createElement('tr');
		elem.id=data.name;

		// Puts icon
		var mleft=document.createElement('td');	

		var img = document.createElement('a');
		img.classList.add('fsoexplorer-icon');

		if(isFile)
		{
			if(data.extension!='')
				img.classList.add(data.extension);
		}
		else
		{
			img.classList.add('folder');
		}

		// Puts link in icon
		if(isFile)
			img.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].download('"+data.link+"')");
		else
			img.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].goto('"+data.link+"')");

		mleft.appendChild(img);
		elem.appendChild(mleft);

		// Puts label
		var label=document.createElement('td');
		label.classList.add('fsoexplorer-object');
		label.appendChild(document.createTextNode(data.name));

		// Puts link in icon
		if(isFile)
			label.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].download('"+data.link+"')");
		else
			label.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].goto('"+data.link+"')");

		elem.appendChild(label);

		// Puts toolbar
		var tools=document.createElement('td');
		if(data.name!='..')
		{			
			tools.classList.add('fsoexplorer-toolbar');

			var del=document.createElement('SPAN');
			del.classList.add('fsoexplorer-icon');
			del.classList.add('fsoexplorer-del');
			del.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].erase('"+data.link+"')");
			tools.appendChild(del);
		}

		elem.appendChild(tools);

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

		var title=document.createElement('div');

		// Path
		var lblPath=document.createElement('span');
		var txt=decodeURIComponent(data.path);
		if(txt=='')
			txt='/';
		lblPath.appendChild(document.createTextNode(txt));

		// Free space
		var lblFree=document.createElement("span");
		lblFree.appendChild(document.createTextNode(this.toUnits(data.free)));
		lblFree.appendChild(document.createTextNode('/'));
		lblFree.appendChild(document.createTextNode(this.toUnits(data.total)));

		title.appendChild(lblPath);
		title.appendChild(lblFree);

		this.progressBar=document.createElement('progress');
		//this.progressBar.hidden=true;
		this.progressBar.id=tag+'-progress';
		this.progressBar.value=0;
		this.progressBar.max=100;
		this.toolbar.appendChild(this.progressBar);

		this.toolbar.appendChild(title);

		for(var i in this.onRenderListeners)
			this.onRenderListeners[i].onBeginRender(this,this.toolbar,data);

		container.appendChild(this.toolbar);

		//Paint dirs and files
		var lst=document.createElement('table');
		lst.classList.add('table-striped');
		lst.classList.add('table-responsive-sm');
		lst.classList.add('table');

		// Table header
		var hdr = document.createElement('thead');

		var td = document.createElement('th');
		td.appendChild(document.createTextNode('Nombre'));
		td.colSpan=2;
		hdr.appendChild(td);

		td = document.createElement('th');
		td.appendChild(document.createTextNode('Acciones'));
		hdr.appendChild(td);

		lst.appendChild(hdr);

		// Table body
		var tbody=document.createElement('tbody');

		// Directorys first
		for(var d in data.dirs)
		{
			tbody.appendChild(this.render_obj(data.dirs[d],false));
			this.dirdata[d]=data.dirs[d];
		}
		
		// Files later
		for(var d in data.files)
		{
			tbody.appendChild(this.render_obj(data.files[d],true));
			this.dirdata[d]=data.files[d];
		}

		lst.appendChild(tbody);
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


