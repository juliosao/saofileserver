<?php

require('../../../lib/Util.php');

class Setup extends HTMLApp
{
	function putMsg($description)
	{
		echo '<li>';
		echo $description;
		echo '</li>';
	}

	function doTask($description,$qry,$params=array())
	{
		echo '<li>';
		try
		{
			echo $description;
			echo ' ';
			$this->db->execute($qry,$params);
			echo '<span class="badge badge-success">OK</span>';
			echo '</li>';
		}
		catch(Exception $ex)
		{
			echo '<span class="badge badge-danger">ERROR</span>';
			echo '</li>';
			throw $ex;
		}
	}

	function main()
	{
		$this->log=array();

		$usr=getParam('usr','');
		$pwd=getParam('pwd','');
		$appUsr=getParam('appUsr','');
		$appPwd=getParam('appPwd','');

		if($usr=='' || $pwd=='' || $appUsr=='' || $appPwd=='')
		{			
    		header('Location: index.php?error=1',true,302);				
			return;
		}

		try
		{
			echo "<h3>Preparando base de datos...</h3>";
			$this->db=new Database("mysql:host=localhost;",$usr,$pwd);

			echo '<ul>';

			$this->doTask('Limpiando bbdd anteriores...','DROP DATABASE IF EXISTS saofileserver');
			$this->doTask('Creando bbdd...',"CREATE DATABASE saofileserver CHARSET=latin1; USE saofileserver");
			$this->doTask('Configurando bbdd...',"GRANT DELETE,UPDATE,INSERT,SELECT ON saofileserver.* TO saofileserver@localhost IDENTIFIED BY 'saofileserver' WITH GRANT OPTION");
			$this->doTask('Creando tablas (1/3)','CREATE TABLE users (
				id INT PRIMARY KEY,
				name VARCHAR(64) NOT NULL UNIQUE,
				auth VARCHAR(256),
				session VARCHAR(256),
				mail VARCHAR(256)
			)');

			$this->doTask('Creando tablas (2/3)','CREATE TABLE groups (
				id INT PRIMARY KEY,
				name VARCHAR(64) NOT NULL UNIQUE
			)');

			$this->doTask('Creando tablas (3/3)','CREATE TABLE user2groups (
				user INT REFERENCES users(id),
				grp INT REFERENCES groups(id),
				PRIMARY KEY (user,grp)
			)');
		
			$this->doTask('Creando grupo admin...', "INSERT INTO groups (id,name) VALUES (0,'admin')");
			$this->doTask('Creando grupo users...',"INSERT INTO groups (id,name) VALUES (1,'users')");
			$this->doTask('Creando usuario inicial...',"INSERT INTO users (id,name,auth) VALUES (?,?,?)",array(0,$appUsr,hash('sha256',$appPwd)));			
			$this->doTask('Configurando usuario inicial...',"INSERT INTO user2groups (user,grp) VALUES (0,0),(0,1)",array($appUsr));

			$this->putMsg('Tareas terminadas, tal vez quiera <a href="'.self::getAppURL().Cfg::get()->app->main.'">hacer login</a>');
		}
		catch(Exception $ex)
		{
			echo '<br/>';
			echo '<div class="alert alert-warning" role="alert">';
			echo '<p>'.$ex->getMessage().'</p>';
			echo '</div>';
		}

		echo '</ul>';

	}
}

$setup=new Setup();
$setup->run();
