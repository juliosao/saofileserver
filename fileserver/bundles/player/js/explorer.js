class fsoExplorerPlayer {
	constructor(tag) {
		this.playlist = [];
		this.tag = tag;
		this.explorer = document.getElementById(tag);
		this.canPlay = false;
		if(typeof fsoExplorerPlayer.video == 'undefined')
		{
			fsoExplorerPlayer.video = document.createElement('video');
			fsoExplorerPlayer.audio = document.createElement('audio');
		}
	}
	//fsoPlayer initialization
	static setup() {
		// Adds plugin to the controllers
		var explorers = document.getElementsByClassName("fso-explorer");
		for (var i = 0; i < explorers.length; i++) {
			fsoExplorer.controllers[explorers[i].id].appendRenderListener(new fsoExplorerPlayer(explorers[i].id));
		}
	}

	onBeginRender(explorer)
	{
		this.canPlay = false;
	}

	onElementRender(explorer,elem,src)
	{
		if(src instanceof fsoFile)
		{
			// If file is supported adds a play button
			if( (src.data.extension=='mp4' || src.data.extension=='ogv' || src.data.extension=='webm' || src.data.extension=='avi') && fsoExplorerPlayer.video.canPlayType(src.data.mime) )
			{
				this.putElementPlayer(elem,src.data,true);
				//TODO: Put a playlist player on main toolbar
				this.canPlay="video";
			}
			else if( ( src.data.extension=='mp4' || src.data.extension=='mp3' || src.data.extension=='ogg' || src.data.extension=='flac' ) && fsoExplorerPlayer.audio.canPlayType(src.data.mime))
			{
				this.putElementPlayer(elem,src.data,false);
				//TODO: Put a playlist player on main toolbar
				if(!this.canPlay)
					this.canPlay="audio";
			}
			
		}
	}

	onFinishRender(explorer)
	{
		if(this.canPlay !== false)
		{
			var player=document.createElement('SPAN');
			player.classList.add('fsoexplorer-icon');
			player.classList.add('fsoplayer-icon-'+ this.canPlay );
			player.setAttribute('onclick',"window.open('../player/index.php?data-mode="+this.canPlay+"&file="+encodeURIComponent(explorer.fso.data.link)+"')");
			explorer.toolbar.appendChild(player);
		}
	}

	putElementPlayer(elem,data,video)
	{
		// Gets toolbar
		var toolbar=elem.tools;

		// Adds the button
		var player=document.createElement('SPAN');
		player.classList.add('fsoexplorer-icon');
		player.classList.add('fsoplayer-icon-'+ (video ? 'video' : 'audio') );
		player.setAttribute('onclick',"window.open('../player/index.php?data-mode="+(video ? 'video' : 'audio')+"file="+encodeURIComponent(data.link)+"')");
		toolbar.appendChild(player);
	}
}

//Calls init
window.addEventListener('load',fsoExplorerPlayer.setup);
