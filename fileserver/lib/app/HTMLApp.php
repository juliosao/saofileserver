<?php

namespace app;

 //Represents a HTMLApp extends App
 abstract class HTMLApp extends App{
	public abstract function putBody();

	public function __construct($doAuth=false)
	{
		parent::__construct($doAuth);
		$this->title='HTMLApp';
		$this->mods=array();		
 		$this->styles=array();
		$this->scripts=array();
		$this->styles=array();
	}

	//Adds a script to a HTMLApp extends App
	public function addScript($script)
	{
		
 		$this->scripts[]=$script;
		return $this;
	}

	//Adds a CSS to a HTMLApp extends App
	public function addStyle($style)
	{
		
 		$this->styles[]=$style;
		return $this;
	}

	//Puts HTMLApp extends App header
	public function putHeader()
	{
		?>
<!DOCTYPE html>
<html>
    <head>
		<title><?=$this->title ?></title>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<?php
		foreach($this->scripts as $script)
		{
		?>
			<script type="text/javascript" src="<?=$script ?>" ></script>
		<?php
		}
		
		foreach($this->styles as $style)
		{
		?>
			<link rel="stylesheet" type="text/css" href="<?=$style?>"/>
		<?php
		}
		?>
	</head>
	<body><?php
		return $this;
	}

	

	public function putFooter()
	{
		?>
	</body>
</html>
		<?php
	}

	//Runs HTMLApp
    public function run()
    {
        $this->putHeader();
		$this->putBody();
		$this->putFooter();
    }

 }