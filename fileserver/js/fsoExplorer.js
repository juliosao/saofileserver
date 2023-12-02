import { fsoDir, fsoFile } from "./fso.js";
import * as UI from "./ui.js";

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
export class fsoExplorer  
{
	static setup()
	{		
		fsoExplorer.units = ['bytes','Kb','Mb','Gb','Tb','Pb','Eb'];
		fsoExplorer.baseUnit = Math.log(1000);
		fsoExplorer.controllers = [];
		fsoExplorer.touchScreen = window.ontouchstart !== undefined;

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
		this.workSpace.classList.add('w3-container');
		this.view.appendChild(this.workSpace);
		this.selectedItems = [];
		this.plugins=[];	

		var self = this;
		fsoDir.get('/').then(function (result){
			self.dir = result;
			self.render();
		})

		view.addEventListener("dragover", fsoExplorer.cancel);
        view.addEventListener("dragenter", fsoExplorer.cancel);
        view.addEventListener("drop", (ev)=>{
			ev.preventDefault(ev);
			ev.stopPropagation(ev);
			var files = ev.dataTransfer.files;
			this.upload(files);
		} );
		
	}

	createToolBar()
	{
		let toolBar = document.createElement('div');
		toolBar.classList.add('w3-panel');

		this.title = document.createElement('h1');
		toolBar.appendChild(this.title);

		this.spaceleft = document.createElement('h2');
		toolBar.appendChild(this.spaceleft);
		
		this.extraTools = document.createElement('div');
		this.extraTools.classList.add('fso-explorer-toolbar')
		toolBar.appendChild(this.extraTools);

		let btn = document.createElement('button');
		btn.classList.add('sfs-icon','fsoexplorer-home','w3-button');
		btn.onclick = (() => this.goHome());
		this.extraTools.appendChild(btn);

		btn = document.createElement('button');
		btn.classList.add('sfs-icon','fsoexplorer-folder-add','w3-button');
		btn.onclick = (() => this.mkdir());
		this.extraTools.appendChild(btn);

		btn = document.createElement('button');
		btn.classList.add('sfs-icon','fsoexplorer-upload','w3-button');
		btn.onclick = (() => this.uploadFile());
		this.extraTools.appendChild(btn);

		btn = document.createElement('button');
		btn.classList.add('sfs-icon','fsoexplorer-del','w3-button');
		btn.onclick = () => this.deleteSelected();
		this.extraTools.appendChild(btn);

		btn = document.createElement('button');
		btn.classList.add('sfs-icon','fsoexplorer-config','w3-button');
		btn.onclick = (() => window.open('../config/index.php'))  ;
		this.extraTools.appendChild(btn);

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
		let td = document.createElement('div');
		td.classList.add('sfs-tools');
		if(obj.name!='..')
		{						
			let del=document.createElement('button');
			del.classList.add('sfs-icon','fsoexplorer-del','w3-button');

			let self = this;
			del.onclick=function()
			{
				self.delete(obj);
			};
			td.appendChild(del);
		}
		return td;
	}

	render()
	{
		this.setTitle("Contenido de "+this.dir.name);
		this.setSpaceLeft(this.dir.free,this.dir.total);
		UI.clear(this.workSpace);

		let list = document.createElement('ul');
		list.classList.add('w3-ul','w3-border','sfs-icon-list');		

		for(let i in this.plugins )
		{
			if( typeof this.plugins[i].beforeRender !== 'undefined' )
				typeof this.plugins[i].beforeRender(this,this.dir);
		}

		for(let i in this.dir.childDirs)
		{
			const elem = new fsoExplorerItem(this,this.dir.childDirs[i])
			list.appendChild(elem.render());
		}
		for(let i in this.dir.childFiles)
		{
			const elem = new fsoExplorerItem(this,this.dir.childFiles[i])
			list.appendChild(elem.render());
		}

		this.workSpace.appendChild(list);

		for(let i in this.plugins )
		{
			if( typeof this.plugins[i].onRender !== 'undefined' )
				typeof this.plugins[i].onRender(this,this.dir);
		}		
	}

