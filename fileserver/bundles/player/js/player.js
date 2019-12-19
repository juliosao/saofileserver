class fsoPlayer
{
	constructor(tag) {
		this.tag = tag;
		this.playIdx = 0;
		this.playlist = [];
		this.container=document.getElementById(tag);
		this.path = '';
	}

	//fsoPlayer initialization
	static setup() {
		fsoPlayer.players = [];
		// Adds plugin to the controllers
		let players = document.getElementsByClassName("fso-player");
		for (let i = 0; i < players.length; i++) {
			let player = players[i];
			let p = new fsoPlayer(player.id);
			fsoPlayer.players[player.id] = p;
			p.load(player.getAttribute('data-src'), player.getAttribute('data-mode'));
		}
	}

	//Gets directory data
	load(path,mode)
	{
		this.path=path;
		this.mode=mode;
		
		let self=this;

		App.jsonRemoteCall("../explorer/api/explore.php",{path:this.path}).then(
			function(data)
			{		
				// Clears old data
				while (self.container.firstChild) {
					self.container.removeChild(self.container.firstChild);
				}

				// Puts UI
				self.loadPlayList(data);
				self.putToolBar();
				self.putPlayer(self.mode!='audio');				
				
				if(self.playlist.length!=1)
					self.putPlayList();
				
				if(self.playlist.length>0)
					self.play(self.playIdx);
			}
		);
	}

	loadPlayList(data)
	{
		// Gets tracklist
		this.playlist=[];

		if(data.isDir)
		{
			for(let i in data.files)
			{
				if( (data.files[i].extension=='mp3' || data.files[i].extension=='ogg' || data.files[i].extension=='flac') && this.mode!='video')
				{
					this.playlist.push({ link:data.files[i].link, name:data.files[i].name});	
				}
				else if((data.files[i].extension=='mp4' || data.files[i].extension=='ogv' || data.files[i].extension=='webm') && this.mode!='audio')
				{
					// Video only allows to play one file at time
					this.playlist.push({link:data.files[i].link,name:data.files[i].name});
				}
			}
		}
		else
		{
			if( (data.extension=='mp3' || data.extension=='ogg' || data.extension=='flac') && this.mode!='video')
			{
				this.playlist.push({ link:data.link, name:data.name});	
			}
			else if((data.extension=='mp4' || data.extension=='ogv' || data.extension=='webm') && this.mode!='audio')
			{
				// Video only allows to play one file at time
				this.playlist.push({link:data.link,name:data.name});
			}
		}
	}

	putToolBar()
	{
		let toolbar=document.createElement('div');
		toolbar.id=this.tag+'-toolbar';
		toolbar.className='fsoplayer-toolbar';
		let title=document.createElement('h1');
		if(this.path!='')
			title.appendChild(document.createTextNode(this.path));
		else
			title.appendChild(document.createTextNode('/'));
		
		toolbar.append(title);

		this.container.appendChild(toolbar);
		return toolbar;
	}

	putPlayList()
	{
		//Paint dirs and files
		let lst=document.createElement('table');
		lst.classList.add('w3-striped','w3-responsive','w3-large')		
		lst.classList.add('fso-player-playlist');

		// Table header
		let hdr = document.createElement('thead');
		let td = document.createElement('th');
		td.appendChild(document.createTextNode('Nombre'));
		td.classList.add('w3-padding');
		td.colSpan=2;
		hdr.appendChild(td);
		lst.appendChild(hdr);

		// Table body
		let tbody=document.createElement('tbody');
		for(let i in this.playlist)
		{
			let me=this;
			let tr = document.createElement('tr');
			tr.id=this.tag+'-playitem-'+i;
			tr.setAttribute('data-idx',i);
			tr.onclick=function(e){
				me.play(parseInt(e.currentTarget.getAttribute('data-idx')));
			};

			// Number
			td = document.createElement('td');
			td.appendChild(document.createTextNode(''+(parseInt(i)+1)));
			td.classList.add('w3-padding');
			tr.appendChild(td);

			// Name
			td = document.createElement('td');
			td.appendChild(document.createTextNode(decodeURIComponent(this.playlist[i].name)));
			td.classList.add('w3-padding');
			tr.appendChild(td);

			tbody.appendChild(tr);
		}
		lst.appendChild(tbody);
		this.container.appendChild(lst);
	}

	putPlayer(video)
	{
		if(this.playlist.length>0)
		{			
			// Video and audio have diferent engines
			this.player = video ? document.createElement('video') :document.createElement('audio');
			
			this.player.id=this.tag+'-player';
			this.player.setAttribute('width','100%');				
			this.player.setAttribute('controls','true');
			this.container.appendChild(this.player);
			
			this.player.className='fso-player-controls';

			// Creates sources
			this.src=document.createElement('source');
			this.src.id=this.tag+'-player-src';
			this.player.appendChild(this.src);
			
			// Control for track-end
			let me=this;
			this.player.onended=function(){
				me.play( (me.playIdx+1) % me.playlist.length );
			};
		}
	}

	play(idx)
	{		
		for(let i in this.playlist)
		{
			let elem = document.getElementById(this.tag+'-playitem-'+i);
			if(elem!=null)
			{
				if(i==idx)
					elem.classList.add('selected');
				else
					elem.classList.remove('selected');
			}
		}

		this.src.src='../explorer/api/download.php?path='+this.playlist[idx].link;
		this.player.load();
		this.playIdx=idx;
		self=this;
		setTimeout(function() {
			
			self.player.play();	
		}, 500);
		
	}
}


//Calls init
window.addEventListener('load',fsoPlayer.setup);
