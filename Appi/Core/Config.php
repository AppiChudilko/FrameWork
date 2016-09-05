<?php

namespace Appi\Core;

/**
* Config
*/
class Config
{
	protected $result;

	public function getConfig($file) {
		ob_start();
		include 'config/' . $file . '.json';
		$this->result = ob_get_clean();
		
		return $this;
	}

	public function getAppiAllConfig() {
		ob_start();
		include 'config/appi.json';
		$this->result = ob_get_clean();

		return $this;
	}

	public function getArrayResult() {
		return json_decode($this->result, true);
	}

	public function getObjectResult() {
		return json_decode($this->result);
	}

	public function getJsonResult() {
		return $this->result;
	}
}