<?php

namespace Produtil;

use Produtil\Database;
use DateTime;

class Guestbook{

	function registerVisitation():bool{
		try{
			$instance = Database::getInstance();

			$sql = 'insert into ' .GUESTBOOK_CONFIG["table_name"]. ' (ip) values (:ip)';
			$ip = self::getIp();

			$prepare = $instance->prepare($sql);
			$prepare->execute([
				"ip" => $ip
			]);

			return true;
		}
		catch(Exception $e){
			self::$error = $e;
			return false;
		}
	}

	public function getVisitations(){
		try{
			$lastMonth = date('Y-m-d', strtotime('-30 days'));
			$lastWeek = date('Y-m-d', strtotime('-1 week'));
			$today = date('Y-m-d');

			$instance = Database::getInstance();

			$sql = '
				select
					(select count(*) from '.GUESTBOOK_CONFIG["table_name"].') as total,
					(select count(*) from '.GUESTBOOK_CONFIG["table_name"].' where time between :lastMonth and :today) as month,
					(select count(*) from '.GUESTBOOK_CONFIG["table_name"].' where time between :lastWeek and :today) as week,
					(select count(*) from '.GUESTBOOK_CONFIG["table_name"].' where time=:today) as today,

					(select count(distinct ip) from '.GUESTBOOK_CONFIG["table_name"].') as total_users,
					(select count(distinct ip) from '.GUESTBOOK_CONFIG["table_name"].' where time between :lastMonth and :today) as users_month,
					(select count(distinct ip) from '.GUESTBOOK_CONFIG["table_name"].' where time between :lastWeek and :today) as users_week,
					(select count(distinct ip) from '.GUESTBOOK_CONFIG["table_name"].' where time=:today) as users_today
				';

			$prepare = $instance->prepare($sql);
			$prepare->execute([
					"today"=>$today,
					"lastWeek"=>$lastWeek,
					"lastMonth"=>$lastMonth
				]);

			$result = $prepare->fetch();

			return $result;
		}catch(Exception $e){
			self::$error = $e;
			return false;
		}
		
	}

	public function getVisitationsByPeriod($start, $end){
		try {
			$instance = Database::getInstance();

			$sql = 'select count(*) as visitations from '.GUESTBOOK_CONFIG["table_name"].' where time between :start and :end';

			$prepare = $instance->prepare($sql);
			$prepare->execute([
				"start" => $start,
				"end"   => $end
			]);

			$result = $prepare->fetch();

			return $result;

		} catch (Exception $e) {
			self::$error = $e;
		}
	}

	private static function getIp(){
		$ip = null;
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	function getError(){
		return self::$error;
	}

	function __construct(){
	}
}