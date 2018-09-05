class User extends Remote
{
    constructor(data)
    {
        super(data);
    }

    static parse(data)
    {
        data=JSON.parse(data);
        return new User(data.id,data.name,data.mail);
    }

    static load(id,callback)
    {       
        Remote.call("../../../api/user/load.php",{'id':id},function(data){
                callback(new User(data))
            });
    }

    static list()
    {
        
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
                    callback(new User(res.id,res.mail));
			}
        };
        
		xhttp.open("POST", "../../../api/user/save.php", true);
		xhttp.send(data);
    }


}