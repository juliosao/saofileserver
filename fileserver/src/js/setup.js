//Constructor
class Setup
{
	async createUser(appUser,appUserPw)
	{
		let res = await App.jsonRemoteCall("api/setup/createFirstUser.php",{appUsr:appUser,appPwd:appUserPw});
		return res;
	}
}