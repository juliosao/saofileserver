//Constructor
class setup extends RemoteObject
{
	createDataBase(user,pass)
	{
		var self = this;
		this.jsonRemoteCall("api/createDatabase.php",{usr:user,pwd:pass},
			function(data)
			{
				self.data.childs={};

				// Paint folders first
				for(var i in data.dirs)
				{
					self.data.childs[data.dirs[i].name] = new fsoDir(data.dirs[i],self.listener);
				}

				for(var i in data.files)
				{
					self.data.childs[data.files[i].name] = new fsoFile(data.files[i],self.listener);
				}

				self.data.free = data.free;
				self.data.total = data.total;
				self.data.link = data.link;
				self.data.name = data.name;
				
				self.listener.onRefresh(self);
			}
		);
	}
}