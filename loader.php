<?php
session_start();

spl_autoload_register(function($class) {
	include str_replace('\\', '/', $class).'.php';
});

use Appi\AntiDDos;
use Appi\Config;

if (Config::isAntiSqlDDos) {
	new AntiDDos;
}