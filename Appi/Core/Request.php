<?php

namespace Appi\Core;

/**
* Request
*/
class Request
{
	public function getRequest($params = []) {

		$result = [];
		$params = array_merge($params, json_decode(file_get_contents('config/request.json'), true));
		$i = 0;

		if (empty($params)) return false;

		foreach ($params as $value) {
			if (preg_match('#/' . $value . '([^_/]+)#', $_SERVER['REQUEST_URI'], $match)) {
			    $result[$value] = $match[1];
			}
			else if (preg_match('#' . $value . '#', $_SERVER['REQUEST_URI'], $match)) {
				$result['p'] = $match[0];
			}
		}
		return $result;
	}
}