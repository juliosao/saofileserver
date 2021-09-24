class Auth extends App
{
    static async login(user,pass)
    {
        var data = await App.jsonRemoteCall("api/auth/api/login.php",{'usr':user, 'pwd':pass});
        return new Auth(data);
    }

    async logout()
    {
        var result = await App.jsonRemoteCall("api/auth/api/logout.php",null);
        return result;
    }
}
