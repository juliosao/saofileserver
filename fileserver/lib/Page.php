 <?php
 
 require_once('Mod.php');
 
 class Page{
	public __construct($title)
	{
		$this->title=$title;
		$this->mods=array();
		$this->scripts=array();
		$this->styles=array();
	}
	
	public loadMod($mod)
	{
		$m=Mod::load($mod);
		if($m)
		{
			$this->mods[]=$m;
			array_splice($this->scripts,count($this->scripts),0,$m->getScripts());
		}
		return $this;
	}
	
	public addScript($script)
	{
		$this->scripts[]=$script;
	}
	
	
	
	public putHeader()
	{
		?>
<!DOCTYPE html>
<html>
    <head>
		<title><?=$this->title ?></script>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">  
        <meta name="viewport" content="width=480, initial-scale=1.0">
		<?php 
		foreach($this->scripts as $script){ 
		?>
			<script type="text/javascript" src="<?=$script ?>" />
		<?php
		}
		foreach($this->styles as $style)
		{
		?>
			<link rel="stylesheet" type="text/css" href="<?=$style?>"/>
		<?php
		}
	</head>
		return $this;
	}
 }
