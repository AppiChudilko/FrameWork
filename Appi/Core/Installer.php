<?php

namespace Appi\Core;

use Appi\Core\EnumConst;

/**
* Installer
*/
class Installer
{
	protected $qb;

	function __construct($qb)
	{
		$this->qb = $qb;
	}

	public function install() {

		if (!file_exists('views/twig')) {
		    if (!mkdir('views/twig', 0777, true)) {
		    	echo 'Please, set chmod "views" - 777';
		    	die;
		    }
		}
		
		try {
			$this->qb
				->createQueryBuilder(EnumConst::STATS)
				->createTableSql(
					[
						EnumConst::ST_IP, 
						EnumConst::ST_COUNT, 
						EnumConst::ST_LAST_CONNECT,
						EnumConst::ST_REFERER
					], 
					[
						'varchar(32)', 
						'int(11)', 
						'int(11)', 
						'varchar(256)'
					]
				)
			;

			$this->qb
				->createQueryBuilder(EnumConst::STATS_DAY)
				->createTableSql(
					[
						EnumConst::ST_D_ALL_COUNT, 
						EnumConst::ST_D_COUNT, 
						EnumConst::ST_D_DAY,
						EnumConst::ST_D_MONTH,
						EnumConst::ST_D_YEAR
					], 
					[
						'int(11)', 
						'int(11)', 
						'int(11)', 
						'int(11)',
						'int(11)'
					]
				)
			;
		} 
		catch (Exception $e) {
			return $e;
		}

		$this->installHeaderTwig();
		$this->installIndexTwig();
		$this->installFooterTwig();
		$this->installIndex();
		$this->installRobots();
		header("Refresh: 0; url=/");
	}

	protected function installRobots() {
		$output = '
		User-agent: *
		Crawl-delay: 10
		Disallow: /Appi
		Disallow: /config
		';
		
		$fp = fopen ('robots.txt', 'w');   
		fwrite($fp, $this->replaceTab($output));   
		fclose($fp);
	}

	protected function installIndex() {

		$output = '
		<?php
		include_once "autoLoader.php";

		use Appi\Core\Init;
		use Appi\Core\EnumConst;
		use Appi\Core\QueryBuilder;
		use Appi\Core\Server;
		use Appi\Core\Template;

		$init = new Init;
		$init->initAppi();

		if (!empty($_GET[\'ajax_p\'])) {
			switch ($_GET[\'ajax_p\']) {
				case \'utc\':
					Server::setClientUTC($_GET[\'utc\']);
					break;
			}
		}

		$server = new Server;';
		if ($_SERVER['REQUEST_URI'] == '/') {
			$output .= '
			$view = new Template(\'/views/twig/\');
			';
		}
		else {
			$output .= '
			$view = new Template($_SERVER[\'REQUEST_URI\'] . \'/views/twig/\');
			';
		}
		$output .= '
		/*
		$timeNow = $server->unixNow(); // Time Now
		$qb = new QueryBuilder();
		$qb->connectDataBase(EnumConst::DB_HOST, EnumConst::DB_NAME, EnumConst::DB_USER, EnumConst::DB_PASS);
		*/

		echo $view->display(\'header\' . $init->config->Template);
		$view->set(\'version\', \'v\' . EnumConst::VERSION);
		$view->set(\'title\', \'Woohoo!<br>Framework has been installed<br>\');
		echo $view->display(\'index\' . $init->config->Template);
		echo $view->display(\'footer\' . $init->config->Template);
		';
		
		$fp = fopen ('index.php', 'w');   
		fwrite($fp, $this->replaceTab($output));   
		fclose($fp);  
	}

	protected function installHeaderTwig() {
		$output = '
		<!DOCTYPE html>
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta name="Description" content="This new framework by Appi">
				<meta name="robots" content="all"/>

				<title>Appi - Main</title>

				<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
				<script type="text/javascript" src="views/js/appi.main.js"></script>

				<link rel="stylesheet" type="text/css" href="views/css/appi.style.css">
			</head>
			<body>
		';

		$fp = fopen ('views/twig/header.twig', 'w');   
		fwrite($fp, $this->replaceTab($output));   
		fclose($fp);  
	}

	protected function installIndexTwig() {
		$output = '
		<section class="center" style="width:300px; text-align: center;">
			<img src="http://fw.byappi.com/img/logo.png" style="width: 200px; height: 200px;">
			<h1>{{ echo $this->title; }}</h1>
			{{ echo $this->version }}
			{* 
			Comment tag. 
			{! if u want use double {} !}
			*}
		</section>
		';

		$fp = fopen ('views/twig/index.twig', 'w');    
		fwrite($fp, $this->replaceTab($output));   
		fclose($fp);  
	}

	protected function installFooterTwig() {
		$output = '
			</body>
		</html>
		';

		$fp = fopen ('views/twig/footer.twig', 'w');    
		fwrite($fp, $this->replaceTab($output));   
		fclose($fp);  	
	}

	protected function replaceTab($data) {
		$lit = ["\t\t"];
		$sp = [''];
		return str_replace($lit, $sp, $data);
	}
}