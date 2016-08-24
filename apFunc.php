<?php
/**
*
* Framework by Appi (Alexander Pozharov)
*
*/

namespace Appi;

use \PDO;

/**
* Constant class
*/
class EnumConst
{	

	const VERSION = '0.1.0';
	/*
	* ERRORS
	*/
	const ERROR_CODE_0 = '0';
	const ERROR_EMAIL_IS_CREATE = 'This email is create';
	const ERROR_SQL_ARRAY = 'Error params';

	/*
	* DATA BASE CONNECT PARAMS
	*/
	const DB_NAME = 'db_name';
	const USR_NAME = 'user_name';

	/*
	* DATA BASE TABLE
	*/
	const USERS = 'a_user';
	const NEWS = 'a_news';
	const STATS = 'fw_statistic';

	/*
	* DATA BASE COLUMN NAME
	*/
	const ID = 'id';

	const U_NAME = 'name';
	const U_SURNAME = 'surname';
	const U_LASTNAME = 'lastname';
	const U_EMAIL = 'email';
	const U_LOGIN = 'login';
	const U_PASSWORD = 'password';
	const U_TOKEN = 'token';

	const ST_IP = 'ip';
	const ST_COUNT = 'count';
	const ST_LAST_CONNECT = 'last_connect';
}

/**
* Config
*/
class Config
{
	const isLog = true;
	const isAntiSqlDDos = true;
	const Template = '.twig';
}

/**
* AntiDDos
*/
class AntiDDos
{
    protected $whiteIp;

    protected $countConnect = 10;

    protected $timeOut = 30;

    protected $timeNow;

    protected $DDosMessage;
 
    function __construct()
    {
        $this->whiteIp = ['176.15.18.126', '84.16.133.26'];
        $this->timeNow = time();
        $this->DDosMessage = 'Time out '.$this->timeOut.'s';
        $this->antiDDos();
    }
 
    /**
    * Mehtod. CheckDDos;
    */
    public function antiDDos() {
 
        if (empty($_COOKIE['AntiDDos'])) {
           
            if (!in_array($_SERVER['REMOTE_ADDR'], $this->whiteIp )) {
           
                if (empty($_SESSION['lastConnect'])) {
                    $_SESSION['lastConnect'] = $this->timeNow;
                    $_SESSION['countConnect'] = 0;
                }
                elseif ($_SESSION['lastConnect'] > $this->timeNow + 10) {
                    $_SESSION['lastConnect'] = $this->timeNow;
                    $_SESSION['countConnect'] = 0;
                }
 
                $_SESSION['countConnect']++;
 
                if ($_SESSION['countConnect'] >= $this->countConnect) {
                    setcookie("AntiDDos", true, $this->timeNow + $this->timeOut);
                    $_SESSION['countConnect'] = 0;
                    die($this->DDosMessage);
                }
            }
        }
        else {
            die($this->DDosMessage);
        }
    }

    /**
    * Mehtod. Set whiteIp;
    */
    public function setWhiteIp($params) {

    	$this->whiteIp = $params;
    	return $this;
    }

    /**
    * Mehtod. Get whiteIp;
    */
    public function getWhiteIp($params) {

    	return $this->whiteIp;
    }

    /**
    * Mehtod. Set timeOut;
    */
    public function setTimeOut($params) {
    	
    	$this->timeOut = $params;
    	return $this;
    }

    /**
    * Mehtod. Get timeOut;
    */
    public function getTimeOut($params) {

    	return $this->timeOut;
    }

    /**
    * Mehtod. Set countConnect;
    */
    public function setCountConnect($params) {
    	
    	$this->countConnect = $params;
    	return $this;
    }

    /**
    * Mehtod. Get countConnect;
    */
    public function getCountConnect($params) {

    	return $this->countConnect;
    }
}

/**
* Server
*/
class Server
{

	public $timeNow;

	public $dateNow;

	function __construct()
	{
		$this->timeNow = time();
		$this->dateNow = date('d/m/Y H:i:s',time());
		$this->requestLog();
	}

	/**
	* Mehtod. Get time stamp;
	*/
	public function unixNow() {
        return $this->timeNow ;
    }

