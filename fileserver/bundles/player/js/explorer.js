function fsoExplorerPlayer(tag)
{
	this.playlist=[]
	this.tag=tag;
}

//Method definitions
fsoExplorerPlayer.prototype={

	constructor:fsoExplorerPlayer,
	video:null,

	onBeginRender:function(source,toolbar,data)
	{		
		var pvideo=false;
		var paudio=false;
		if(this.video==null)	
			this.video=document.createElement('video');

		for(var i in data.files)
		{
			if( pvideo==false && (data.files[i].extension=='mp4' || data.files[i].extension=='ogv' || data.files[i].extension=='webm' || data.files[i].extension=='avi') )
			{				
				if( this.video.canPlayType(data.files[i].mime))
				{
					// Adds the button
					var player=document.createElement('SPAN');
					player.classList.add('fsoexplorer-icon');
					player.classList.add('fsoplayer-icon-video');
					player.setAttribute('onclick',"window.open('../../bundles/player/views/index.php?file="+data.path+"&mode=video')");
					toolbar.appendChild(player);
					pvideo=true;
				}
			}
			else if( paudio==false && (data.files[i].extension=='mp3' || data.files[i].extension=='ogg' || data.files[i].extension=='flac') )
			{								
				// Adds the button
				var player=document.createElement('SPAN');
				player.classList.add('fsoexplorer-icon');
 				player.classList.add('fsoplayer-icon-audio');
				player.setAttribute('onclick',"window.open('../../bundles/player/views/index.php?file="+data.path+"&mode=audio')");
				toolbar.appendChild(player);
				paudio=true;
			}			
		}
	},

	onElementRender:function(source,elem,data,isFile)
	{
		if(isFile)
		{
			// If file is supported adds a play button
			if( data.extension=='mp4' || data.extension=='ogg' || data.extension=='flac' ) 
			{
				this.putElementPlayer(elem,data,false);
			}
			else if( data.extension=='mp4' || data.extension=='ogv' || data.extension=='webm' || data.extension=='avi' )
			{
				if(this.video.canPlayType(data.mime))
				{
					this.putElementPlayer(elem,data,true);
				}
			}
		}
	},

	putElementPlayer(elem,data,video)
	{
		// Gets toolbar
		var tools=elem.getElementsByClassName('fsoexplorer-toolbar');
		var toolbar=tools[0];

		// Adds the button
		var player=document.createElement('SPAN');
		player.classList.add('fsoexplorer-icon');
		player.classList.add('fsoplayer-icon-'+ (video ? 'video' : 'audio') );
		player.setAttribute('onclick',"window.open('../../bundles/player/views/index.php?file="+encodeURIComponent(data.link)+"')");
		toolbar.appendChild(player);
	},

	onFinishRender(source)
	{
		//Not used
	},

	putPlayer(playerDiv)
	{

	}
}

//fsoPlayer initialization
fsoExplorerPlayer.setup=function()
{
	// Adds plugin to the controllers
	var explorers=document.getElementsByClassName("fso-explorer");
	for( var i=0; i<explorers.length; i++ )
	{
		fsoExplorer.controllers[explorers[i].id].appendRenderListener(new fsoExplorerPlayer(explorers[i].id));
	}
}


//Calls init
window.addEventListener('load',fsoExplorerPlayer.setup);
