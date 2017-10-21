<?php
require_once('../../lib/Util.php');

class logout extends app\HTMLApp
{
	function __construct()
	{
		parent::__construct(true);
		$this->title='player';
        $this->msg="Cerrando Sesion";
        auth\Auth::logout();
	}

	function putBody()
	{		
		?>
		<h1>Sesion cerrada</h1>
        <h2>Haga clic <a href="login.php">aqu&iacute;</a> para volver a iniciarla</h2>
		<?php
	}
}

$b= new logout();
$b->run();
