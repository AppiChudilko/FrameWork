<?php

namespace Appi\Core;

/**
* AntiDDos
*/
class AntiDDos
{
    protected $whiteIp;

    protected $countConnect = 30;

    protected $timeOut = 10;

    protected $timeNow;

    protected $DDosMessage;
 
    function __construct($ips = ['localhost', '127.0.0.1'])
    {        
        $this->whiteIp = $ips;
        $this->timeNow = time();
        $this->DDosMessage = 'Time out '.$this->timeOut.'s';
        $this->antiDDos();
    }
 
    /**
    * Mehtod. CheckDDos;
    */
    public function antiDDos() {
 
        if (empty($_COOKIE['AntiDDos'])) {
           
            if (!in_array(Server::getClientIp(), $this->whiteIp )) {
           
                if (empty($_SESSION['lastConnect'])) {
                    $_SESSION['lastConnect'] = $this->timeNow;
                    $_SESSION['countConnect'] = 0;
                }
                elseif ($_SESSION['lastConnect'] > $this->timeNow + $this->timeOut) {
                    $_SESSION['lastConnect'] = $this->timeNow;
                    $_SESSION['countConnect'] = 0;
                }
 
                $_SESSION['countConnect']++;
 
                if ($_SESSION['countConnect'] >= $this->countConnect) {
                    setcookie("AntiDDos", true, $this->timeNow + $this->timeOut);
                    $_SESSION['countConnect'] = 0;
                    die($this->DDosMessage);
                }
            }
        }
        else {
            die($this->DDosMessage);
        }
    }

    /**
    * Mehtod. Set whiteIp;
    */
    public function setWhiteIp($params) {

    	$this->whiteIp = $params;
    	return $this;
    }

    /**
    * Mehtod. Get whiteIp;
    */
    public function getWhiteIp($params) {

    	return $this->whiteIp;
    }

    /**
    * Mehtod. Set timeOut;
    */
    public function setTimeOut($params) {
    	
    	$this->timeOut = $params;
    	return $this;
    }

    /**
    * Mehtod. Get timeOut;
    */
    public function getTimeOut($params) {

    	return $this->timeOut;
    }

    /**
    * Mehtod. Set countConnect;
    */
    public function setCountConnect($params) {
    	
    	$this->countConnect = $params;
    	return $this;
    }

    /**
    * Mehtod. Get countConnect;
    */
    public function getCountConnect($params) {

    	return $this->countConnect;
    }
}