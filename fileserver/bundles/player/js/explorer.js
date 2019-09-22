class fsoExplorerPlayer {
	constructor(tag) {
		this.playlist = [];
		this.explorer = document.getElementById(tag);
		
		if(typeof fsoExplorerPlayer.video == 'undefined')
		{
			fsoExplorerPlayer.video = document.createElement('video');
			fsoExplorerPlayer.audio = document.createElement('audio');
		}
	}
	//fsoPlayer initialization
	static async setup() {
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
		this.canPlayVideos = false;
		this.canPlayAudios = false;
		UI.clear(this.mainToolBar);
	}

	onRender(src,dir)
	{
		if(this.canPlayAudios)
		{
			let elem = document.createElement('a');
			let span = document.createElement('span');
			elem.href='../player/index.php?file='+dir.link;
			span.classList.add('fsoexplorer-icon','fsoplayer-icon-audio');
			elem.appendChild(span);
			this.mainToolBar.appendChild(elem);
		}

		if(this.canPlayVideos)
		{
			let elem = document.createElement('a');
			let span = document.createElement('span');
			elem.href='../player/index.php?file='+dir.link+"&data-mode=video";
			span.classList.add('fsoexplorer-icon','fsoplayer-icon-video');
			elem.appendChild(span);
			this.mainToolBar.appendChild(elem);
		}
	}

	onRenderFile(src,toolbox,file)
	{
		if( file.extension=='mp3' || file.extension=='ogg' )
		{
			if(fsoExplorerPlayer.audio.canPlayType(file.mime))
			{
				let elem = document.createElement('a');
				let span = document.createElement('span');
				elem.href='../player/index.php?file='+file.link;
				span.classList.add('fsoexplorer-icon','fsoplayer-icon-audio');
				elem.appendChild(span);
				toolbox.appendChild(elem);
				this.canPlayAudios=true;
			}
		}
		
		if( file.extension=='mp4' || file.extension=='ogv' || file.extension=='webm' || file.extension=='avi' )
		{
			if(fsoExplorerPlayer.video.canPlayType(file.mime))
			{
				let elem = document.createElement('a');
				let span = document.createElement('span');
				elem.href='../player/index.php?file='+file.link+"&data-mode=video";
				span.classList.add('fsoexplorer-icon','fsoplayer-icon-video');
				elem.appendChild(span);
				toolbox.appendChild(elem);
				this.canPlayVideos=true;
			}
		}
	}
}

//Calls init
window.addEventListener('load',fsoExplorerPlayer.setup);
