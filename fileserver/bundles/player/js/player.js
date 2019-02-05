class fsoPlayer extends RemoteObject
{
	constructor(tag) {
		super({'tag':tag});
		this.playIdx = 0;
		this.playlist = [];
		this.container=document.getElementById(tag);
		this.path = '';
	}
	//fsoPlayer initialization
	static setup() {
		fsoPlayer.players = [];
		// Adds plugin to the controllers
		var players = document.getElementsByClassName("fso-player");
		for (var i = 0; i < players.length; i++) {
			var player = players[i];
			var p = new fsoPlayer(player.id);
			fsoPlayer.players[player.id] = p;
			p.load(player.getAttribute('data-src'), player.getAttribute('data-mode'));
		}
	}

	//Gets directory data
	load(path,mode)
	{
		this.data.path=path;
		this.data.mode=mode;
		
		var self=this;

		this.jsonRemoteCall("../explorer/api/explore.php",{path:this.data.path},
			function(data)
			{		
				// Clears old data
				while (self.container.firstChild) {
					self.container.removeChild(self.container.firstChild);
				}

				// Puts UI
				self.loadPlayList(data);

				self.putToolBar();

				self.putPlayer(self.data.mode!='audio');				
				
				if(self.playlist.length!=1)
					self.putPlayList();
				
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
			for(var i in data.files)
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
		var toolbar=document.createElement('div');
		toolbar.id=this.tag+'-toolbar';
		toolbar.className='fsoplayer-toolbar';
		var title=document.createElement('h1');
		if(this.data.path!='')
			title.appendChild(document.createTextNode(this.data.path));
		else
			title.appendChild(document.createTextNode('/'));
		
		toolbar.append(title);

		this.container.appendChild(toolbar);
		return toolbar;
	}

	putPlayList()
	{
		//Paint dirs and files
		var lst=document.createElement('table');
		lst.classList.add('table-striped')
		lst.classList.add('table-responsive-sm');
		lst.classList.add('table');
		lst.classList.add('fso-player-playlist');

		// Table header
		var hdr = document.createElement('thead');
		var td = document.createElement('th');
		td.appendChild(document.createTextNode('Nombre'));
		td.colSpan=2;
		hdr.appendChild(td);
		lst.appendChild(hdr);

		// Table body
		var tbody=document.createElement('tbody');
		for(var i in this.playlist)
		{
			var me=this;
			var tr = document.createElement('tr');
			tr.id=this.tag+'-playitem-'+i;
			tr.setAttribute('data-idx',i);
			tr.addEventListener('click',function(e){
				me.play(parseInt(e.currentTarget.getAttribute('data-idx')));
			});

			// Number
			td = document.createElement('td');
			td.appendChild(document.createTextNode(''+(parseInt(i)+1)));
			tr.appendChild(td);

			// Name
			td = document.createElement('td');
			td.appendChild(document.createTextNode(decodeURIComponent(this.playlist[i].name)));
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
			var me=this;
			this.player.addEventListener("ended", function(){
				me.play( (me.playIdx+1) % me.playlist.length );
			}, false);
		}
	}

	play(idx)
	{		
		for(var i in this.playlist)
		{
			var elem = document.getElementById(this.tag+'-playitem-'+i);
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
