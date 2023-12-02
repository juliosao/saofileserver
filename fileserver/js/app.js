// Needed for some stuff. Do not remove
export class App
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
	
	static login(err)
	{
		window.location.href = "../index.php"
	}

	static setup()
	{
		App.appName = 'sfs';		
		App.main = "views/explorer/index.php";
		App.loginUrl = "index.php";

		const whereAmI = window.location.pathname.split('/');
		const idx = whereAmI.indexOf(App.appName);
		App.baseUrl = whereAmI.slice(0,idx+1).join('/')+'/';
	}
}

App.setup();