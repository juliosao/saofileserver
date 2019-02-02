<?php

class FsoException extends Exception
{
	static $errNumber=500;

	public function abort($debug)
	{
		error_log("".static::$errNumber.":".$this->getMessage());
		ob_end_clean();
		http_response_code(static::$errNumber);
		
		echo $this->getMessage();
		die();
	}
}