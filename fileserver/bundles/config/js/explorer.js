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
		let btn = document.createElement('button');
		btn.classList.add('fsoexplorer-icon','fsoexplorer-icon-config','w3-button');
		btn.onclick=(() => window.open('../config/index.php'));
		src.extraTools.appendChild(btn);
	}
}

//Calls init
window.addEventListener('load',MainCfg.setup);
