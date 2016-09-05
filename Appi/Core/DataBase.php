<?php

namespace Appi\Core;

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
	* Mehtod. Create table Sql;
	*/
	public function createTableSql($paramsColumn = [], $paramsType = []) {

		if (!$this->checkCountArray($paramsColumn, $paramsType)) {
			return EnumConst::ERROR_SQL_ARRAY;
		}

		$this->sql = 'CREATE TABLE IF NOT EXISTS '.$this->tableName.' (`id` int(11) NOT NULL AUTO_INCREMENT, ';
		for ($i=0; $i < count($paramsColumn); $i++) { 
			$this->sql .= '`'.$paramsColumn[$i].'` '.$paramsType[$i].' NOT NULL, ';
		}
		$this->sql .= 'PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
		$this->query = $this->dbh->prepare($this->sql);
		$this->query->execute();

		if ($this->query) {
			return true;
		}
		return false;

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