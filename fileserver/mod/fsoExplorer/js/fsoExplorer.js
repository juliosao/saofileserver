//Constructor
function fsoExplorer(tag)
{
	this.tag=tag;
	this.fso=new fso(tag,this);
	this.goto('/');
	this.dirdata=[];
}

//Method definitions
fsoExplorer.prototype={

	constructor:fsoExplorer,

	//Occurs when a succesfull call returns
	okCallBack:function(data,tag)
	{
		if(data.function=='explore')
			this.render(data,tag);
		else if (data.function=='delete')
			this.fso.refresh();
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

		// Link
		var link=document.createElement('A');
		if(isFile)
			link.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].download('"+data.link+"')");
		else
			link.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].goto('"+data.link+"')");

		// Puts icon
		var img=document.createElement('SPAN');
		img.className='fsoexplorer-icon';
		if(isFile)
			img.classList.add(data.extension);

		link.appendChild(img);

		// Puts label
		var label=document.createElement('SPAN');
		label.classList.add('fsoexplorer-label');
		label.appendChild(document.createTextNode(data.name));
		link.appendChild(label);

		elem.appendChild(link);

		// Puts delbutton
		var del=document.createElement('SPAN');
		del.classList.add('fsoexplorer-toolbar-icon');
		del.classList.add('fsoexplorer-del');
		del.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].erase('"+data.link+"')");
		elem.appendChild(del);

		return elem;
	},


	//Renders directory data
	render:function(data,tag)
	{
		this.dirdata=[];

		//Buscamos donde cargar los datos
		var container=document.getElementById(tag);
		if(container==null)
			alert("No se encontro "+tag);

		//Borramos contenido
		while (container.firstChild) {
			container.removeChild(container.firstChild);
		}

		//Pintamos directorios
		var lst=document.createElement('UL');
		lst.className='fsoexplorer-list';
		for(var d in data.dirs)
		{
			lst.appendChild(this.render_obj(data.dirs[d],false));
			this.dirdata[d]=data.dirs[d];
		}
		container.appendChild(lst);

		//Pintamos archivos
		var lst=document.createElement('UL');
		lst.className='fsoexplorer-list';
		for(var d in data.files)
		{
			lst.appendChild(this.render_obj(data.files[d],true));
			this.dirdata[d]=data.files[d];
		}
		container.appendChild(lst);
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
	}
}

//fsoExplorer initialization
fsoExplorer.setup=function()
{
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

		controller.goto(initial);

	}
}

//Calls init
window.addEventListener('load',fsoExplorer.setup);


