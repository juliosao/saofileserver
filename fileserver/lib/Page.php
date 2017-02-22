 <?php
 
 require_once('Mod.php');
 
 //Represents a Page
 class Page{
	public function __construct($title)
	{
		$this->title=$title;
		$this->mods=array();
		$this->scripts=array();
		$this->styles=array();
	}
	
	//Adds a Mod to a page
	public function addMod($mod)
	{
		//Checks if Mod exiists
		if( !in_array( $mod in $this->mods ) )
			return $this
	
		//Loads the mod
		$m=Mod::load($mod);
		if($m==null)
			throw new Exception("MOD NOT FOUND:$mod");
			
		//Loads the mod dependencies
		$deps=$m->getDependencies();
		foreach($deps as $dep)
		{
			$this->addMod($dep);
		}
			
		//Adds the mod to the list
		$this->mods[]=$m;
		
		//Adds scripts and styles from mod to the page
		array_splice($this->scripts,count($this->scripts),0,$m->getScripts());
		array_splice($this->styles,count($this->styles),0,$m->getStyles());
		return $this;
	}
	
	//Adds a script to a page
	public function addScript($script)
	{
		$this->scripts[]=$script;
		return $this;
	}
	
	//Adds a CSS to a page
	public function addStyle($style)
	{
		$this->styles[]=$style;
		return $this;
	}
	
	//Puts page header
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
