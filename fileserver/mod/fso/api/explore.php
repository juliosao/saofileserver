<?php
    require_once '../../../lib/JSONApp.php';
    require_once("../lib/FSO.php");
    require_once("../lib/FSODir.php");
    require_once("../lib/FSOFile.php");   
    require_once("../cfg/fso.cfg");
	
	class Explore extends JSONApp{
		public function main() {
			global $basedir;
			
			$this->setResult('function','explore');
			
			$dirname='.';
			
			if(isset($_REQUEST['path'])) {
				$dirname=str_replace('..','.', urldecode($_REQUEST['path']));
			}
			error_log("Ruta:".$dirname);
			
			$dirpath=fso::joinPath($basedir,$dirname);
			
			$dir=new FSODir($dirpath);
			if(!$dir->exists()) {
				$this->exitApp(false,"Ruta no encontrada :$dirname");
			}

			$dirs=array();
			$files=array();
			
			
			$parent=$dir->getParent();
			
			if(strlen($parent->path)>=strlen($basedir))
			{
				$dirs['..']=array('name'=>'..','link'=>$parent->relativePath($basedir));
			}
				
			$c=$dir->childDirs();    
			foreach($c as $d)
			{
				$dirs[$d->getName()]=array('name'=>$d->getName(),'link'=>urlencode($d->relativePath($basedir)));
			}
			
			$c=$dir->childFiles();    
			foreach($c as $f)
			{
				$files[$f->getName()]=array('name'=>$f->getName(),'link'=>urlencode($f->relativePath($basedir)),'extension'=>$f->extension());
			}

			ksort($dirs);
			ksort($files);
			
			$this->setResult('path',$dirname);
			$this->setResult('dirs',$dirs);
			$this->setResult('files',$files);
			
		}
	}
		
	$b=new Explore();
	$b->run();
?>
