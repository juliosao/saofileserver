<?php

require('../../lib/Util.php');

class Setup extends app\App
{
	function __construct()
	{
		$this->log=array();

		if(!isset($_REQUEST['usr']) || !isset($_REQUEST['pwd']) || !isset($_REQUEST['appUsr']) || !isset($_REQUEST['appPwd']))
		{
			$this->log[]='<b>ERROR</b>';
			$this->log[]="Por favor, rellene todos los parametros";
			return;
		}

		$usr=$_REQUEST['usr'];
		$pwd=$_REQUEST['pwd'];
		$appUsr=$_REQUEST['appUsr'];
		$appPwd=$_REQUEST['appPwd'];

		try
		{
			$this->log[]= "<h3>Preparando base de datos...</h3>";
			$db=new database\Database("mysql:host=localhost;",$usr,$pwd);
			$this->log[]= "Limpiando bbdd...";
			$db->execute("DROP DATABASE IF EXISTS fileserver");
			$this->log[]= "Creando bbdd...";
			$db->execute("CREATE DATABASE fileserver CHARSET=latin1");
			$db->execute("GRANT DELETE,UPDATE,INSERT,SELECT ON fileserver.* TO fileserver@localhost IDENTIFIED BY 'fileserver' WITH GRANT OPTION");
			$db->execute("USE fileserver");
			
			$this->log[]= "Creando tablas...";
            
            $this->log[]= "* users...";
			$db->execute("CREATE TABLE users (
							id VARCHAR(64) PRIMARY KEY,
							auth VARCHAR(256),
                            session VARCHAR(256)
						)");

            $this->log[]= "* groups...";
			$db->execute("CREATE TABLE groups (
				id VARCHAR(64) PRIMARY KEY
			)");

            $this->log[]= "* user2groups...";
			$db->execute("CREATE TABLE user2groups (
				user VARCHAR(64) REFERENCES users(id),
				grp VARCHAR(256) REFERENCES groups(id),
				PRIMARY KEY (user,grp)
			)");

			$this->log[]= "Configurando usuario inicial...";
			
			$db->execute("INSERT INTO users (id,auth) VALUES (?,?)",array($appUsr,hash('sha256',$appPwd)));
			$db->execute("INSERT INTO groups (id) VALUES ('admin')");
			$db->execute("INSERT INTO user2groups (user,grp) VALUES ('root','admin')");

			$this->log[]= "Tareas terminadas";
		}
		catch(Exception $ex)
		{
			$this->log[]='<b>ERROR</b>';
			$this->log[]=$ex->getMessage();
		}

	}

	function run()
	{
		echo implode('<br/>',$this->log);
	}
}

$setup=new Setup();
$setup->run();
