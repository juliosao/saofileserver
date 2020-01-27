<?php

namespace database;

use \PDO;
use \Cfg;

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
        if(isset(Cfg::get()->bbdd->database))
            Database::$defaultPath=Cfg::get()->bbdd->database;

        if(isset(Cfg::get()->bbdd->user))
            Database::$defaultUser=Cfg::get()->bbdd->user;

        if(isset(Cfg::get()->bbdd->pass))
            Database::$defaultPass=Cfg::get()->bbdd->pass;                    
        
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
            $this->db = new PDO($path,$usr,$passw,
                    array( PDO::ATTR_PERSISTENT => true )
                    );

        
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            //$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE);
        }
        catch(\Exception $ex)
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


    /**
     * \fn consultar($consulta,&$campos=-1,$pagina=-1)
     * \brief ejecuta una consulta en la base de datos y retorna las filas resultantes
     * \param $consulta Cadena con la consulta a ejecutar
     * \param $campos Array asociativo con los parametros a pasar a la consulta
     * 
     * Dentro de la consulta podemos usar el caracter '?' para indicar un parametro de $campos (Se buscará por posición)
     * o bien :nombreCampo para indicarlo (En este caso se buscara la clave 'nombreCampo'		
     */
    function query($consulta, $campos = array(),$className=null,$ctorArgs=array()) 
    {	
        try
        {
            $stm = $this->db->prepare($consulta);
            if($className!==null)
                $stm->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $className, $ctorArgs);                    

            $stm->execute($campos);
            $res=$stm->fetchAll();
            $stm->closeCursor();
            return $res;
        }
        catch(\Exception $ex)
        {
            error_log($consulta);
            error_log(json_encode($campos));
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
            error_log($consulta.':'.$res);
            return $res;
        }
        catch(Exception $ex)
        {
            error_log($consulta);
            throw new DatabaseException($ex->getMessage());
        }
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