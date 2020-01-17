<?php

namespace Produtil;

use PDO;
use PDOExcetption;

class Database {
	public function getInstance(){
		try{
			$instance = new PDO(GUESTBOOK_CONFIG['driver'].":host=" . GUESTBOOK_CONFIG['host'] . ";dbname=" . GUESTBOOK_CONFIG['dbname'], GUESTBOOK_CONFIG['username'], GUESTBOOK_CONFIG['passwd'], GUESTBOOK_CONFIG['options']);
			return $instance;
		}catch(PDOExcetption $exception){
			self::$error = $exception;
		}
	}

	public static function getError(): ?PDOExcetption{
		return self::$error;
	}

	function __construct(){}
}