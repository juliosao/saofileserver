// Needed for some stuff. Do not remove
class App
{
	static async jsonRemoteCall(url,data)
	{
		const response = await fetch(
			url,
			{
				method:'POST',
				body: JSON.stringify(data),
				headers:{
					'Content-Type': 'application/json'
				},
				credentials:'include'
			}
		);
		
		if(!response.ok)
		{
			if(response.status == 401) //Unauthorized
				App.login();

			throw new RemoteException(response.status,response.statusText);
		}
	
		return response.json();
	};
	
	static async blobRemoteCall(url,data)
	{
		const response = await fetch(
			url,
			{
				method:'POST',
				body: data,
				headers:{
					'Content-Type': 'application/octect-stream'
				},
				credentials:'include'
			}
		);
		
		if(!response.ok)
		{
			if(response.status == 401) //Unauthorized
				App.login();
			throw new RemoteException(response.status,response.statusText);
		}
	
		return response.blob();
	};
	
	static async plainRemoteCall(url,data)
	{
		const response = await fetch(
			url,
			{
				method:'POST',
				body: data,
				headers:{
					'Content-Type': 'text/plain'
				},
				credentials:'include'
			}
		);
		
		if(!response.ok)
		{
			if(response.status == 401) //Unauthorized
				App.login();

			throw new RemoteException(response.status,response.statusText);
		}
	
		return response.text();
	};
	
	/*
	{
	App.baseUrl = "<?=self::getAppUrl();?>";
	App.main = "<?=App::getAppURL(Cfg::get()->app->main);?>";			
	App.loginUrl = "<?=App::getAppURL(Cfg::get()->app->loginUrl);?>";
	*/
	static login()
	{
		window.location.href = App.baseUrl;
	}

	static setup()
	{
		var current = document.currentScript.src;
		var base = current.split('/').slice(0,-2);
		App.baseUrl = base.join('/')
	}

	static goBundle(bundle)
	{
		window.location.href = App.baseUrl+"bundles/"+bundle;
	}
}

class RemoteException 
{
	constructor(code,msg)
	{
		this.code=code;
		this.msg=msg;
	}

	toString()
	{
		return "Error "+this.code+":"+this.msg;
	}
}

App.setup();