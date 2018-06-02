<?php
	require_once('../../lib/Util.php');
		
	class Explore extends app\JSONApp{
		
		public function __construct()
		{
			parent::__construct(1);
		}

		public function main() {
			$basedir=Cfg::get()->fso->basedir;
			
			$this->setResult('function','explore');
			
			$dirname='.';
			
			if(isset($_REQUEST['path'])) {
				$dirname=str_replace('..','.', urldecode($_REQUEST['path']));
			}
			error_log("Ruta:".$dirname);
			
			$dirpath=fso\FSO::joinPath($basedir,$dirname);
			
			$fso=fso\FSO::fromPath($dirpath);
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
				
				
				$this->setResult('free',$fso->getFreeSpace());
				$this->setResult('total',$fso->getTotalSpace());
			}
			else
			{
				$files[$fso->getName()]=array(
						'name'=>$fso->getName(),
						'link'=>urlencode($fso->relativePath($basedir)),
						'extension'=>$fso->extension());
			}
			
			$this->setResult('path',rawurlencode($dirname));			
			$this->setResult('fsotype',$fso->type);
			$this->setResult('dirs',$dirs);
			$this->setResult('files',$files);
		}
	}
		
	$b=new Explore();
	$b->run();
?>
