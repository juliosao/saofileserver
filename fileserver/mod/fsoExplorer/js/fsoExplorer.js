class fsoExplorer{
	constructor(tag)
	{
		this.tag=tag;
		this.fso=new fso(tag,this.render,this.fail);
		this.goto('/');
	}
	
	render(data,tag)
	{
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
		lst.className='fsoexplorer-dir-list';
		for(var d in data.dirs)
		{
			var elem=document.createElement('LI');
			lst.className='fsoexplorer-dir';
			elem.appendChild(document.createTextNode(data.dirs[d].name));
			lst.appendChild(elem);
		}
		container.appendChild(lst);
		
		//Pintamos archivos
		var lst=document.createElement('UL');
		lst.className='fsoexplorer-file-list';
		for(var d in data.files)
		{
			var elem=document.createElement('LI');
			lst.className='fsoexplorer-file';
			elem.appendChild(document.createTextNode(data.files[d].name));
			lst.appendChild(elem);
		}		
		container.appendChild(lst);
	}
	
	goto(path)
	{
		this.fso.explore(path);
	}
}
