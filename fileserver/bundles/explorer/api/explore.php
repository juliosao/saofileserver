<?php
	require_once('../../../lib/Util.php');
		
	class Explore extends JSONApp{
		
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
			error_log("Buscando ".$dirname);
			
			$dirpath=FSO::joinPath($basedir,$dirname);
			$fso=FSO::fromPath($dirpath);

			if(!$fso===null) {
				error_log("Not found:".$dirname);
				throw new FSONotFoundException($dirname);
			}

			// Basic data of the FSO
			$result['name']=$fso->getName();
			$result['link']=urlencode($fso->relativePath($basedir));			

			$dirs=array();
			$files=array();			
			
			if( $fso instanceof FSODir)
			{
				$result['free']=$fso->getFreeSpace();
				$result['total']=$fso->getTotalSpace();
				$result['isDir']=True;	
				// Fills child dirs
				$parent=$fso->getParent();
				
				if(strlen($parent->path)>=strlen($basedir))
				{
					$dirs['..']=array('name'=>'..',
						'name'=>'..',
						'link'=>$parent->relativePath($basedir),
						'isDir'=>True					
						);
				}				

				$c=$fso->childDirs();    
				foreach($c as $d)
				{
					$dirs[$d->getName()]=array(
						'name'=>$d->getName(),
						'link'=>urlencode($d->relativePath($basedir)),
						'isDir'=>True
					);
				}
				
			
				$c=$fso->childFiles();
				foreach($c as $f)
				{
					$files[$f->getName()]=array(
						'name'=>$f->getName(),
						'link'=>urlencode($f->relativePath($basedir)),
						'extension'=>$f->extension(),
						'mime'=>$f->mime(),
						'isDir'=>False);
				}

				ksort($dirs);
				ksort($files);			

				$result['dirs']=$dirs;
				$result['files']=$files;
			}
			else
			{
				$result['isDir']=False;	
				$result['extension']=$fso->extension();
				$result['mime']=$fso->mime();
			}
			return $result;
		}
	}
		
	$b=new Explore();
	$b->run();
?>
