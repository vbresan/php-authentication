<?php

include_once(dirname(__FILE__) . "/Cookie.class.php");
include_once(dirname(__FILE__) . "/Properties.class.php");

include_once(dirname(__FILE__) . "/../" . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

class CurrentUser {
	
	public static $ID_ANONYMOUS = 1;
	
	/**
	 * @return int
	 */
	public static function getUser_ID() {
		
		$user_ID = CurrentUser::$ID_ANONYMOUS;
		
		if (isset($_SESSION["user_ID"])) {
			$user_ID = $_SESSION["user_ID"];
		} else {
			$user_ID = Cookie::getUser_ID();
		}
		
		return $user_ID;
	}
	
	/**
	 * @return string
	 */
	public static function getUserName() {
		
		$m = epManager::instance();
		
		$user = $m->get("User", CurrentUser::getUser_ID());
		if ($user) {
			return $user->getUserName();
		} else {
			return "anonymous";
		}
	}
}

?>
