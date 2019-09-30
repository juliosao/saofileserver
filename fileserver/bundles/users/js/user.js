class User
{
	constructor(id,name,mail)
	{
        this.id = id;
        this.name = name;
        this.mail = mail;
    }
    
    static parse(data)
    {
        data=JSON.parse(data);
        return new User(data.id,data.name,data.mail);
    }

    async static get(id)
    {       
        let data = await App.jsonRemoteCall("./api/load.php",{'id':id});
        return User.parse(data);
    }

    static async list()
    {        
        let data = await App.jsonRemoteCall("./api/list.php");
        let result=[];
        for(let i; i<data.length; i++)
            result.push(User.parse(data[i]));

        return result;
    }

    async save()
    {
        let data = {id: this.id, name:this.name, mail:this.mail };

        if( typeof this.pw != 'undefined' && this.pw != "")
            data['pw']=this.pw;

        if( typeof this.pw2 != 'undefined' && this.pw2 != "")
            data['pw2']=this.pw2;

        let result = await App.jsonRemoteCall("./api/load.php",data);

        return result;		
    }


}


