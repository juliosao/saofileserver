<?php

class SfsException extends \Exception
{
	static $errNumber=500;

	public function __construct($msg)
	{
		parent::__construct($msg);
	}

	public function abort($debug)
	{
		error_log("".static::$errNumber.":".$this->getMessage());
		ob_end_clean();
		
		$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
        header($protocol.' '.static::$errNumber.' ' .$this->getMessage());
        $GLOBALS['http_response_code'] = static::$errNumber;
		
		echo $this->getMessage();
		die();
	}
}