class CurrentUserCfg {
	constructor(tag) {

		this.explorer = document.getElementById(tag);
	
	}
	//fsoPlayer initialization
	static async setup() {
		// Adds plugin to the controllers
		for (var i in fsoExplorer.controllers) 
		{
			fsoExplorer.controllers[i].addPlugin('currentUser',new CurrentUserCfg())
		}
	}

	start(src)
	{
        this.mainToolBar = document.createElement('span');

        let elem = document.createElement('a');
        let span = document.createElement('span');
        elem.href='../user/index.php'
        span.classList.add('fsoexplorer-icon','fsoexplorer-icon-user');
        elem.appendChild(span);
        this.mainToolBar.appendChild(elem);

		src.extraTools.appendChild(this.mainToolBar);
	}
}

//Calls init
window.addEventListener('load',CurrentUserCfg.setup);
