function fsoExplorer(tag)
{
	this.tag=tag;
	this.fso=new fso(tag,this);
	this.goto('/');
	this.dirdata=[];
}
			
fsoExplorer.prototype={

	constructor:fsoExplorer,

	okCallBack:function(data,tag)
	{
		if(data.function=='explore')
			this.render(data,tag);
		else if (data.function=='delete')
			this.fso.refresh();
	},
	
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
			var elem=document.createElement('LI');
			elem.classList.add('fsoexplorer-dir');
			elem.id=d;
		
			var link=document.createElement('A');
			link.setAttribute('onclick',"fsoExplorer.controllers['"+tag+"'].goto('"+data.dirs[d].link+"')");	
		
			var img=document.createElement('SPAN');
			img.className='fsoexplorer-icon';
			link.appendChild(img);
		
			var label=document.createElement('SPAN');
			label.classList.add('fsoexplorer-label');
			label.appendChild(document.createTextNode(data.dirs[d].name));	
			link.appendChild(label);						
		
			elem.appendChild(link);
		
			var del=document.createElement('SPAN');
			del.classList.add('fsoexplorer-toolbar-icon');
			del.classList.add('fsoexplorer-del');	
			del.setAttribute('onclick',"fsoExplorer.controllers['"+tag+"'].erase('"+data.dirs[d].link+"')");	
			elem.appendChild(del);
		
			lst.appendChild(elem);
			this.dirdata[d]=data.dirs[d];
		}
		container.appendChild(lst);
	
		//Pintamos archivos
		var lst=document.createElement('UL');
		lst.className='fsoexplorer-list';
		for(var d in data.files)
		{
			var elem=document.createElement('LI');
			elem.classList.add('fsoexplorer-file');			
			elem.id=d;
		
			var link=document.createElement('A');
			link.setAttribute('onclick',"fsoExplorer.controllers['"+tag+"'].download('"+data.files[d].link+"')");	
		
			var img=document.createElement('SPAN');
			img.classList.add('fsoexplorer-icon');			
			img.classList.add(data.files[d].extension);
			link.appendChild(img);
	
			var label=document.createElement('SPAN');
			label.classList.add('fsoexplorer-label');
			label.appendChild(document.createTextNode(data.files[d].name));	
			link.appendChild(label);
		
			elem.appendChild(link);
		
			var del=document.createElement('SPAN');
			del.classList.add('fsoexplorer-toolbar-icon');
			del.classList.add('fsoexplorer-del');	
			del.setAttribute('onclick',"fsoExplorer.controllers['"+tag+"'].erase('"+data.files[d].link+"')");	
			elem.appendChild(del);
		
			lst.appendChild(elem);
			this.dirdata[d]=data.files[d];
		}		
		container.appendChild(lst);
	},
	
	goto:function(path)
	{
		this.fso.goto(path);
	},
	
	download:function(path)
	{
		this.fso.download(path);
	},
	
	erase:function(path)
	{
		if(confirm("¿Borrar "+path+"?"))
			this.fso.erase(path);
	},
	
	fail:function(data)
	{
		alert(data);
	}
}


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

window.addEventListener('load',fsoExplorer.setup);


