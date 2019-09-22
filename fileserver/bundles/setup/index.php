<?php

require('../../lib/Util.php');

?>
<!DOCTYPE html>
<html>
	<head>
		<?php HTMLApp::putHeaders('SAO-Player'); ?>
		<script type="text/javascript" src="js/setup.js"></script>
        <script type="text/javascript">

            function log(logstr)
            {
                let li = document.createElement('li');
                
                if(typeof logstr=='string')
                    li.appendChild(document.createTextNode(logstr));
                else
                    li.appendChild(logstr);

                document.getElementById('logger').appendChild(li);
            }

            async function setupfn()
            {
                let btnSetup = document.getElementById('setup');
                let dbUsr = document.getElementById('usr').value;
                let dbPass = document.getElementById('pwd').value;
                let appUsr = document.getElementById('appUsr').value;
                let appPass = document.getElementById('appPwd').value;

                btnSetup.hidden=true;
                let s = new Setup(dbUsr,dbPass);
                try
                {
                    UI.clear('logger');
                    log("Creating database...");
                    await s.createDataBase();
                    log("Creating tables...");
                    await s.createTables();
                    log("Creating initial user...");                    
                    await s.createUser(appUsr,appPass);
                    log("Done. You will be redirected to log-in screen.");
                    setTimeout(() => {
                        window.location.href="../../";
                    }, 3000);
                }
                catch(ex)
                {
                    btnSetup.hidden=false;
                    log('Error:'+ex);
                    alert(ex);
                }
            }
        </script>
	</head>
    <body>
        <div class="jumbotron col-sm-6 mx-auto">        
    		<h1>Setup</h1>    
            <div class="form">
                <div class="form-group"><label for="usr">Usuario de la BBDD:</label><input class="form-control" id="usr" /></div>
                <div class="form-group"><label for="pwd">Password de la BBDD:</label><input class="form-control" id="pwd" type="password"/></div>
                <div class="form-group"><label for="appUsr">Usuario de la App:</label><input class="form-control" id="appUsr" /></div>
                <div class="form-group"><label for="appPwd">Password de la App:</label><input class="form-control" id="appPwd" type="password"/></div>
                <div class="form-group"><button id="setup" class="btn btn-primary" onclick="setupfn()">Setup</button></div>
            </div>
            <ul id="logger">
            </ul>
        </div>
    </body>
</html>