class Group
{
	constructor()
	{
        this.name = name;
    }
    
    parse(data)
    {
        this.name = data.name;
        return this;
    }

    static async get(name)
    {       
        let data = await App.jsonRemoteCall(App.baseUrl+"bundles/groups/api/load.php",{'name':name});
        if( data != null )
        {
            let group = new Group();
            return group.parse(data);
        }
        return null;
    }

    static async list()
    {        
        let data = await App.jsonRemoteCall(App.baseUrl+"bundles/groups/api/list.php");
        let result=[];
        for(let g of data)
        {
            let group = new Group();
            result.push(group.parse(g));
        }

        return result;
    }

    async save()
    {        
        let result = await App.jsonRemoteCall(App.baseUrl+"bundles/groups/api/save.php",this);
        this.parse(result);
        return this;
    }

    async insert()
    {
        let result = await App.jsonRemoteCall(App.baseUrl+"bundles/groups/api/create.php",this);
        this.parse(result);
        return this;
    }

    async delete()
    {
        let result = await App.jsonRemoteCall(App.baseUrl+"bundles/groups/api/delete.php",this);
        this.parse(result);
        return this;
    }
}


