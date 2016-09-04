<?php

namespace Appi\Classes;

use \PDO;

/**
* QueryBuilder
*/
class QueryBuilder
{
	public $dbh;

	protected $server;

	function __construct()
	{
		$this->server = new Server;
	}

	/**
	* Mehtod. Connect data base;
	*/
	public function connectDataBase($host, $db_name, $user, $pass, $charset = 'utf8') {
		$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset"; 

		$opt = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
		// указатель на соединение
		try 
		{
		    $this->dbh = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass, $opt);
		}
		catch(PDOException $e)
		{
		    echo $e->getMessage();
		    $server->log("[".$server->dateNow."] ".$e.";\n", "logs/errors.log");
		    return false;
		}
		$char = $this->dbh->prepare('SET NAMES UTF8');
		$char->execute();	
		$this->server->log("Connect to data base - true; DB Name = ".$db_name.";\n", "logs/sql.log");
		return true;
	}

	/**
	* Mehtod. Create data base connect;
	*/
	public function createQueryBuilder($tableName) {
		return new DataBase($this->dbh, $tableName);
	}
}