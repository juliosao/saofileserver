<?php

namespace app;

use \Cfg;

//Represents a HTMLApp extends App
abstract class HTMLApp extends App
{
	protected $scripts = ['js/App.js','js/Ui.js'];
	protected $styles = [ 'styles/w3.css', 'styles/main.css'];
	protected $title;
	protected $name;

	public function __construct()
	{
		$this->title = static::class;
		$this->name = static::class;
	}

	public function header($args)
	{}

	public abstract function body($args);

	//Puts HTMLApp extends App header
	public function main($args)
	{
		$bundles = Bundle::select(['enabled'=>true]);
		foreach($bundles as $bundle)
		{
			$cfg = $bundle->load($this->name); 
			if($cfg != null)
			{
				if(isset($cfg->scripts))
				{
					foreach($cfg->scripts as $script)
						$this->$scripts[] = $script;
				}

				if(isset($cfg->styles))
				{
					foreach($cfg->styles as $style)
						$this->$styles[] = $style;
				}
			}
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$this->title ?></title>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
<?php
		foreach($this->styles as $style)
		{
?>		<link rel="stylesheet" href="<?=self::getAppUrl($style);?>"><?php
		}

		foreach($this->scripts as $script)
		{
?>		<script src="<?=self::getAppUrl($script);?>" ></script><?php
		}
?>		
		<script type="text/javascript" >
			App.main = "<?=App::getAppURL(Cfg::get()->app->main);?>";
		</script>
<?php $this->header($args); ?>		
	</head>
	<body>
<?php $this->body($args); ?>
	</body>
</html>
<?php 
	}

}