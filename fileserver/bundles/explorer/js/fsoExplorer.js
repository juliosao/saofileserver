//Constructor
class fsoExplorer extends FsoListener {
	constructor(tag) {
		super();
		this.tag = tag;
		this.fso = fsoObj.getRoot(this)
		this.dirdata = [];
		this.onRenderListeners = [];
		this.path = '';
		
	}
	//fsoExplorer initialization
	static setup() {

		fsoExplorer.controllers = {};
		var explorers = document.getElementsByClassName("fso-explorer");
		for (var i = 0; i < explorers.length; i++) {
			var controller = new fsoExplorer(explorers[i].id);
			fsoExplorer.controllers[explorers[i].id] = controller;
			var initial = explorers[i].getAttribute('initial-dir');
			if (initial == null || initial == '') {
				initial = '/';
			}
			explorers[i].addEventListener("dragover", fsoExplorer.cancel);
			explorers[i].addEventListener("dragenter", fsoExplorer.cancel);
			explorers[i].addEventListener("drop", function (ev) { fsoExplorer.upload(ev, controller); });
			controller.fso.explore();
		}
		document.addEventListener('dragover', fsoExplorer.cancel);
	}

	static toUnits(size)
	{		
		var idx=Math.trunc(Math.log(size) / fsoExplorer.baseUnit);
		idx = idx >= fsoExplorer.units.length ? fsoExplorer.units.length-1 : idx;
		size =  Math.round( 100 * size / Math.pow(1000,idx))/100;

		return ""+size+" "+fsoExplorer.units[idx];
	}

	static cancel(ev) 
	{
		ev.preventDefault();
		ev.dataTransfer.dropEffect = 'copy';
		//ev.stopPropagation();
		return false;
	};

	static upload(e, src) 
	{
		e.preventDefault();
		e.stopPropagation();
		var dt = e.dataTransfer;
		var files = dt.files;
		src.progressBar.hidden = false;
		src.progressBar.value = 1;
		src.fso.upload(files, src.path);
	};

	/*
	REACCIONES A LOS EVENTOS DEL FSO
	*/
	onError(sender,message)
	{
		alert("Error:"+message);
		console.log("Error:"+sender.name+" at "+sender.name);
		this.fso.explore();
	}

	onOk(sender)
	{
		console.log("Ok:"+sender.name);
		this.fso.explore();
	}

	// Mantiene la barra de progreso
	onProgress(sender,fraction,total)
	{
		if(pos!=-1)
		{
			this.progressBar.value=fraction/total*100;
		}
		else
		{
			this.progressBar.value = (this.progressBar.value + 5) % 100;
		}
	}

	// Pinta la lista de archivos
	onRefresh(sender)
	{
		this.fso = sender;
		this.path=sender.data.path;

		//Finds tag
		var container=document.getElementById(this.tag);
		if(container==null)
		{
			console.log(this.tag+" not found.");
			return;
		}

		//Clears old data
		while (container.firstChild) {
			container.removeChild(container.firstChild);
		}

		//Puts main toolbar
		this.toolbar = this.renderToolBar(sender);
		
		for(var i in this.onRenderListeners)
			this.onRenderListeners[i].onBeginRender(this,sender);

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

		// Puts fsos
		for(var d in this.fso.data.childs)
		{
			tbody.appendChild(this.renderObj(this.fso.data.childs[d]));
		}

		lst.appendChild(tbody);
		container.appendChild(lst);
		
		for(var i in this.onRenderListeners)
			this.onRenderListeners[i].onFinishRender(this);
		
	}

	/**
	 * 
	 * @param {*} obj The FsoObject with the data to render.
	 * @returns A <tr> with the object rendered. We can access these object inside this object:
	 * 		- img: A image tag indeed for object icon.
	 * 		- label: A label
	 * 		- tools: A toolbar span.
	 */
	renderObj(obj)
	{
		// List Entry
		var elem=document.createElement('tr');
		elem.id=obj.data.name;

		// Puts icon
		var mleft=document.createElement('td');	
		elem.img = document.createElement('a');
		elem.img.classList.add('fsoexplorer-icon');
		mleft.appendChild(elem.img);
		elem.appendChild(mleft);

		if(obj instanceof fsoDir)
		{
			elem.img.classList.add('folder');			
		}
		else 
		{
			elem.img.classList.add('file');
			if(obj.data.extension!='')
			{			
				elem.img.classList.add(obj.data.extension);
			}
		}
		

		elem.img.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].fso.data.childs['"+obj.data.name+"'].explore()");

		// Puts label
		elem.label=document.createElement('td');
		elem.label.classList.add('fsoexplorer-object');
		elem.label.appendChild(document.createTextNode(obj.data.name));
		elem.appendChild(elem.label);

		// Puts toolbar
		elem.tools=document.createElement('td');
		if(obj.data.name!='..')
		{			
			elem.tools.classList.add('fsoexplorer-toolbar');
			var del=document.createElement('span');
			del.classList.add('fsoexplorer-icon');
			del.classList.add('fsoexplorer-del');
			del.setAttribute('onclick',"fsoExplorer.controllers['"+this.tag+"'].fso.data.childs['"+obj.data.name+"'].delete()");
			elem.tools.appendChild(del);
		}

		for(var i in this.onRenderListeners)
			this.onRenderListeners[i].onElementRender(this,elem,obj);

		elem.appendChild(elem.tools);

		return elem;
	}

	/**
	 * Returns a toolbar object
	 * @param {*} src : The fsoObject firing this event
	 */
	renderToolBar(src)
	{
		var toolbar=document.createElement('div');
		toolbar.className='fsoexplorer-toolbar';

		var title=document.createElement('div');

		// Path
		var lblPath=document.createElement('span');
		var txt=decodeURIComponent(src.data.link);
		if(txt=='')
			txt='/';
		lblPath.appendChild(document.createTextNode(txt));

		// Free space
		var lblFree=document.createElement("span");
		lblFree.appendChild(document.createTextNode(fsoExplorer.toUnits(src.data.free)));
		lblFree.appendChild(document.createTextNode('/'));
		lblFree.appendChild(document.createTextNode(fsoExplorer.toUnits(src.data.total)));

		title.appendChild(lblPath);
		title.appendChild(lblFree);
		toolbar.appendChild(title);

		toolbar.progressBar=document.createElement('progress');
		//this.progressBar.hidden=true;
		toolbar.progressBar.id=this.tag+'-progress';
		toolbar.progressBar.value=0;
		toolbar.progressBar.max=100;
		toolbar.progressBar.hidden=true;
		toolbar.appendChild(toolbar.progressBar);

		
		return toolbar;

	}
	


	//Moves to directory
	goto(p)
	{
		this.fso.data.childs[p].explore();
	}

	//Downloads a file
	download(p)
	{
		this.fso.data.childs[p].explore();
	}

	//Erases a file or directory
	erase(p)
	{
		this.fso.data.childs[p].delete();
	}

	appendRenderListener(obj)
	{
		this.onRenderListeners.push(obj);
	}

}

fsoExplorer.units = ['bytes','Kb','Mb','Gb','Tb'];
fsoExplorer.baseUnit = Math.log(1000);


//Calls init
window.addEventListener('load',fsoExplorer.setup);


