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
        return new User(data.id,data.name,data.mail);
    }

    static async get(id)
    {       
        let data = await App.jsonRemoteCall("../users/api/load.php",{'id':id});
        return User.parse(data);
    }

    static async list()
    {        
        let data = await App.jsonRemoteCall("../users/api/list.php");
        let result=[];
        for(let i in data)
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

        if( typeof this.cpw != 'undefined' && this.cpw != "")
            data['cpw']=this.cpw;

        let result = await App.jsonRemoteCall("../users/api/save.php",data);

        return result;		
    }


}


