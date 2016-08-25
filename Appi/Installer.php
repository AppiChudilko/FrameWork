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

		try {
			$this->qb
				->createQueryBuilder(EnumConst::STATS)
				->createTableSql([EnumConst::ST_IP, EnumConst::ST_COUNT, EnumConst::ST_LAST_CONNECT], ['varchar(32)', 'int(11)', 'int(11)'])
			;

			$this->qb
				->createQueryBuilder(EnumConst::STATS_DAY)
				->createTableSql([EnumConst::ST_D_ALL_COUNT, EnumConst::ST_D_COUNT, EnumConst::ST_D_DAY, EnumConst::ST_D_YEAR], ['int(11)', 'int(11)', 'int(11)', 'int(11)'])
			;
			return true;
		} 
		catch (Exception $e) {
			return $e;
		}
	}
}