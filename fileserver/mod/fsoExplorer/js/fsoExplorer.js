class fsoExplorer{
	constructor(tag)
	{
		this.tag=tag;
		this.fso=new fso(tag,this);
		this.goto('/');
		this.dirdata=[];
	}
			
	
	okCallBack(data,tag)
	{
		if(data.function=='explore')
			this.render(data,tag);
		else if (data.function=='delete')
			this.fso.refresh();
	}
	
	render(data,tag)
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
			link.setAttribute('onclick',tag+"_controller.goto('"+data.dirs[d].link+"')");	
			
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
			del.setAttribute('onclick',tag+"_controller.erase('"+d+"')");	
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
			link.setAttribute('onclick',tag+"_controller.download('"+data.files[d].link+"')");	
			
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
			del.setAttribute('onclick',tag+"_controller.erase('"+d+"')");	
			elem.appendChild(del);
			
			lst.appendChild(elem);
			this.dirdata[d]=data.files[d];
		}		
		container.appendChild(lst);
	}
	
	goto(path)
	{
		this.fso.explore(path);
	}
	
	download(path)
	{
		this.fso.download(path);
	}
	
	erase(path)
	{
		if(confirm("¿Borrar "+path+"?"))
			this.fso.erase(this.dirdata[path].link);
	}
	
	fail(data)
	{
		alert(data);
	}
}
