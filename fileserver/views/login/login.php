<?php
require_once('../../lib/Util.php');
require_once(\App::getAppDir().'cfg/fso.cfg');

class login extends HTMLApp
{
	function __construct()
	{
		parent::__construct();
		$this->title='player';
		$this->redirect=isset($_REQUEST['p']) ? App::getAppURL().$_REQUEST['p'] : null;
		$this->addScript('../../js/fsoPlayer/fsoPlayer.js');
		$this->addStyle('../../styles/fsoPlayer/fsoPlayer.css');
	}

	function putBody()
	{
		?>
        <h1>Login</h1>
        <form method="POST" action="<?=$p.'views/main/login.php' ?>">
            <label for="usr">Usuario:</label><input name="usr" />
            <label for="pwd">Password:</label><input name="pwd" />
<?php if(isset($_REQUEST['p'])){ ?>
            <input type="hidden" name="p" value="<?=$_REQUEST['p'] ?>" />
<?php } ?>
        </form>
		<?php
	}
}

$b= new login();
$b->run();