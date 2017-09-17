<?php

/**
 * \fn DBObject
 * \brief Clase generica para objetos guardados en bases de datos
 */
abstract class DBObject
{
	// Claves con las que se construyó el objeto, para casos de actualizacion
	protected $oldKeys=array();
	
	/**
	 * \fn __construct($args)
	 * \brief Constructor de clase
	 * \param args Un array asociativo con los campos del objeto
	 */	
	protected function __construct($args)
	{
		//print_r($args);
		$this->oldKeys=array();
		foreach($args as $argkey => $argval)
		{						
			if(in_array($argkey,static::$fields,TRUE))
			{
				$this->$argkey=$argval;

				if(array_search($argkey,static::$keys)!==FALSE)
				{					
					$this->oldKeys[$argkey]=$argval;				
				}
			}
		}
	}
	
	/**
	 * \fn select($args)
	 * \brief Obtiene un array con todos los objetos que cumplen un patron en una tabla
	 * \param args Un array asociativo con los campos que actuaran de filtro
	 * \return Un array con todos los elementos encontrados
	 */
	static function select($args=array())
	{	
		error_log(json_encode($args));
		$camposCondicion=array();
		
		if(static::$select===null)
		{
			static::$select = 'SELECT '.implode(',',static::$fields).' FROM '.static::$table;
		}		
		
		$qry=static::$select;		

		if(count($args))
		{		
			$condiciones=array();		
			foreach($args as $argkey => $argval)
			{
				if(in_array($argkey,static::$fields))
				{
					$condiciones[]= is_array( $argval ) ? implode(' ',$argval) : $argkey.'= :'.$argkey;
					$camposCondicion[$argkey]=$argval;
				}
			}
		
			$qry.= ' WHERE '.implode(' AND ', $condiciones );	
		}
			
		$tmp=static::$db->query($qry,$camposCondicion);
		$res=array();
		
		foreach($tmp as $row)
		{
			$res[]=new static($row);
		}
		
		return $res;
	}
	
	/**
	 * \fn insert()
	 * \brief Inserta en la base de datos un registro con los datos del objeto
	 */
	function insert()
	{
		$values=array();
		$parametros=array();
		
		
		foreach(static::$fields as $key)
		{
			$values[]=':'.$key;
			$parametros[$key]=$this->$key;
		}
		
		print_r($values);
		print_r($parametros);

		if(static::$insert===null)
		{
			static::$insert='INSERT INTO '.static::$table.'('.implode(',',static::$fields).') VALUES ('.implode(',',$values).')';		
			
		}
		
		return static::$db->execute(static::$insert,$parametros);		
	}
	
/**
	 * \fn replace()
	 * \brief Reemplaza en la base de datos un registro con los datos del objeto
	 */
	function replace()
	{
		$values=array();
		$parametros=array();
		
		
		foreach(static::$fields as $key)
		{
			$values[]=':'.$key;
			$parametros[$key]=$this->$key;
		}
		
		if(static::$insert===null)
		{
			static::$insert='REPLACE INTO '.static::$table.'('.implode(',',static::$fields).') VALUES ('.implode(',',$values).')';		
			
		}
		
		error_log(static::$insert);
		return static::$db->execute(static::$insert,$parametros);		
	}

	/**
	 * \fn update
	 * \brief Actualiza en la base de datos el registro con los datos del objeto
	 */
	function update()
	{
		$dict=array();
		
		$conditions=array();
		foreach($this->oldKeys as $key => $val)
		{
			$okey='o_'.$key;
			$conditions[]=$key.' = :'.$okey;
			$dict[$okey]=$val;
		}
		
		$values=array();
		foreach(static::$fields as $key)
		{
			$nkey='n_'.$key;
			$values[]=$key.' = :'.$nkey;
			$dict[$nkey]=$this->$key;
			
		}
		
		if(static::$update===null)
		{
			static::$update='UPDATE '.static::$table.' SET '.implode(', ',$values).' WHERE '.implode(' AND ',$conditions);
		}

		return static::$db->execute(static::$update,$dict);
	}
	
	/**
	 * \fn delete()
	 * \brief Elimina en la base de datos el registro con los datos del objeto
	 */
	function delete()
	{
		$values=array();
		$dict=array();
		
		foreach(static::$keys as $key)
		{
			$values[]=$key.' = :'.$key;
			$dict[$key]=$this->$key;
		}
		
		if(static::$delete===null)
		{
			static::$delete='DELETE FROM '.static::$table.' WHERE '.implode(' AND ',$values);
		}
		
		return static::$db->execute(static::$delete,$dict);
	}	
}

