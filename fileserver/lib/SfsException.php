<?php

class SfsException extends \Exception
{
	static $errNumber=500;

	public function __construct($msg)
	{
		parent::__construct($msg);
		$this->stack=[];

		$st = debug_backtrace();
		foreach($st as $f)
		{
			$this->stack[] = $f['file'].':'.$f['line'].' -> '.$f['function'];
		}
	}

	public function abort($debug)
	{
		error_log("".static::$errNumber.":".$this->getMessage());
		$this->debugStacktrace();
		
		ob_end_clean();
		
		$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
        header($protocol.' '.static::$errNumber.' ' .$this->getMessage());
        $GLOBALS['http_response_code'] = static::$errNumber;
		
		echo $this->getMessage();

		die();
	}

	function debugStacktrace()
	{
		foreach($this->stack as $line)
			error_log($line);
	}
}