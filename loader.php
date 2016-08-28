<?php
session_start();

spl_autoload_register(function($class) {
	include_once str_replace('\\', '/', $class).'.php';
});

use Appi\AntiDDos;
use Appi\Config;
use Appi\DataBase;
use Appi\EnumConst;
use Appi\Installer;
use Appi\QueryBuilder;
use Appi\Server;
use Appi\Statistic;
use Appi\Template;

if (Config::isAntiSqlDDos) {
	new AntiDDos;
}

if (!isset($_SESSION['UTC'])) {
	$_SESSION['UTC'] = 0;
}

if (!empty($_GET['ajax_p'])) {
	switch ($_GET['ajax_p']) {
		case 'utc':
			Server::setClientUTC($_GET['utc']);
			break;
	}
}
