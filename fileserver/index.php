<?php
require_once('lib/Util.php');

use app\App;
use app\HTMLApp;
use auth\User;
use database\Database;

if(!User::created())
{
    error_log("Tenemos que hacer setup");
    header('Location: '.App::getAppURL().Cfg::get()->app->setup,true,302);
}

class Main extends HTMLApp
{
    function __construct()
    {
        $this->scripts[]='js/auth.js';
    }

    function header($args)
    {
?>
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
<?php
    }

    function body($args)
    {
?>
        <div class="w3-animate-opacity w3-modal " style="display:block">
            <div class="w3-modal-content w3-padding w3-border w3-card">
                <h1>Login</h1>
                <div class="form">
                    <div class="w3-margin">
                        <label for="usr">Usuario:</label>
                        <input class="w3-input" id="usr" />
                    </div>
                    <div class="w3-margin">
                        <label for="pwd">Password:</label>
                        <input class="w3-input" type="password" id="pwd" />
                    </div>
                    <div class="w3-margin w3-center">
                        <button class="w3-button w3-blue w3-row" onclick="login()" >Login</button>
                        <input type="reset" class="w3-button w3-row" />
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}

$m = new Main();
$m->run();
