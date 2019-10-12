<?php

/**
 * \class Database
 * \brief Clase manejadora de la base de datos
 */
class Database {    
    static $defaultPath = 'mysql:host=localhost;dbname=saofileserver;charset=utf8';
    static $defaultUser = 'saofileserver';
    static $defaultPass = 'saofileserver';
    
    static $instancia; /**< Aqui guardamos la instancia creada de la clase, solo puede haber una a la vez */
    static $initialized=false;

    static function init()
    {  
        if(isset(\Cfg::get()->bbdd->database))
            Database::$defaultPath=\Cfg::get()->bbdd->database;

        if(isset(\Cfg::get()->bbdd->user))
            Database::$defaultUser=\Cfg::get()->bbdd->user;

        if(isset(\Cfg::get()->bbdd->pass))
            Database::$defaultPass=\Cfg::get()->bbdd->pass;                    
        
    }


	/**
	 * \fn __construct($path, $usr = null, $passw = null)
	 * \brief Constructor de clase
	 */
    function __construct($path = null, $usr = null, $passw = null) 
    {

        if($path===null)
            $path=self::$defaultPath;

        if($usr===null)
            $usr=self::$defaultUser;
            
        if($passw===null)
            $passw=self::$defaultPass;


        try
        {
            $this->db = new \PDO($path,$usr,$passw,
                    array( \PDO::ATTR_PERSISTENT => true )
                    );

        
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        catch(Exception $ex)
        {
            error_log($ex);
            throw new DatabaseException($ex->getMessage());
        }

    }

    /**
     * \fn __destruct()
     * \brief Desconecta la base de datos 
     */
    function __destruct() {
        $this->db = null;
    }

	function prepare($qry)
    {
        $stmt=$this->db->prepare($qry);
		return $stmt;
    }

    /**
     * \fn consultar($consulta,&$campos=-1,$pagina=-1)
     * \brief ejecuta una consulta en la base de datos y retorna las filas resultantes
     * \param $consulta Cadena con la consulta a ejecutar
     * \param $campos Array asociativo con los parametros a pasar a la consulta
     * 
     * Dentro de la consulta podemos usar el caracter '?' para indicar un parametro de $campos (Se buscará por posición)
     * o bien :nombreCampo para indicarlo (En este caso se buscara la clave 'nombreCampo'		
     */
    function query($consulta, $campos = array()) 
    {	
        try
        {
            $stm = $this->db->prepare($consulta);
            $stm->execute($campos);
            $res=$stm->fetchAll();
            $stm->closeCursor();
            return $res;
        }
        catch(Exception $ex)
        {
            throw new DatabaseException($ex->getMessage());
        }

    }
    
    /**
     * \fn execute($consulta,&$campos=-1,$pagina=-1)
     * \brief ejecuta una consulta en la base de datos y retorna el numero de filas afectadas
     * \param $consulta Cadena con la consulta a ejecutar
     * \param $campos Array asociativo con los parametros a pasar a la consulta
     * 
     * Dentro de la consulta podemos usar el caracter '?' para indicar un parametro de $campos (Se buscará por posición)
     * o bien :nombreCampo para indicarlo (En este caso se buscara la clave 'nombreCampo'		
     */
    function execute($consulta, $campos = array()) 
    {	
        try
        {
            $stm = $this->db->prepare($consulta);
            $stm->execute($campos);
            $res=$stm->rowCount();	
            $stm->closeCursor();
            return $res;
        }
        catch(Exception $ex)
        {
            throw new DatabaseException($ex->getMessage());
        }
    }

	/**
	 * \fn insertObj($table, $obj)
	 * \brief Inserta una fila en la tabla
	 * \param $table Tabla donde insertar la fila
	 * \param $obj Array asociativo con los valores a insertar, las claves seran los nombres de columna y los valores los valores de la fila
	 * \retval Siempre debería devolver 1
	 */
    function insertObj($table, $obj) 
    {
        $campos = "";
        $valores = "";

        $sep = "";
        foreach ($obj as $clave => $valor) {
            $campos.=$sep . $clave;
            $valores.=$sep . ':' . $clave;
            $sep = ',';
        }

        $consulta = 'INSERT INTO ' . $table . ' (' . $campos . ') VALUES (' . $valores . ')';        

        return $this->execute($consulta,$obj);
    }

	/**
	 * \fn replaceObj($table, $obj)
	 * \brief Inserta una fila en la tabla, si el objeto ya existiera lo modificaría
	 * \param $table Tabla donde insertar la fila
	 * \param $obj Array asociativo con los valores a insertar, las claves seran los nombres de columna y los valores los valores de la fila
	 * \retval 0 Si no se modificó el registro
	 * \retval 1 Si se modificó el registro
	 */
	function replaceObj($table, $obj) 
	{
        $campos = "";
        $valores = "";

        $sep = "";
        foreach ($obj as $clave => $valor) {
            $campos.=$sep . $clave;
            $valores.=$sep . ':' . $clave;
            $sep = ',';
        }

        $consulta = 'REPLACE INTO ' . $table . ' (' . $campos . ') VALUES (' . $valores . ')';

        return $this->execute($consulta,$obj);
    }

	/**
	 * \fn replaceObj($table, $obj, $condition=array())
	 * \brief Actualiza filas en la tabla
	 * \param $table Tabla donde insertar la fila
	 * \param $obj Array asociativo con los valores a actualizar, las claves seran los nombres de columna y los valores los valores de la fila
	 * \param $condition Array asociativo con las condiciones a cumplir por los registros a modificar. Las claves seran los nombres de columna y los valores los valores de la fila
	 * \return Numero de filas actualizadas
	 */
    function updateObj($table, $obj, $condition=array()) 
    {
		$dict=array();
		
        $consulta = 'UPDATE ' . $table . ' SET ';
        $sep = '';
        
        // Nuevos valores
        foreach ($obj as $clave => $valor) {
				$dict['new_'.$clave]=$valor;
                $consulta.=$sep . $clave . '=:new_' . $clave;
                $sep = ',';
        }

		// Condicion
        if (count($condition) > 0) {
            $consulta.=' WHERE ';
            $sep = '';
            foreach ($condition as $clave => $valor) {
				$dict['cond_'.$clave]=$valor;
                $consulta.=$sep . $clave . '=:cond_' . $clave;
                $sep = ' AND ';
            }
        }
		
        return $this->execute($consulta,$dict);
    }

	/**
	 * \fn deleteObj($table, $condition=array())
	 * \brief Borra filas de la tabla
	 * \param $table Tabla donde insertar la fila
	 * \param $condition Array asociativo con las condiciones a cumplir por los registros a eliminar. Las claves seran los nombres de columna y los valores los valores de la fila
	 * * \return Numero de filas borradas
	 */
    function deleteObj($table, $condition) 
    {
        $consulta = 'DELETE FROM ' . $table;
        
		// Condicion
        if (count($condition) > 0) {
            $consulta.=' WHERE ';
            $sep = '';
            foreach ($condition as $clave => $valor) {
				$dict['cond_'.$clave]=$valor;
                $consulta.=$sep . $clave . '=:cond_' . $clave;
                $sep = ' AND ';
            }
        }
		
        return $this->execute($consulta,$dict);
    }

	/**
	 * \fn getLastError()
	 * \brief Devuelve el ultimo error ocurrido en la base de datos
	 */
    function getLastError() 
    {
        return $this->db->errorInfo(2);
    }

	/**
	 * \fn getInsertId()
	 * \brief Devuelve el ID de la ultima fila insertada (su clave autonumerica)
	 */
    function getInsertId() 
    {
        return $this->db->lastInsertId();
    }

	/**
	 * \fn getInstance()
	 * \brief Mantiene un singleton para esta clase
	 * 
	 * De esta forma podemos usar una instancia para la clese en todo el programa
	 */
    static function getInstance() 
    {
        if(!Database::$instancia)
        {
            $maindb = new Database();
            Database::$instancia=$maindb;
        }  
        return Database::$instancia;
    }	
}

Database::init();