<?php
	require_once('../../../lib/Util.php');

	use app\JSONApp;
	use filesystem\FileSystemObject;
	use filesystem\Directory;
	
	class Explore extends JSONApp{
		
		public function __construct()
		{
			parent::__construct(1);
		}

		public function main($args) {
			$result=[];
			$basedir=Cfg::get()->fso->basedir;
			
			$dirname='';
			
			if(isset($args['path'])) {
				$dirname=str_replace('..','.',$args['path']);
			}
			error_log("Buscando ".$dirname);
			
			$dirpath=FileSystemObject::joinPath($basedir,$dirname);
			$fso=FileSystemObject::fromPath($dirpath);

			if(!$fso===null) {
				error_log("Not found:".$dirname);
				throw new NotFoundException($dirname);
			}

			// Basic data of the FileSystemObject
			$result['name']=$fso->getName();
			$result['link']=$fso->getRelativePath($basedir);

			$dirs=[];
			$files=[];			
			
			if( $fso instanceof Directory)
			{
				$result['free']=$fso->getFreeSpace();
				$result['total']=$fso->getTotalSpace();
				$result['isDir']=True;	
				// Fills child dirs
				$parent=$fso->getParent();
				
				if(strlen($parent->path)>=strlen($basedir))
				{
					$dirs['..']=[						
						'name'=>'..',
						'link'=>$parent->getRelativePath($basedir),					
						];
				}				

				$c=$fso->childDirs();    
				foreach($c as $d)
				{
					$dirs[$d->getName()]=[
						'name'=>$d->getName(),
						'link'=>$d->getRelativePath($basedir)
						];
				}
				
			
				$c=$fso->childFiles();
				foreach($c as $f)
				{
					$files[$f->getName()]=[
						'name'=>$f->getName(),
						'link'=>$f->getRelativePath($basedir),
						'extension'=>$f->extension(),
						'mime'=>$f->mime()
						];
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
