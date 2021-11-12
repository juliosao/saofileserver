class fsoExplorerPlayer {
	constructor(tag) {
		this.playlistName = 'playlist';
		this.playlist = [];
		this.explorer = document.getElementById(tag);
		
		if(typeof fsoExplorerPlayer.video == 'undefined')
		{
			fsoExplorerPlayer.video = document.createElement('video');
			fsoExplorerPlayer.audio = document.createElement('audio');
		}
	}
	//fsoPlayer initialization
	static setup() {
		// Adds plugin to the controllers
		for (var i in fsoExplorer.controllers) 
		{
			fsoExplorer.controllers[i].addPlugin('player',new fsoExplorerPlayer())
		}
	}

	start(src)
	{
		this.mainToolBar = document.createElement('span');
		src.extraTools.appendChild(this.mainToolBar);
	}

	beforeRender(src,dir)
	{
		this.playlist = [];
		this.canPlayVideos = false;
		this.canPlayAudios = false;
		UI.clear(this.mainToolBar);
	}

	onRender(src,dir)
	{
		if(this.canPlayAudios)
		{
			let btn = document.createElement('button');
			btn.classList.add('sfs-icon','fsoplayer-icon-audio','w3-button');
			btn.onclick=(() => window.open(App.baseUrl + 'bundles/player/views/index.php?file='+dir.link));
			this.mainToolBar.appendChild(btn);
		}

		if(this.canPlayVideos)
		{
			let btn = document.createElement('button');
			btn.classList.add('sfs-icon','fsoplayer-icon-video','w3-button');
			btn.onclick=(() => window.open(App.baseUrl + 'bundles/player/views/index.php?file='+dir.link+"&data-mode=video"));
			this.mainToolBar.appendChild(btn);
		}

		if(this.playlist.length > 0)
		{
			this.playlistName = dir.name;
			let btn = document.createElement('button');
			btn.classList.add('sfs-icon','fsoplayer-icon-m3u','w3-button');
			btn.onclick=()=>this.getM3u();
			this.mainToolBar.appendChild(btn);
		}
	}

	onRenderFile(src,toolbox,file)
	{
		if( file.extension=='mp3' || file.extension=='ogg' )
		{
			this.playlist.push(App.baseUrl +  'api/fso/download.php?path='+file.link);
			if(fsoExplorerPlayer.audio.canPlayType(file.mime))
			{
				let btn = document.createElement('button');
				btn.classList.add('sfs-icon','fsoplayer-icon-audio','w3-button');
				btn.onclick=(() => window.open(App.baseUrl + 'bundles/player/views/index.php?file='+file.link))
				toolbox.appendChild(btn);
				this.canPlayAudios=true;
			}
		}
		
		if( file.extension=='mp4' || file.extension=='ogv' || file.extension=='webm' || file.extension=='avi'|| file.extension=='mkv' )
		{
			this.playlist.push(App.baseUrl +  'api/fso/download.php?path='+file.link);
			if(fsoExplorerPlayer.video.canPlayType(file.mime))
			{
				let btn = document.createElement('button');
				btn.classList.add('sfs-icon','fsoplayer-icon-video','w3-button');
				btn.onclick=(() => window.open(App.baseUrl + 'bundles/player/views/index.php?file='+file.link+"&data-mode=video"))
				toolbox.appendChild(btn);
				this.canPlayVideos=true;
			}
		}
	}

	getM3u()
	{
		let lines='#EXTM3U\n' + this.playlist.join('\n');

		let obj = document.createElement('a');
		obj.setAttribute('href', 'data:audio/x-mpequrl;charset=utf-8,' + encodeURIComponent(lines));
		obj.setAttribute('download', this.playlistName+".m3u");
	
		obj.style.display = 'none';
		document.body.appendChild(obj);
	
		obj.click();
	
		document.body.removeChild(obj);
	}
}

//Calls init
window.addEventListener('load',fsoExplorerPlayer.setup);
