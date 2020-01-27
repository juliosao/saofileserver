class User
{   
    parse(data)
    {
        this.id = data.id;
        this.name = data.name;
        this.mail = data.mail;
        return this;
    }

    static async get(id)
    {       
        let data = await App.jsonRemoteCall(App.baseUrl+"bundles/users/api/load.php",{'id':id});
        if( data != null )
        {
            let user = new User();
            return user.parse(data);
        }
        return null;
    }

    static async list()
    {        
        let data = await App.jsonRemoteCall(App.baseUrl+"bundles/users/api/list.php");
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
        let result = await App.jsonRemoteCall(App.baseUrl+"bundles/users/api/save.php",this);
        this.parse(result);
        return this;
    }

    async insert()
    {
        let result = await App.jsonRemoteCall(App.baseUrl+"bundles/users/api/create.php",this);
        this.parse(result);
        return this;
    }

    async delete()
    {
        let result = await App.jsonRemoteCall(App.baseUrl+"bundles/users/api/delete.php",this);
        this.parse(result);
        return this;
    }

    async getGroups()
    {
        let data = await App.jsonRemoteCall(App.baseUrl+"bundles/users/api/groups.php",this);
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
        let data = await App.jsonRemoteCall(App.baseUrl+"bundles/users/api/addgroup.php",{'id':this.id,'group':group});
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
        let data = await App.jsonRemoteCall(App.baseUrl+"bundles/users/api/removegroup.php",{'id':this.id,'group':group});
        let result=[];
        for(let g of data)
        {
            let grp = new Group();
            result.push(grp.parse(g));
        }
        return result;
    }
}


