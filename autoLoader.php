<?php

spl_autoload_register(function($class) {
	include_once str_replace('\\', '/', $class) . '.php';
});