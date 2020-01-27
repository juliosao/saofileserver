<?php

namespace database;

use NotFoundException;

/**
 * \fn DBObject
 * \brief Clase generica para objetos guardados en bases de datos
 */
abstract class DBObject
{
	/*
	static $fields=array();
	static $keys=array();
	static $table='';
	// Mandatory
	static $selectQry = null;
	static $fieldsEnum = null;
	static $insert=null;
	static $update=null;
	static $delete=null;
	*/
	static $db;

	
	static function init()
	{
		DBObject::$db = Database::getInstance();
	}

	public function __construct(){}

	public static function select($filters=array(),$ctorArgs=array())
	{
		$where=array();
		foreach($filters as $key => $unused)
		{
			$where[]=$key.'= :'.$key;
		}

		if(count($where)==0)
			return static::$db->query(static::selectQry(), $filters,static::class,$ctorArgs);
		else
			return static::$db->query(static::selectQry().' WHERE '.implode(' AND ',$where), $filters,static::class,$ctorArgs);
	}

	public static function get($ctorArgs=array())
	{
		$args=func_get_args();
		$argv=array_shift($args);
		error_log(static::class.':'.json_encode($argv));
		$res = static::$db->query(static::getQry(),$args,static::class,$ctorArgs);
		if(count($res)!==1)
		{
			return static::onNotFound($argv);
		}
		return $res[0];
	}

	public function __toString()
	{
		return static::class.':'.json_encode($this);
	}

	public function update()
	{		
		return static::$db->execute(static::updateQry(),get_object_vars($this));
	}

	public function insert()
	{
		$ret = static::$db->execute(static::insertQry(),get_object_vars($this));
		if($ret==1)
			return static::$db->getInsertId();
		else
			throw new DatabaseException("Cannot insert ".static::class);
		
	}

	public function delete()
	{		
		return static::$db->execute(static::deleteQry(),get_object_vars($this));
	}

	static function onNotFound($args)
	{
		throw new \NotFoundException(static::class);
	}

	abstract static function selectQry();
	abstract static function getQry();	
	abstract static function insertQry();
	abstract static function updateQry();
	abstract static function deleteQry();
}


DBObject::init();
