<?php

namespace Appi;

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

		if (empty($_SESSION['time_stats']) || $_SESSION['time_stats']+ 600 < ($this->timeNow)) {
			
			$this->getClientStats();

			if (!empty($this->statsResult)) {
				if (gmdate('Y/d', $this->timeNow) != gmdate('Y/d', $this->statsResult[EnumConst::ST_LAST_CONNECT])) {
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
			->orderBy(EnumConst::ST_LAST_CONNECT.' DESC,id DESC')
			->executeQuery()
			->getResult()
		;

		return $result;
	}

	public function getAllDaysStats() {
		$result = $this->qb
			->createQueryBuilder(EnumConst::STATS_DAY)
			->selectSql()
			->orderBy(EnumConst::ST_D_YEAR.' ASC,'.EnumConst::ST_D_DAY.' ASC')
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
			->updateSql([EnumConst::ST_COUNT, EnumConst::ST_LAST_CONNECT], [$this->statsResult[EnumConst::ST_COUNT] + $_SESSION['count_stats'], $this->timeNow])
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