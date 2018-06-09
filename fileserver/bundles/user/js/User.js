class User
{
    constructor(id,mail)
    {
        this.id=id;
        this.mail=mail;
    }

    static parse($data)
    {
        return new User($data.id,$data.mail);
    }

    static load(id,callback)
    {
        var data = new FormData();
		data.append('id', id);
        		
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(data) {
			if (this.readyState == 4 && this.status == 200) {
                var res=JSON.parse(this.responseText)
                if(res.ok)
                    callback(new User(res.usr.id,res.usr.mail));               
                else
                    callback(res);
			}
        };
        
		xhttp.open("POST", "../api/load.php", true);
		xhttp.send(data);
    }

    save(callback)
    {
        var data = new FormData();
		data.append('id', this.id);
        data.append('mail', this.mail);

        if( typeof this.pw != 'undefined' && this.pw != "")
            data.append('pw',this.pw);

        if( typeof this.pw2 != 'undefined' && this.pw2 != "")
            data.append('pw2',this.pw2);
        		
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(data) {
			if (this.readyState == 4 && this.status == 200) {
                var res=JSON.parse(this.responseText)
                if(res.ok)
                    callback(new User(res.usr.id,res.usr.mail));               
                else
                    callback(res);
			}
        };
        
		xhttp.open("POST", "../api/save.php", true);
		xhttp.send(data);
    }


}