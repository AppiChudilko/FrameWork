<?php
namespace Appi\Classes;

use Appi\Classes\AntiDDos;
use Appi\Classes\Config;
use Appi\Classes\DataBase;
use Appi\Classes\EnumConst;
use Appi\Classes\Installer;
use Appi\Classes\QueryBuilder;
use Appi\Classes\Server;
use Appi\Classes\Statistic;
use Appi\Classes\Template;

/**
* Init
*/
class Init
{
	public $config;

	public function __construct()
	{
		session_start();
	}

	public function initAppi() {
		$this->initCfg();
		$this->initRequest();

		if (!isset($_COOKIE['UTC'])) {
			setcookie('UTC', 0, 0x6FFFFFFF);
		}

		return $this;
	}

	protected function initCfg() {
		$this->config = new Config;
		$this->config = $this->config->getAppiAllConfig()->getObjectResult();

		if ($this->config->isAntiSqlDDos) {
			new AntiDDos();
		}

		return $this;
	}

	protected function initRequest() {
		return $this;
	}
}