	/**
	* Mehtod. Get date time;
	*/
	public function dateNow() {
        return $this->dateNow ;
    }

	/**
	* Mehtod. Get version framework;
	*/
	public function getVersionFW() {
        return EnumConst::VERSION;
    }

	/**
	* Mehtod. Get client ip;
	*/
	public function getClientIp() {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
	* Mehtod. Get Server URL;
	*/
    public function getServerURL() {
        $url = "http://";
        $url .= $_SERVER["SERVER_NAME"]; // $_SERVER["HTTP_HOST"] is equivalent
        if ($_SERVER["SERVER_PORT"] != "80") $url .= ":".$_SERVER["SERVER_PORT"];
        return $url;
    }

    /**
	* Mehtod. Get full URL;
	*/
    public function getCompleteURL() {
        return $this->getServerURL().$_SERVER["REQUEST_URI"];
    }

    /**
	* Mehtod. Get server info;
	*/
    public function serverInfo(){
	    if (!@phpinfo()) echo 'No Php Info...';
	    echo "<br><br>";
	    $a=ini_get_all();
	    $output="<table border=1 cellspacing=0 cellpadding=4 align=center>";
	    $output.="<tr><th colspan=2>ini_get_all()</td></tr>";

	    while(list($key, $value)=each($a)) {
	        list($k, $v)= each($a[$key]);
	        $output.="<tr><td align=right>$key</td><td>$v</td></tr>";
	    }

	    $output.="</table>";
		echo $output;
	    echo "<br><br>";
	    $output="<table border=1 cellspacing=0 cellpadding=4 align=center>";
	    $output.="<tr><th colspan=2>\$_SERVER</td></tr>";

	    foreach ($_SERVER as $k=>$v) {
	        $output.="<tr><td align=right>$k</td><td>$v</td></tr>";
	    }

	    $output.="</table>";
		echo $output;
	    echo "<br><br>";
	    echo "<table border=1 cellspacing=0 cellpadding=4 align=center>";
	    $safe_mode=trim(ini_get("safe_mode"));

	    if ((strlen($safe_mode)==0)||($safe_mode==0)) $safe_mode=false;
	    else $safe_mode=true;

	    $is_windows_server = (substr(PHP_OS, 0, 3) === 'WIN');
	    echo "<tr><td colspan=2>".php_uname();
	    echo "<tr><td>safe_mode<td>".($safe_mode?"on":"off");

	    if ($is_windows_server) echo "<tr><td>sisop<td>Windows<br>";
	    else echo "<tr><td>sisop<td>Linux<br>";

	    echo "</table><br><br><table border=1 cellspacing=0 cellpadding=4 align=center>";
	    $display_errors=ini_get("display_errors");
	    $ignore_user_abort = ignore_user_abort();
	    $max_execution_time = ini_get("max_execution_time");
	    $upload_max_filesize = ini_get("upload_max_filesize");
	    $memory_limit=ini_get("memory_limit");
	    $output_buffering=ini_get("output_buffering");
	    $default_socket_timeout=ini_get("default_socket_timeout");
	    $allow_url_fopen = ini_get("allow_url_fopen");
	    $magic_quotes_gpc = ini_get("magic_quotes_gpc");
	    ignore_user_abort(true);
	    ini_set("display_errors",0);
	    ini_set("max_execution_time",0);
	    ini_set("upload_max_filesize","10M");
	    ini_set("memory_limit","20M");
	    ini_set("output_buffering",0);
	    ini_set("default_socket_timeout",30);
	    ini_set("allow_url_fopen",1);
	    ini_set("magic_quotes_gpc",0);
	    echo "<tr><td> <td>Get<td>Set<td>Get";
	    echo "<tr><td>display_errors<td>$display_errors<td>0<td>".ini_get("display_errors");
	    echo "<tr><td>ignore_user_abort<td>".($ignore_user_abort?"on":"off")."<td>on<td>".(ignore_user_abort()?"on":"off");
	    echo "<tr><td>max_execution_time<td>$max_execution_time<td>0<td>".ini_get("max_execution_time");
	    echo "<tr><td>upload_max_filesize<td>$upload_max_filesize<td>10M<td>".ini_get("upload_max_filesize");
	    echo "<tr><td>memory_limit<td>$memory_limit<td>20M<td>".ini_get("memory_limit");
	    echo "<tr><td>output_buffering<td>$output_buffering<td>0<td>".ini_get("output_buffering");
	    echo "<tr><td>default_socket_timeout<td>$default_socket_timeout<td>30<td>".ini_get("default_socket_timeout");
	    echo "<tr><td>allow_url_fopen<td>$allow_url_fopen<td>1<td>".ini_get("allow_url_fopen");
	    echo "<tr><td>magic_quotes_gpc<td>$magic_quotes_gpc<td>0<td>".ini_get("magic_quotes_gpc");
	    echo "</table><br><br>";
	    echo "
	    <script language=\"Javascript\" type=\"text/javascript\">
	    <!--
	        window.moveTo((window.screen.width-800)/2,((window.screen.height-600)/2)-20);
	        window.focus();
	    //-->
	    </script>";
	    echo "</body>\n</html>";
	}

	public function requestLog() {
		if(!empty($_REQUEST)) {
			$this->log("[".$this->getCompleteURL()."] ".json_encode($_REQUEST)."\n", "logs/request.log");
		}
	}

	public function log($msg, $dir) {
		if (!file_exists('logs')) {
		    mkdir('logs', 0777, true);
		}
		if (Config::isLog) {
			error_log("[".$this->dateNow."] [".$this->getClientIp()."] ".$msg, 3, $dir);
		}
	}

	public function error($msg, $errorCode = null) {
		$this->log($msg.". Error Code:".$errorCode.";\n", "logs/errors.log");
		return sprintf('Error: '.$msg, $errorCode);
	}
}

/**
* Template
*/
class Template
{
	protected $path;

