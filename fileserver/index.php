<?php
require_once('lib/Util.php');

try
{
    Database::getInstance();
}
catch(Exception $ex)
{
    error_log("Tenemos que hacer setup");
    header('Location: '.App::getAppURL().Cfg::get()->app->setup,true,302);   
}
?>
<!DOCTYPE html>
<html>
    <head>
    <?php HTMLApp::putHeaders('Login'); ?>
    <script type="text/javascript" src="bundles/auth/js/auth.js"></script>
    <script type="text/javascript">
    async function login()
    {
        try
        {
            var auth = await Auth.login(document.getElementById('usr').value,document.getElementById('pwd').value);
            window.location.href=App.main;
        }
        catch(ex)
        {
            alert(""+ex);
        }
        
    }
    </script>
    <body>
        <div class="jumbotron col-sm-6 mx-auto">
            <h1>Login</h1>
            <div class="form">
                <div class="form-group">
                    <label for="usr">Usuario:</label>
                    <input class="form-control" id="usr" />
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input class="form-control" type="password" id="pwd" />
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" onclick="login()" >Login</button>
                    <input type="reset" class="btn" />
                </div>
            </div>
        </div>
    </body>
</html>