class Auth extends RemoteObject
{
    constructor(listener)
    {
        if(typeof listener == 'undefined')
            listener = AuthListener.getInstance();

        super(null,listener);
    }

    login(user,pass)
    {
        var data = {'usr':user, 'pwd':pass};
        this.jsonRemoteCall("api/login.php",data,'onLogin');
    }

    logout()
    {
		this.jsonRemoteCall("api/logout.php",null,'onLogout');
    }
}

class AuthListener extends RemoteListener
{
    static getInstance()
    {
        if(AuthListener.instance == null)
            AuthListener.instance = new AuthListener();

        return AuthListener.instance;
    }

    onLogin()
    {
        window.location.href=App.main;
    }

    onLogout()
    {
    }

    onError(msg)
    {
        alert(msg);
    }
}
AuthListener.instance = null;