	protected $template;

	protected $vars = [];

	public function __construct($path = '') {
		$this->path = $_SERVER['DOCUMENT_ROOT'] . $path;
	}

	public function __get($name) {
		if (isset($this->vars[$name])) return $this->vars[$name];
		return '';
	}

	public function set($name, $value) {
		$this->vars[$name] = $value;
	}

	public function display($dir, $strip = true) {

		$this->template = $this->path.$dir;
		if (!file_exists($this->template)) die('Template '.$this->template.' does not exist');

		if (!file_exists($this->path.'cache')) {
		    mkdir($this->path.'cache', 0777, true);
		}

		$output = file_get_contents($this->template, true);
		$output = ($strip) ? $this->replaceReg($this->replaceTag($output)) : $this->replaceTag($output);

		$fp = fopen ($this->path.'cache/'.md5($dir).Config::Template, 'w');   
		fwrite($fp,$output);   
		fclose($fp);  

		ob_start();
		include_once($this->path.'cache/'.md5($dir).Config::Template);
		echo ob_get_clean();
	}

	private function replaceTag($buffer) {
		$replaceTwig = ["{{", "}}", "{*", "*}", "{!", "!}"];
		$replaceFinal = ["<?php", "?>", "<?php /*", "*/ ?>", "{{", "}}"];
		return str_replace($replaceTwig, $replaceFinal, $buffer);
	}

	private function replaceReg($data) {
		$lit = ["\\t", "\\n", "\\n\\r", "\\r\\n", "  "];
		$sp = ['', '', '', '', ''];
		return str_replace($lit, $sp, $data);
	}
}

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

/**
* Data base
*/
class DataBase
{
	protected $server;

	protected $dbh;

	protected $isSelect = false;

	protected $sql = '';

	protected $andWhere = [];

	protected $orWhere = [];	

	protected $where = '';

	protected $groupBy = '';

	protected $orderBy = '';

	protected $innerJoin;

	protected $leftJoin;

	protected $rightJoin;

	protected $fullJoin;

	protected $onJoin;

	protected $like;

	protected $notLike;

	protected $limit;

	protected $query;

	protected $paramsColumn;

	protected $paramsValues;

	protected $tableName;

	function __construct($dbh, $tableName)
	{
		$this->server = new Server;
		$this->dbh = $dbh;
		$this->tableName = $tableName;
		$this->paramsValues = [];
		$this->paramsColumn = [];
	}

	/**
	* Mehtod. Generate Select Sql;
	*/
	public function selectSql($columnName = '*') {

		$this->isSelect = true;
		$this->sql .= 'SELECT '.$columnName.' FROM '.$this->tableName;
		return $this;
	}

