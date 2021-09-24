<?php

namespace app;

use \Cfg;

//Represents a HTMLApp extends App
abstract class HTMLApp extends App
{

	//Puts HTMLApp extends App header
	public static function putHeaders($title)
	{
		?>
		<title><?=$title ?></title>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?=self::getAppUrl('styles/w3.css');?>" >
		<link rel="stylesheet" href="<?=self::getAppUrl('styles/main.css');?>" >
		<script src="<?=self::getAppUrl('js/App.js');?>" ></script>
		<script src="<?=self::getAppUrl('js/Ui.js');?>" ></script>
		<script type="text/javascript" >
			App.main = "<?=App::getAppURL(Cfg::get()->app->main);?>";
		</script>
		<?php
	}

}