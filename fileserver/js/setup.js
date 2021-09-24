//Constructor
class Setup
{
	constructor(user,pass)
	{
		this.user=user;
		this.pass=pass;
	}

	async createDataBase()
	{
		let res = await App.jsonRemoteCall("api/createDatabase.php",{usr:this.user,pwd:this.pass});
		return res;
	}

	async createTables()
	{
		let res = await App.jsonRemoteCall("api/createTables.php",{usr:this.user,pwd:this.pass});
		return res;
	}

	async createUser(appUser,appUserPw)
	{
		let res = await App.jsonRemoteCall("api/createFirstUser.php",{usr:this.user,pwd:this.pass,appUsr:appUser,appPwd:appUserPw});
		return res;
	}
}