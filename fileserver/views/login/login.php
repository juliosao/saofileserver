<?php
require_once('../../lib/Util.php');

class login extends HTMLApp
{
	function __construct()
	{
		parent::__construct();
		$this->title='player';
		$this->msg="Introduzca usuario y contrase&ntilde;a";
		$this->redirect=isset($_REQUEST['p']) ? App::getAppURL().$_REQUEST['p'] : App::getAppURL().Cfg::get()->app->main;

		if($this->check()==true)
		{
			header('Location: '.$this->redirect,true,302);
			?>
<html>
	<head>
		<meta http-equiv="refresh" content="2;url=<?=$this->redirect?>/"> 
	</head>
	<body>Cargando...</body>
</html>
			<?php
			die();
		}
	}

	function check()
	{
		error_log(json_encode($_REQUEST));
		if(!isset($_REQUEST['usr']) || !isset($_REQUEST['pwd']))		
		{
			$this->msg="Usuario o password no definido";
            return false;
        }

        $usr=Auth::checkPassw($_REQUEST['usr'],$_REQUEST['pwd']);

        if($usr)
        {
            Auth::set($usr);
            return true;
        }
        else
        {
			$this->msg="Usuario o password no erroeneos";
            return false;
        }
    }


	function putBody()
	{		
		?>
		<h1>Login</h1>
		<h2><?=$this->msg ?></h2>
		<form method="POST" action="<?=App::getAppURL().'views/login/login.php' ?>">
            <label for="usr">Usuario:</label><input name="usr" />
            <label for="pwd">Password:</label><input name="pwd" />
<?php if(isset($_REQUEST['p'])){ ?>
			<input type="hidden" name="p" value="<?=$_REQUEST['p'] ?>" />			
<?php } ?>
			<input type="submit" /><input type="reset"/>
        </form>
		<?php
	}
}

$b= new login();
$b->run();