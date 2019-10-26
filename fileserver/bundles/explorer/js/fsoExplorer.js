/**
 * Plugin interface:
 * fsoExplorer:
 *  .view : Main div
 *  .workSpace : Files DIV
 *  .extraTools : Main toolbar
 *  .plugins : Other plugins
 *  .dir : Current dir
 * plugin:
 *  .start(src) : Called when a plugin is added
 *  -beforeRender(src,dir) : Called before render 
 *  .onRender(src,dir) : Called after render completed
 *  .onRednderFile(src,file,toolbox) : Called after reder a file
 *  .onRednderDir(src,dir,toolbox) : Called after reder a file
 */
class fsoExplorer  
{
	static async setup()
	{		
		fsoExplorer.units = ['bytes','Kb','Mb','Gb','Tb','Pb','Eb'];
		fsoExplorer.baseUnit = Math.log(1000);
		fsoExplorer.controllers = [];

		let divs = document.getElementsByClassName('fso-explorer');
		for(let i = 0; i<divs.length; i++)
		{
			let fsoE = new fsoExplorer(divs[i]);
			fsoExplorer.controllers.push(fsoE);
		}

		document.addEventListener('dragover',fsoExplorer.cancel);
		document.addEventListener('drop',fsoExplorer.cancel);
	}

	static cancel(ev)
	{
		ev.preventDefault();
		ev.dataTransfer.dropEffect = 'copy';
		ev.stopPropagation();
		return false;
	}

	constructor(view)
	{
		this.view = view;
		this.createToolBar();
		this.workSpace = document.createElement('div');	
		this.workSpace.classList.add('container','table-responsive');
		this.view.appendChild(this.workSpace);
		this.plugins=[];

		var self = this;
		fsoDir.get('/').then(function (result){
			self.dir = result;
			self.render();
		})

		view.addEventListener("dragover", fsoExplorer.cancel);
        view.addEventListener("dragenter", fsoExplorer.cancel);
        view.addEventListener("drop", function(ev){
			self.upload(ev)
		} );
		
	}

	createToolBar()
	{
		let self = this;
		let toolBar = document.createElement('div');
		toolBar.classList.add('toolbar');

		this.title = document.createElement('h1');
		toolBar.appendChild(this.title);

		this.spaceleft = document.createElement('h2');
		toolBar.appendChild(this.spaceleft);
		
		this.extraTools = document.createElement('div');
		this.extraTools.classList.add('fso-explorer-toolbar')
		toolBar.appendChild(this.extraTools);


		let home = document.createElement('span');
		home.classList.add('fsoexplorer-icon','fsoexplorer-home');
		home.onclick = function()
		{
			self.goto('/');
		}
		this.extraTools.appendChild(home);


		let mkdir = document.createElement('span');
		mkdir.classList.add('fsoexplorer-icon','fsoexplorer-folder-add');
		mkdir.onclick = function()
		{
			self.mkdir(prompt("Nombre para la nueva carpeta","nueva carpeta"));
		}
		this.extraTools.appendChild(mkdir);

		this.progressBar=document.createElement('progress');
		this.progressBar.hidden=true;
		toolBar.appendChild(this.progressBar);

		this.view.appendChild(toolBar);
	}

	static toUnits(size)
	{		
		let idx=Math.trunc(Math.log(size) / fsoExplorer.baseUnit);
		idx = idx >= fsoExplorer.units.length ? fsoExplorer.units.length-1 : idx;
		size =  Math.round( 100 * size / Math.pow(1000,idx))/100;

		return ""+size+" "+fsoExplorer.units[idx];
	}

	setTitle(text)
	{
		UI.clear(this.title);
		this.title.appendChild(document.createTextNode(text));
	}

	setSpaceLeft(left,total)
	{
		UI.clear(this.spaceleft);
		this.spaceleft.appendChild(document.createTextNode('Espacio libre:'+fsoExplorer.toUnits(left)+" / "+fsoExplorer.toUnits(total)));
	}

	renderActions(obj)
	{
		let td = document.createElement('td');
		if(obj.name!='..')
		{			
			td.classList.add('fsoexplorer-element-toolbar');
			let del=document.createElement('span');
			del.classList.add('fsoexplorer-icon','fsoexplorer-del');

			let self = this;
			del.onclick=function()
			{
				self.delete(obj);
			};
			td.appendChild(del);
		}
		return td;
	}

	renderIcon(obj)
	{
		let img = document.createElement('a');
		if( obj instanceof fsoDir )
		{
			img.classList.add('fsoexplorer-icon','folder');
		}
		else
		{
			img.classList.add('fsoexplorer-icon','file');
			if(obj.extension!='')
			{			
				img.classList.add(obj.extension);
			}
		}
		return img;
	}

