<?php
    
    require_once('cfg/fso.cfg');
	
	class Explore extends JSONApp{
		
		public function __construct()
		{
			parent::__construct();
			Mod::load('fso');
		}

		public function main() {
			global $basedir;
			
			$this->setResult('function','explore');
			
			$dirname='.';
			
			if(isset($_REQUEST['path'])) {
				$dirname=str_replace('..','.', urldecode($_REQUEST['path']));
			}
			error_log("Ruta:".$dirname);
			
			$dirpath=FSO::joinPath($basedir,$dirname);
			
			$fso=FSO::fromPath($dirpath);
			if(!$fso===null) {
				$this->exitApp(false,"Ruta no encontrada :$dirname");
			}

			$dirs=array();
			$files=array();			
			
			$parent=$fso->getParent();
			
			if(strlen($parent->path)>=strlen($basedir))
			{
				$dirs['..']=array('name'=>'..','link'=>$parent->relativePath($basedir));
			}
				
			// Puts subfolders
			if($fso->type==FSODIR)
			{
				$c=$fso->childDirs();    
				foreach($c as $d)
				{
					$dirs[$d->getName()]=array(
						'name'=>$d->getName(),
						'link'=>urlencode($d->relativePath($basedir)));
				}
			}
			
			// Puts files
			if($fso->type==FSODIR)
			{
				$c=$fso->childFiles();
				foreach($c as $f)
				{
					$files[$f->getName()]=array(
						'name'=>$f->getName(),
						'link'=>urlencode($f->relativePath($basedir)),
						'extension'=>$f->extension());
				}

				ksort($dirs);
				ksort($files);
			}
			else
			{
				$files[$fso->getName()]=array(
						'name'=>$fso->getName(),
						'link'=>urlencode($fso->relativePath($basedir)),
						'extension'=>$fso->extension());
			}
			
			$this->setResult('path',$dirname);
			$this->setResult('dirs',$dirs);
			$this->setResult('files',$files);
			$this->setResult('fsotype',$fso->type);
		}
	}
		
	$b=new Explore();
	$b->run();
?>
