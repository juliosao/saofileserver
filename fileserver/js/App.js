// Needed for some stuff. Do not remove
class App
{
	static async jsonRemoteCall(url,data)
	{
		const response = await fetch(
			App.baseUrl+url,
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
				App.login(response.statusText);

			throw response.statusText;
		}
	
		return response.json();
	};
	
	static async blobRemoteCall(url,data)
	{
		const response = await fetch(
			App.baseUrl+url,
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
				App.login(response.statusText);
			throw response.status,response.statusText;
		}
	
		return response.blob();
	};
	
	static async plainRemoteCall(url,data)
	{
		const response = await fetch(
			App.baseUrl+url,
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
				App.login(response.statusText);

			throw response.status,response.statusText;
		}
	
		return response.text();
	};
	
	/*
	{
	App.baseUrl = "<?=self::getAppUrl();?>";
	App.main = "<?=App::getAppURL(Cfg::get()->app->main);?>";			
	App.loginUrl = "<?=App::getAppURL(Cfg::get()->app->loginUrl);?>";
	*/
	static login(err)
	{
		window.location.href = App.baseUrl+'?err='+encodeURIComponent(err);
	}

	static setup()
	{
		var current = document.currentScript.src;
		var base = current.split('/').slice(0,-2);
		App.baseUrl = base.join('/')+"/"
	}

}

App.setup();