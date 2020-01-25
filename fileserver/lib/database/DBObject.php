<?php

namespace database;

use auth\Group;

/**
 * \fn DBObject
 * \brief Clase generica para objetos guardados en bases de datos
 */
abstract class DBObject
{
	static $fields=array();
	static $keys=array();
	static $table='';
	static $selectQry = null;
	static $fieldsEnum = null;
	// Mandatory
	static $insert=null;
	static $update=null;
	static $delete=null;
	
	static $db;
	
	static function init()
	{
		DBObject::$db = Database::getInstance();
	}

	public function __construct(){}

	public static function select($filters=array(),$ctorArgs=array())
	{
		if(static::$selectQry === null)
		{
			static::$selectQry = 'SELECT '.implode(',',static::$fields).' FROM '.static::$table;
		}

		$where=array();
		foreach($filters as $key => $value)
		{
			$where[]=$key.'= :'.$key;
		}

		if(count($where)==0)
			return static::$db->query(static::$selectQry, $filters,$className=static::class,$ctorArgs);
		else
			return static::$db->query(static::$selectQry.' WHERE '.implode(' AND ',$where), $filters,$className=static::class,$ctorArgs);
	}

	public function getGroups()
	{
		return Group::fromUser($this);
	}

	public function __toString()
	{
		return static::class.':'.json_encode($this);
	}

	public function update()
	{		
		if(static::$update==NULL)
		{
			$conditions=array();
			$values=array();			

			foreach(static::$fields as $key)
			{  
				if(in_array($key,static::$keys))
					$conditions[]= $key.' = :'.$key;
				else
					$values[]= $key.' = :'.$key;
			}

			static::$update='UPDATE '.static::$table.' SET '.implode(',',$values).' WHERE '.implode(' AND ',$conditions);
		}

		$dict=array();
		foreach(static::$fields as $key)
		{
			$dict[$key]=$this->$key;
		}


		return static::$db->execute(static::$update,$dict);
	}

	public function insert()
	{
		$fields=array();
		$values=array();
		$dict=array();

		if(static::$insert==NULL)
		{
			foreach(static::$fields as $fieldName)
			{
				if(!in_array($fieldName,static::$keys))
				{
					$fields[]=$fieldName;
					$values[]=':'.$fieldName;
					$dict[$fieldName]=$this->$fieldName;
				}
			}
			static::$insert='INSERT INTO '.static::$table.' ('.implode(',',$fields).') VALUES ';
		}
		

		$ret= static::$db->execute(static::$insert.'('.implode(',',$values).')',$dict);
		return static::$db->getInsertId();
		
	}

	public function delete()
	{		
		if(static::$delete==NULL)
		{
			$conditions=array();	

			foreach(static::$fields as $key)
			{  
				if(in_array($key,static::$keys))
					$conditions[]= $key.' = :'.$key;				
			}

			static::$delete='DELETE FROM '.static::$table.' WHERE '.implode(' AND ',$conditions);
		}

		$dict=array();
		foreach(static::$fields as $key)
		{
			if(in_array($key,static::$keys))
				$dict[$key]=$this->$key;
		}

		error_log(static::$delete);
		error_log(json_encode($dict));
		return static::$db->execute(static::$delete,$dict);
	}
}


DBObject::init();
