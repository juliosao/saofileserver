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
	async load(path,mode)
	{
		this.path=path;
		this.mode=mode;

		let data = await App.jsonRemoteCall("api/fso/explore.php",{path:this.path})
		// Clears old data
		while (this.container.firstChild) {
			this.container.removeChild(this.container.firstChild);
		}

		// Puts UI
		this.loadPlayList(data);
		this.putToolBar();
		this.putPlayer(this.mode!='audio');				
		
		if(this.playlist.length!=1)
			this.putPlayList();
		
		if(this.playlist.length>0)
			this.play(this.playIdx);		
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
		let lst=document.createElement('ul');
		lst.classList.add('w3-striped','w3-ul','w3-large');
		lst.classList.add('fso-player-playlist');

		// Table body
		for(let i in this.playlist)
		{
			let me=this;
			let tr = document.createElement('li');
			tr.classList.add('w3-padding');
			tr.id=this.tag+'-playitem-'+i;
			tr.setAttribute('data-idx',i);
			tr.onclick=function(e){
				me.play(parseInt(e.currentTarget.getAttribute('data-idx')));
			};

			// Number
			let td = document.createElement('div');
			td.appendChild(document.createTextNode(''+(parseInt(i)+1)));
			tr.appendChild(td);

			// Name
			td = document.createElement('div');
			td.appendChild(document.createTextNode(decodeURIComponent(this.playlist[i].name)));
			tr.appendChild(td);

			lst.appendChild(tr);
		}
		
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
			this.player.setAttribute('autoplay','autoplay');
			this.container.appendChild(this.player);
			
			this.player.className='fso-player-controls';

			// Creates sources
			this.src=document.createElement('source');
			this.src.id=this.tag+'-player-src';
			this.player.appendChild(this.src);
			
			// Control for track-end			
			this.player.onended=()=>{
				this.play( (this.playIdx+1) % this.playlist.length );
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

		this.src.src=App.baseUrl +  'api/fso/download.php?path='+this.playlist[idx].link;
		this.player.autoplay = true;
		this.player.load();
		this.playIdx=idx;
		setTimeout(()=>this.player.play(), 500);
		
	}
}


//Calls init
window.addEventListener('load',fsoPlayer.setup);
