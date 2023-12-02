import {App} from './app.js';

export class Auth extends App
{
    static async login(user,pass)
    {
        var data = await App.jsonRemoteCall("api/auth/login.php",{'usr':user, 'pwd':pass});
        return new Auth(data);
    }

    async logout()
    {
        var result = await App.jsonRemoteCall("api/auth/logout.php",null);
        return result;
    }
}