	/**
	* Mehtod. Generate Update Sql;
	*/
	public function updateSql($paramsColumn = [], $paramsValues = []) {

		if (!$this->checkCountArray($paramsColumn, $paramsValues)) {
			return EnumConst::ERROR_SQL_ARRAY;
		}


		$this->paramsValues = $paramsValues;
		$this->paramsColumn = $paramsColumn;

		$this->sql = 'UPDATE '.$this->tableName.' SET ';
		$paramsColumnToSql = array_map(function($value){ return $value.' = :'.$value.'_val'; }, $paramsColumn);
		$paramsColumnToSql = array_map(function($value){ return $value.', '; }, $paramsColumnToSql);
		foreach ($paramsColumnToSql as $value) {
			$this->sql .= $value;
		}
		$this->sql = substr($this->sql, 0, -2); 

		return $this;
	}

	/**
	* Mehtod. Generate Insert Sql;
	*/
	public function insertSql($paramsColumn = [], $paramsValues = []) {

		if (!$this->checkCountArray($paramsColumn, $paramsValues)) {
			return EnumConst::ERROR_SQL_ARRAY;
		}

		$this->paramsValues = $paramsValues;
		$this->paramsColumn = $paramsColumn;

		$this->sql = 'INSERT INTO '.$this->tableName.' (';
		$this->sql .= implode(', ',$paramsColumn);
		$this->sql .= ') VALUES (';
		$paramsColumn = array_map(function($value){ return ' :'.$value.'_val'; }, $paramsColumn);
		$this->sql .= implode(', ',$paramsColumn);
		$this->sql .= ')';

		return $this;
	}

	/**
	* Mehtod. Generate Delete Sql;
	*/
	public function deleteSql() {

		$this->sql = 'DELETE FROM '.$this->tableName;
		return $this;
	}

	/**
	* Mehtod. Where Sql;
	*/
	public function where($param) {

		$this->where = ' WHERE '.$param;
		return $this;
	}

	/**
	* Mehtod. And Where Sql;
	*/
	public function andWhere($param) {

		$this->andWhere[] = ' AND '.$param;
		return $this;
	}

	/**
	* Mehtod. or Where Sql;
	*/
	public function orWhere($param) {

		$this->orWhere[] = ' OR '.$param;
		return $this;
	}

	/**
	* Mehtod. order by Sql;
	*/
	public function orderBy($param) {

		$this->orderBy = ' ORDER BY '.$param;
		return $this;
	}

	/**
	* Mehtod. group by Sql;
	*/
	public function groupBy($param) {

		$this->groupBy = ' GROUP BY '.$param;
		return $this;
	}

	/**
	* Mehtod. LIMIT Sql;
	*/
	public function limit($param) {

		$this->limit = ' LIMIT '.$param;
		return $this;
	}

	/**
	* Mehtod. LIKE Sql;
	*/
	public function like($param) {

		$this->like = ' LIKE '.$param;
		return $this;
	}

	/**
	* Mehtod. NOT LIKE Sql;
	*/
	public function notLike($param) {

		$this->notLike = ' NOT LIKE '.$param;
		return $this;
	}

	/**
	* Mehtod. Innser Join Sql;
	*/
	public function innerJoin($param) {

		$this->innerJoin = ' INNER JOIN '.$param;
		return $this;
	}

	/**
	* Mehtod. Left Join Sql;
	*/
	public function leftJoin($param) {

		$this->leftJoin = ' LEFT JOIN '.$param;
		return $this;
	}

	/**
	* Mehtod. Right Join Sql;
	*/
	public function rightJoin($param) {

		$this->rightJoin = ' RIGHT JOIN '.$param;
		return $this;
	}

	/**
	* Mehtod. Full Join Sql;
	*/
	public function fullJoin($param) {

		$this->fullJoin = ' FULL JOIN '.$param;
		return $this;
	}

	/**
	* Mehtod. On Join Sql;
	*/
	public function onJoin($param) {

		$this->onJoin = ' ON '.$param;
		return $this;
	}

