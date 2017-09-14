<?php

require('../../lib/Util.php');

class Setup extends App
{
	function run()
	{
		try
		{
			$pwd=$_REQUEST['pwd'];
	
			echo "<h3>Preparando base de datos...</h3>";
			$db=new Database("mysql:host=localhost;","root",$pwd);
			echo "Limpiando bbdd..<br/>";
			$db->execute("DROP DATABASE IF EXISTS fileserver");
			echo "Creando bbdd...<br/>";
			$db->execute("CREATE DATABASE fileserver CHARSET=latin1");
			echo "Creando usuario usuario...<br/>";
			$db->execute("GRANT DELETE,UPDATE,INSERT,SELECT ON fileserver.* TO fileserver@localhost IDENTIFIED BY 'fileserver' WITH GRANT OPTION");
			$db->execute("USE fileserver");
			echo "Robots...<br/>";

			$db->execute("CREATE TABLE users (
							id VARCHAR(32) PRIMARY KEY,
							auth VARCHAR(256),
                            session VARCHAR(256)
						)");

			echo "<h3>Configurando usuario inicial</h3>";

            $u = new Auth(array('id'=>'root','auth'=>hash('sha256','root'),'session'=>null));
            $u->insert();
			
		}
		catch(Exception $ex)
		{
			echo $ex->getMessage();
		}
	}
}

$setup=new Setup();
$setup->run();
