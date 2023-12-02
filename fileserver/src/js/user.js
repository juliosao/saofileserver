class User
{   
    parse(data)
    {
        this.name = data.name;
        this.mail = data.mail;
        return this;
    }

    static async get(name)
    {       
        let data = await App.jsonRemoteCall("api/user/get.php",{'user':name});
        if( data != null )
        {
            let user = new User();
            return user.parse(data);
        }
        return null;
    }

    static async list()
    {        
        let data = await App.jsonRemoteCall("api/user/list.php");
        let result=[];
        for(let u of data)
        {
            let user = new User();
            result.push(user.parse(u));
        }

        return result;
    }

    async save()
    {        
        let result = await App.jsonRemoteCall("api/user/save.php",this);
        this.parse(result);
        return this;
    }

    async insert()
    {
        let result = await App.jsonRemoteCall("api/user/create.php",this);
        this.parse(result);
        return this;
    }

    async delete()
    {
        let result = await App.jsonRemoteCall("api/user/delete.php",this);
        this.parse(result);
        return this;
    }

    async getGroups()
    {
        let data = await App.jsonRemoteCall("api/user/groups.php",this);
        let result=[];
        for(let g of data)
        {
            let grp = new Group();
            result.push(grp.parse(g));
        }
        return result;
    }

    async addGroup(group)
    {
        let data = await App.jsonRemoteCall("api/user/addgroup.php",{'user':this.name,'group':group});
        let result=[];
        for(let g of data)
        {
            let grp = new Group();
            result.push(grp.parse(g));
        }
        return result;
    }

    async removeGroup(group)
    {
        let data = await App.jsonRemoteCall("api/user/removegroup.php",{'user':this.name,'group':group});
        let result=[];
        for(let g of data)
        {
            let grp = new Group();
            result.push(grp.parse(g));
        }
        return result;
    }
}


