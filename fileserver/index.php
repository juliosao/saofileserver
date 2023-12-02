<!DOCTYPE html>
<html>
	<head>
		<title>SFServer2</title>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles/w3.css">
        <link rel="stylesheet" href="styles/main.css">
        <script type="module">
        import {App} from './js/app.js';
        import {Auth} from './js/auth.js';

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

        btnLogin.onclick=login;
        </script>
    </head>
    <body>
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
                        <button id="btnLogin" class="w3-button w3-blue w3-row" >Login</button>
                        <input type="reset" class="w3-button w3-row" />
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
