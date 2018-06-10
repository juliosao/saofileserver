<?php
	require_once('../../lib/Util.php');
		
	class Explore extends app\JSONApp{
		
		public function __construct()
		{
			parent::__construct(1);
		}

		public function main() {
			$result=array();
			$basedir=Cfg::get()->fso->basedir;
			
			$dirname='.';
			
			if(isset($_REQUEST['path'])) {
				$dirname=str_replace('..','.', urldecode($_REQUEST['path']));
			}
			error_log("Ruta:".$dirname);
			
			$dirpath=fso\FSO::joinPath($basedir,$dirname);
			
			$fso=fso\FSO::fromPath($dirpath);
			if(!$fso===null) {
				$this->exitError(404,"Ruta no encontrada :$dirname");
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
						'extension'=>$f->extension(),
						'mime'=>$f->mime());
				}

				ksort($dirs);
				ksort($files);
				
				$result['free']=$fso->getFreeSpace();
				$result['total']=$fso->getTotalSpace();				
			}
			else
			{
				$files[$fso->getName()]=array(
						'name'=>$fso->getName(),
						'link'=>urlencode($fso->relativePath($basedir)),
						'extension'=>$fso->extension(),
						'mime'=>$fso->mime());
			}
			
			$result['path']=rawurlencode($dirname);	
			$result['fsotype']=$fso->type;
			$result['dirs']=$dirs;
			$result['files']=$files;
			$result['function']='explore';

			return $result;
		}
	}
		
	$b=new Explore();
	$b->run();
?>
