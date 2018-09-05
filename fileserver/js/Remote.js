class Remote
{
	constructor(data)
    {
		for(var i in data)
		{
			this[i]=data[i];
		}   
    }

	static call(url,args,onOK,onKO)
	{
		var data = new FormData();
		for(var i in args)
		{
			data[i]=args[i];
		}
				
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(data) 
		{
			if(this.readyState != 4)
				return;

			switch(this.status)
			{
				case 200:
					onOK(JSON.parse(this.responseText));
					break;
				default:
					if(typeof onKO == 'undefined')
						alert(this.responseText);
					else
						onKO(JSON.parse(this.responseText));
			}
		};
		
		xhttp.open("POST", url, true);
		xhttp.send(data);
	}	
}