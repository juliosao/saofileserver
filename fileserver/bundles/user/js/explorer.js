function userProperties(tag)
{
	this.tag=tag;
}

//Method definitions
userProperties.prototype={

	constructor:userProperties,
	video:null,

	onBeginRender:function(source,toolbar,data)
	{
        // Adds the button
        var btn=document.createElement('SPAN');
        btn.classList.add('fsoexplorer-icon');
        btn.classList.add('user-toolbar-icon');
        btn.setAttribute('onclick',"window.open('../../bundles/user/views/index.php')");
        toolbar.appendChild(btn);
        pvideo=true;
    },

    onElementRender:function()
    {
    },

    onFinishRender:function()
    {

    }
}

//initialization
userProperties.setup=function()
{
	// Adds plugin to the controllers
	var explorers=document.getElementsByClassName("fso-explorer");
	for( var i=0; i<explorers.length; i++ )
	{
		fsoExplorer.controllers[explorers[i].id].appendRenderListener(new userProperties(explorers[i].id));
	}
}


//Calls init
window.addEventListener('load',userProperties.setup);