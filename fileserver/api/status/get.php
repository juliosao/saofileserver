<?php

require_once('../../lib/Util.php');
    
class MyApp extends JSONApp{
    public function __construct()
    {
        parent::__construct(1);
	}

	public function main() 
	{   
		$result=array();
		$temp=file_get_contents("/sys/class/thermal/thermal_zone0/temp");
		$result['cpu_temp']=((float)$temp)/1000;

		$temp=explode("\n",file_get_contents("/proc/meminfo"));

		foreach($temp as $line)
		{
			$par=explode(':',$line);
			switch($par[0])
			{
				case 'MemTotal':
					$result['mem_total']=trim($par[1]);
					break;
				case 'MemFree':
					$result['mem_free']=trim($par[1]);
					break;
				case 'MemAvailable':
					$result['mem_available']=trim($par[1]);
					break;
				case 'SwapCached':
					$result['mem_swap']=trim($par[1]);	
					break;
			}
		}

		$parentDir=new FSODir(Cfg::get()->fso->basedir);
		
		$result['disk_free']=$parentDir->getFreeSpace();
		$result['disk_total']=$parentDir->getTotalSpace();

		$this->setResult('status',$result);
    }
}

$b= new MyApp();
$b->run();

$result['result']='ok';
die(json_encode($result));

