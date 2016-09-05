<?php

namespace Appi\Core;

/**
* Hash
*/
class Hash
{
	public function fullHash($value) {
		return password_hash(md5($value), PASSWORD_DEFAULT);
	}

	public function hashIsValid($value, $hash) {
		return password_verify(md5($value), $hash);
	}
}