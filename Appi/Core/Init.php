<?php
namespace Appi\Core;

use Appi\Core\AntiDDos;
use Appi\Core\Config;
use Appi\Core\DataBase;
use Appi\Core\EnumConst;
use Appi\Core\Installer;
use Appi\Core\QueryBuilder;
use Appi\Core\Server;
use Appi\Core\Statistic;
use Appi\Core\Template;

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