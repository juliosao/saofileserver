 <?php
 
 require_once('Mod.php');
 
 class Page{
	public function __construct($title)
	{
		$this->title=$title;
		$this->mods=array();
		$this->scripts=array();
		$this->styles=array();
	}
	
	public function addMod($mod)
	{
		$m=Mod::load($mod);
		if($m==null)
			throw new Exception("MOD NOT FOUND:$mod");
			
		$deps=$m->getDependencies();
		error_log("DEPS: $mod ->".json_encode($deps));
		foreach($deps as $dep)
		{
			$this->addMod($dep);
		}
			
			
		$this->mods[]=$m;
		array_splice($this->scripts,count($this->scripts),0,$m->getScripts());
		array_splice($this->styles,count($this->styles),0,$m->getStyles());
		return $this;
	}
	
	public function addScript($script)
	{
		$this->scripts[]=$script;
		return $this;
	}
	
	public function addStyle($style)
	{
		$this->styles[]=$style;
		return $this;
	}
	
	
	public function putHeader()
	{
		?>
<!DOCTYPE html>
<html>
    <head>
		<title><?=$this->title ?></title>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">  
        <meta name="viewport" content="width=480, initial-scale=1.0">
		<?php 
		foreach($this->scripts as $script){ 
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
		?></head><?php
		return $this;
	}
 }
