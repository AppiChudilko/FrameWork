<?php

namespace Appi\Core;

/**
* Request
*/
class Request
{
	public function getRequest($params = []) {
		if (!empty($params)) {

			$result = [];

			foreach ($params as $value) {
				if (preg_match('#/' . $value . '_([^_/]+)#', $_SERVER['REQUEST_URI'], $match)) {
				    $result [] = $match[1];
				}
			}
			return $result;
		}
		return false;
	}
}