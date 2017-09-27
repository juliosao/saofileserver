<?php

require('../../lib/Util.php');

class Setup extends app\HTMLApp
{
	function putBody()
	{
        ?>
		<h1>Setup</h1>
		<form method="POST" action="<?=app\App::getAppURL().'api/admin/setup.php' ?>">
            <label for="usr">Usuario de la BBDD:</label><input name="usr" /><br/>
            <label for="pwd">Password de la BBDD:</label><input name="pwd" /><br/>
            <label for="appUsr">Password de la App:</label><input name="appUsr" /><br/>
            <label for="appPwd">Password de la App:</label><input name="appPwd" /><br/>
			<input type="submit" /><input type="reset"/>
        </form>
        <?php
    }
}

$s = new Setup();
$s->run();