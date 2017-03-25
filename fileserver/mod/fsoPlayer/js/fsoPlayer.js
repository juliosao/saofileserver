function fsoPlayer(tag)
{
	this.playlist=[]
	this.tag=tag;
}

//Method definitions
fsoPlayer.prototype={

	constructor:fsoPlayer,

	onBeginRender:function(source,toolbar,data)
	{
		for(var i in data.files)
		{
			if( data.files[i].extension=='mp4' || data.files[i].extension=='ogv' || data.files[i].extension=='webm' 
				|| data.files[i].extension=='mp3' || data.files[i].extension=='ogg' )
			{								
				// Adds the button
				var player=document.createElement('SPAN');
				player.classList.add('fsoexplorer-icon');
 				player.classList.add('fsoplayer-icon');
				player.setAttribute('onclick',"window.open('./mod/fsoPlayer/views/index.php?file="+encodeURIComponent(data.path)+"')");
				toolbar.appendChild(player);
				break;
			}
		}

	},

	onElementRender:function(source,elem,data,isFile)
	{
		if(isFile)
		{
			// If file is supported adds a play button
			if( data.extension=='mp4' || data.extension=='ogv' || data.extension=='webm' || data.extension=='mp3' || data.extension=='ogg' )
			{
				// Gets toolbar
				var tools=elem.getElementsByClassName('fsoexplorer-toolbar');
				var toolbar=tools[0];

				// Adds the button
				var player=document.createElement('SPAN');
				player.classList.add('fsoexplorer-icon');
 				player.classList.add('fsoplayer-icon');
				player.setAttribute('onclick',"window.open('./mod/fsoPlayer/views/index.php?file="+encodeURIComponent(data.link)+"')");
				toolbar.appendChild(player);
			}
		}
	},

	onFinishRender(source)
	{
		//Not used
	}


}

//fsoPlayer initialization
fsoPlayer.setup=function()
{
	// Adds plugin to the controllers
	var explorers=document.getElementsByClassName("fso-explorer");
	for( var i=0; i<explorers.length; i++ )
	{
		fsoExplorer.controllers[explorers[i].id].appendRenderListener(new fsoPlayer(explorers[i].id));
	}
}


//Calls init
window.addEventListener('load',fsoPlayer.setup);
