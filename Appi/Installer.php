<?php

namespace Appi;

use Appi\EnumConst;

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

		if (!file_exists('twig')) {
		    mkdir('twig', 0777, true);
		}
		
		try {
			$this->qb
				->createQueryBuilder(EnumConst::STATS)
				->createTableSql([EnumConst::ST_IP, EnumConst::ST_COUNT, EnumConst::ST_LAST_CONNECT], ['varchar(32)', 'int(11)', 'int(11)'])
			;

			$this->qb
				->createQueryBuilder(EnumConst::STATS_DAY)
				->createTableSql([EnumConst::ST_D_ALL_COUNT, EnumConst::ST_D_COUNT, EnumConst::ST_D_DAY, EnumConst::ST_D_YEAR], ['int(11)', 'int(11)', 'int(11)', 'int(11)'])
			;
		} 
		catch (Exception $e) {
			return $e;
		}

		$this->installHeaderTwig();
		$this->installIndexTwig();
		$this->installFooterTwig();
		$this->installIndex();

		header("Refresh: 0; url=index.php");
	}

	protected function installIndex() {

		$output = '
		<?php
		include_once "loader.php";

		use Appi\Config;
		use Appi\EnumConst;
		use Appi\QueryBuilder;
		use Appi\Server;
		use Appi\Template;

		$server = new Server;
		$view = new Template(\'/twig/\');

		$timeNow = $server->unixNow();

		/*$qb = new QueryBuilder();
		$qb->connectDataBase(EnumConst::DB_HOST, EnumConst::DB_NAME, EnumConst::DB_USER, EnumConst::DB_PASS);*/

		$view->display(\'header\'.Config::Template);

		$view->set(\'version\', EnumConst::VERSION);
		$view->display(\'index\'.Config::Template);

		$view->set(\'year\', gmdate("Y", $server->timeNow+($_SESSION[\'UTC\']*3600)));
		$view->display(\'footer\'.Config::Template);
		';
		
		$fp = fopen ('index.php', 'w');   
		fwrite($fp,$output);   
		fclose($fp);  
	}

	protected function installHeaderTwig() {
		$output = '
		<!DOCTYPE html>
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta name="Description" content="This new framework by Appi">
				<title>Appi - Main</title>
				<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
				<script type="text/javascript" src="js/appi.main.js"></script>
				<link rel="stylesheet" type="text/css" href="css/appi.style.css">
			</head>
			<body>
		';

		$fp = fopen ('twig/header.twig', 'w');   
		fwrite($fp,$output);   
		fclose($fp);  
	}

	protected function installIndexTwig() {
		$output = '
		<section class="center" style="width:300px; text-align: center;">
			<img src="http://fw.byappi.com/img/logo.png" style="width: 200px; height: 200px;">
			<h1>Woohoo! Framework has been installed</h1>
		</section>
		';

		$fp = fopen ('twig/index.twig', 'w');    
		fwrite($fp,$output);   
		fclose($fp);  
	}

	protected function installFooterTwig() {
		$output = '
			</body>
		</html>
		';

		$fp = fopen ('twig/footer.twig', 'w');    
		fwrite($fp,$output);   
		fclose($fp);  	
	}
}