	/**
	* Mehtod. Execute query;
	*/
	public function executeQuery() {

		if (!$this->checkCountArray($this->paramsColumn, $this->paramsValues)) {
			return EnumConst::ERROR_SQL_ARRAY;
		}

		$andWhereResult = implode(' ', $this->andWhere);
		$orWhereResult = implode(' ', $this->orWhere);

		$this->sql .= $this->limit.$this->where.$andWhereResult.$orWhereResult.$this->orderBy.$this->groupBy.$this->like.$this->notLike;
		$this->sql .= $this->innerJoin.$this->leftJoin.$this->rightJoin.$this->fullJoin.$this->onJoin;
	
		$this->query = $this->dbh->prepare($this->sql);

		if (!empty($this->paramsColumn) && !empty($this->paramsValues)) {
			for ($i=0; $i < count($this->paramsColumn); $i++) { 
				$this->query->bindvalue($this->paramsColumn[$i].'_val', $this->charsHtmlScript($this->paramsValues[$i]));
			}
		}
		
		$this->query->execute();

		$this->server->log($this->sql, "logs/sql.log");

		return $this;
	}

	/**
	* Mehtod. Get Result;
	*/
	public function getResult() {

		if ($this->query) {
			if ($this->isSelect) {
				return $this->query->fetchAll();
			}
			return true;
		}
		return false;
	}

	/**
	* Mehtod. Get Query;
	*/
	public function getQuery() {
		return $this->sql;
	}

	protected function checkCountArray($paramsColumn = [], $paramsValues = []) {
		if (count($paramsColumn) != count($paramsValues)) {
			return false;
		}
		return true;
	}

	protected function charsHtmlScript($value) {

		$value = htmlspecialchars(stripslashes($value));
		return $value;
	}
}

/**
* Statistic
*/
class Statistic
{
	protected $statsResult;

	protected $clientIp;

	protected $timeNow;

	protected $qb;

	function __construct($qb) {

		$server = new Server;
		$this->qb = new QueryBuilder();
		$this->qb = $qb;
		$this->clientIp = $server->getClientIp();
		$this->timeNow = $server->unixNow();
	}

	public function createStatistic() {

		if (empty($_SESSION['time_stats']) || $_SESSION['time_stats'] < $this->timeNow + 600) {
			
			$this->getClientStats();

			if (!empty($this->statsResult)) {
				if (date('Y/d', $this->timeNow) != date('Y/d', $this->statsResult[EnumConst::ST_LAST_CONNECT])) {
					$this->insertClientStats();
				}
				else {
					$this->updateClientStats();
				}
			}
			else {
				$this->insertClientStats();
			}
			$_SESSION['time_stats'] = $this->timeNow;
		}
		else {
			$_SESSION['count_stats']++;
		}
	}

	public function getAllStats() {
		$result = $this->qb
			->createQueryBuilder(EnumConst::STATS)
			->selectSql()
			->executeQuery()
			->getResult()
		;

		return $result;
	}

	protected function getClientStats() {

		$this->statsResult = end($this->qb
			->createQueryBuilder(EnumConst::STATS)
			->selectSql()
			->where(EnumConst::ST_IP." = '".$this->clientIp."'")
			->executeQuery()
			->getResult()
		);
		return $this;
	}

	protected function updateClientStats() {

		if (empty($_SESSION['count_stats'])) {
			$_SESSION['count_stats'] = 1;
		}

		$this->qb
			->createQueryBuilder(EnumConst::STATS)
			->updateSql([EnumConst::ST_COUNT], [$this->statsResult[EnumConst::ST_COUNT] + $_SESSION['count_stats']])
			->where(EnumConst::ID." = '".$this->statsResult[EnumConst::ID]."'")
			->executeQuery()
		;

		$_SESSION['count_stats'] = 1;

		return $this;
	}

	protected function insertClientStats() {

		$_SESSION['count_stats'] = 1;
		$this->qb
			->createQueryBuilder(EnumConst::STATS)
			->insertSql([EnumConst::ST_IP, EnumConst::ST_COUNT, EnumConst::ST_LAST_CONNECT], [$this->clientIp, '1', $this->timeNow])
			->executeQuery()
		;
		return $this;
	}
}

if (Config::isAntiSqlDDos) {
	new AntiDDos;
}