	select(what, multiple)
	{		
		const pos = this.selectedItems.indexOf(what);

		if(multiple == false)
		{
			while(this.selectedItems.length > 0)
			{
				const e = this.selectedItems[this.selectedItems.length-1];
				this.selectedItems.pop();
				e.setSelected(false);

			}
			
			what.setSelected(pos<0);
			if(pos<0)
				this.selectedItems.push(what);
		}
		else
		{			
			if(pos>-1)
			{
				what.setSelected(false);
				this.selectedItems.splice(pos,1);
			}
			else
			{
				what.setSelected(true);
				this.selectedItems.push(what);
			}
		}
	}

	async deleteSelected()
	{
		const count = this.selectedItems.length;
		if(count == 0)
		{
			alert("You need to select some items in order to remove them");
			return;
		}

		if(!confirm("Are you sure to remove "+count+" items?"))
		{
			return;
		}

		this.progressBar.hidden = false;
		this.progressBar.value = 0;
		this.progressBar.max = count;
		for(const elem of this.selectedItems)
		{
			try
			{				
				await elem.src.delete();				
			}
			catch(ex)
			{
				alert(ex);
			}
			this.progressBar.value++;
		}
		this.progressBar.hidden = true;
		this.refresh();
	}

	uploadFile()
	{
		const input = document.createElement('input');
		input.type = "file";
		input.onchange = (ev)=>{
			this.upload(input.files);
		}

		input.click();
	}

	/**
	 * Uploads a bunch of files to the server
	 * @param {*} files Files field as input type=files returns
	 */
	async upload(files)
	{
		this.progressBar.max=100;
		this.progressBar.hidden=false;
		this.progressBar.value=1;

		this.dir.upload(files, (loaded,total)=>{
			if(loaded>0 && total>0)
			{
				this.progressBar.max=total;
				this.progressBar.value=loaded;
			}
			else
			{
				this.progressBar.pulsate();
			}
		}).catch((ex)=>{
			alert(""+ex);
		}).then(()=>{
			this.progressBar.max=this.progressBar.value=100;
		}).finally(()=>{
			this.refresh();
			this.progressBar.hidden=true;
		});
	}

	goHome()
	{
		this.goto('/');
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

	async mkdir()
	{
		let dir = prompt("Nombre para la nueva carpeta","nueva carpeta");
		
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

export class fsoExplorerItem
{
	constructor(explorer,src)
	{
		this.explorer = explorer;
		this.src = src;
		this.row = null;
		this.selected = false;
	}

	setSelected(status)
	{
		if(status)
			this.row.classList.add('fsoexplorer-selected');
		else
			this.row.classList.remove('fsoexplorer-selected');
	}

	getSelected()
	{
		return this.selected;
	}

	onclick(ev)
	{
		this.explorer.select(this,ev.ctrlKey);
	}

	getElement()
	{
		return this.src;
	}

	renderIcon()
	{	
		let td = document.createElement('div');
		td.classList.add('sfs-tools');

		let img = document.createElement('div');
		if( this.src instanceof fsoDir )
		{
			img.classList.add('sfs-icon','folder');
		}
		else
		{
			img.classList.add('sfs-icon','file');
			if(this.src.extension!='')
			{			
				img.classList.add(this.src.extension);
			}
		}
		td.appendChild(img);
		
		return td;
	}

	renderName()
	{
		let spn = document.createElement('div');
		spn.classList.add('sfs-icon-name');
		spn.appendChild(document.createTextNode(this.src.name));
		return spn;
	}

	render()
	{
		this.row = document.createElement('li');
		this.row.classList.add('w3-padding');
		this.row.appendChild(this.renderIcon());
		this.row.appendChild(this.renderName());					
		
		this.row.onclick = (ev)=>this.onclick(ev);

		if(this.src instanceof fsoDir)
		{
			this.row.ondblclick=()=>this.explorer.goto(this.src);
		}
		else
		{
			this.row.ondblclick=()=>this.src.explore()
		};
		
		return this.row;
	}
}

fsoExplorer.setup();
