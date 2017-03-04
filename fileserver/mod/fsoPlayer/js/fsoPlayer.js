function fsoPlayer(tag)
{
		this.tag=tag;
}

//Method definitions
fsoPlayer.prototype={

	constructor:fsoPlayer,

	onBeginRender:function(source)
	{
	},

	onElementRender:function(source,elem,data,isFile)
	{
		if(isFile)
		{
			if( data.extension=='mp4' || data.extension=='ogv' || data.extension=='webm' )
			{
				//Gets toolbar
				var tools=elem.getElementsByClassName('fsoexplorer-toolbar');
				var toolbar=tools[0];

				var player=document.createElement('SPAN');
				player.classList.add('fsoexplorer-toolbar-icon');
 				player.classList.add('fsopalyer-icon');
				player.setAttribute('onclick',"window.open('./mod/fsoPlayer/views/index.php?file="+encodeURIComponent(data.link)+"')");
				toolbar.appendChild(player);
			}
		}
	},

	onFinishRender(source)
	{
	}


}

//fsoPlayer initialization
fsoPlayer.setup=function()
{
	var explorers=document.getElementsByClassName("fso-explorer");
	for( var i=0; i<explorers.length; i++ )
	{
		fsoExplorer.controllers[explorers[i].id].appendRenderListener(new fsoPlayer(explorers[i].id));
	}
}


//Calls init
window.addEventListener('load',fsoPlayer.setup);
