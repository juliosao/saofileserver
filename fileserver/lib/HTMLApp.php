<?php

//Represents a HTMLApp extends App
abstract class HTMLApp extends App
{

	//Puts HTMLApp extends App header
	public function putHeaders($title)
	{
		?>
		<title><?=$title ?></title>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?=self::getAppUrl('bootstrap/css/bootstrap.min.css');?>" >
        <script src="<?=self::getAppUrl('bootstrap/js/bootstrap.min.js');?>" ></script>
		<script src="<?=self::getAppUrl('js/App.js');?>" ></script>
		<script type="text/javascript" >
			App.baseUrl = "<?=self::getAppUrl();?>";
			App.main = "<?=App::getAppURL(Cfg::get()->app->main);?>";			
			App.loginUrl = "<?=App::getAppURL(Cfg::get()->app->loginUrl);?>";
		</script>
		<?php
	}

}
