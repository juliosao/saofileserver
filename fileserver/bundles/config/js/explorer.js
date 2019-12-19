class MainCfg {
	constructor(tag) {

		this.explorer = document.getElementById(tag);
	
	}
	//fsoPlayer initialization
	static async setup() {
		// Adds plugin to the controllers
		for (var i in fsoExplorer.controllers) 
		{
			fsoExplorer.controllers[i].addPlugin('mainCfg',new MainCfg())
		}
	}

	start(src)
	{
        this.mainToolBar = document.createElement('span');

        let elem = document.createElement('a');
        let span = document.createElement('span');
        elem.href='../config/index.php'
        span.classList.add('fsoexplorer-icon','fsoexplorer-icon-config');
        elem.appendChild(span);
        this.mainToolBar.appendChild(elem);

		src.extraTools.appendChild(this.mainToolBar);
	}
}

//Calls init
window.addEventListener('load',MainCfg.setup);