	renderDir(dir)
	{
		let row = document.createElement('tr');
		

		let col = document.createElement('td');
		col.appendChild(this.renderIcon(dir));
		row.appendChild(col);

		col = document.createElement('td');
		col.appendChild(document.createTextNode(dir.name));
		row.appendChild(col);

		let toolcol = this.renderActions(dir);
		row.appendChild(toolcol);

		for(let i in this.plugins )
		{
			if( typeof this.plugins[i].onRenderDir !== 'undefined' )
				typeof this.plugins[i].onRenderDir(this,toolcol,dir);
		}

		let self = this;
		row.ondblclick=function(){
			self.goto(dir);
		};

		return row;
	}

	renderFile(file)
	{
		let row = document.createElement('tr');

		let col = document.createElement('td');
		col.appendChild(this.renderIcon(file));
		row.appendChild(col);

		col = document.createElement('td');
		col.appendChild(document.createTextNode(file.name));
		row.appendChild(col);

		let toolcol = this.renderActions(file);
		row.appendChild(toolcol);

		for(let i in this.plugins )
		{
			if( typeof this.plugins[i].onRenderFile !== 'undefined' )
				typeof this.plugins[i].onRenderFile(this,toolcol,file);
		}

		row.ondblclick=function(){
			file.explore();
		};

		return row;
	}

	render()
	{
		this.setTitle("Contenido de "+this.dir.name);
		this.setSpaceLeft(this.dir.free,this.dir.total);
		UI.clear(this.workSpace);

		let table = document.createElement('table');
		table.classList.add('table');

		let thead = document.createElement('thead');
		let tr = document.createElement('tr');
		let td = document.createElement('td');
		td.appendChild(document.createTextNode('Fichero'))
		td.colSpan=2;
		tr.appendChild(td);
		
		td = document.createElement('td');
		td.appendChild(document.createTextNode('Acciones'))
		tr.appendChild(td);
		thead.appendChild(tr);
		table.appendChild(thead);

		for(let i in this.plugins )
		{
			if( typeof this.plugins[i].beforeRender !== 'undefined' )
				typeof this.plugins[i].beforeRender(this,this.dir);
		}

		let tbody = document.createElement('tbody');
		for(let i in this.dir.childDirs)
		{
			tbody.appendChild(this.renderDir(this.dir.childDirs[i]));
		}
		for(let i in this.dir.childFiles)
		{
			tbody.appendChild(this.renderFile(this.dir.childFiles[i]));
		}
		table.appendChild(tbody);
		this.workSpace.appendChild(table);

		for(let i in this.plugins )
		{
			if( typeof this.plugins[i].onRender !== 'undefined' )
				typeof this.plugins[i].onRender(this,this.dir);
		}
	}

	async upload(e)
	{
		try
		{
			e.preventDefault();
			e.stopPropagation();

			this.progressBar.hidden=false;
			this.progressBar.value=50;
            this.progressBar.max=100;

			var dt = e.dataTransfer;
			var files = dt.files;

			this.progressBar.hidden=false;
			this.progressBar.value=1;

			let self = this;
			await this.dir.upload(files, function(loaded,total){
				if(loaded>0 && total>0)
				{
					this.progressBar.max=total;
					this.progressBar.value=loaded;
				}
				else
				{
					this.progressBar.pulsate();
				}
			});
		}
		catch(ex)
		{
			alert(""+ex);
		}

		this.progressBar.hidden=true;
		this.refresh();
		return false;
	}

	async goto(dir)
	{
		let result = null;

		if(typeof dir == 'string')
		{
			result = await fsoDir.get('/');
		}
		else
		{
			result = await dir.explore();
		}
		
		if(result != null)
		{
			this.dir = result;
			this.render();
		}
	}

	async refresh()
	{
		await this.dir.explore();
		this.render();
	}

	async delete(what)
	{
		if(confirm("Seguro que desea borrar '"+what.name+"'? (Esta operación no se puede deshacer)"))
		{
			try
			{
				await what.delete();				
			}
			catch(ex)
			{
				alert(''+ex);
			}
			
		}
		this.refresh();
	}

	async mkdir(dir)
	{
		if(dir===null || dir==='')
			return;

		if(dir=='.' || dir=='..')
		{
			alert("Nombre de carpeta invalida")
			return;
		}

		try
		{
			await this.dir.mkdir(dir);
		}
		catch(ex)
		{
			alert(''+ex);
		}
		this.refresh();
	}

	addPlugin(id,plugin)
	{
		this.plugins[id]=plugin;
		if(typeof plugin.start !== 'undefined')
			plugin.start(this);
	}

	
}

window.addEventListener('load',fsoExplorer.setup);