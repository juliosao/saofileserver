<?php
require_once('../../lib/Util.php');

class login extends app\HTMLApp
{
	function __construct()
	{
		parent::__construct();
		$this->title='player';
		$this->msg="Introduzca usuario y contrase&ntilde;a";
		$this->redirect=isset($_REQUEST['p']) ? app\App::getAppURL().$_REQUEST['p'] : app\App::getAppURL().Cfg::get()->app->main;

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

        $usr=auth\Auth::checkPassw($_REQUEST['usr'],$_REQUEST['pwd']);

        if($usr)
        {
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
		<div class="jumbotron col-sm-6 mx-auto">
			<h1>Login</h1>
			<h2><?=$this->msg ?></h2>
			<form class="form" method="POST" action="<?=app\App::getAppURL().'views/login/login.php' ?>">
				<div class="form-group">
					<label for="usr">Usuario:</label>
					<input class="form-control" name="usr" />
				</div>
				<div class="form-group">
					<label for="pwd">Password:</label>
					<input class="form-control" type="password" name="pwd" />
				</div>
	<?php if(isset($_REQUEST['p'])){ ?>
				<input type="hidden" name="p" value="<?=$_REQUEST['p'] ?>" />
	<?php } ?>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" /><input type="reset" class="btn" />
				</div>
			</form>
        </div>
		<?php
	}
}

$b= new login();
$b->run();
