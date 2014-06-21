<?php

include_once(dirname(__FILE__) . "/CurrentUser.class.php");
include_once(dirname(__FILE__) . "/MaskedCookieData.class.php");

class Cookie {
	
	private static $EXPIRE_SECONDS = 31536000; // 365 * 24 * 60 * 60;
	
	/**
	 * @param int $user_ID
	 */
	public static function saveUser_ID($user_ID) {

		if ($user_ID != CurrentUser::$ID_ANONYMOUS) {
			
			$expireTime = time() + Cookie::$EXPIRE_SECONDS;
			$cookie_ID  = MaskedCookieData::save($user_ID);
			 
			setcookie("user_ID", $cookie_ID, $expireTime);
		}
	}
	
	/**
	 *
	 */
	public static function deleteUser_ID() {

		$expireTime = time() - Cookie::$EXPIRE_SECONDS;
		MaskedCookieData::delete(CurrentUser::getUser_ID());
		
		setcookie("user_ID", "", $expireTime);
	}
	
	/**
	 * @return int
	 */
	public static function getUser_ID() {
		
		$user_ID = CurrentUser::$ID_ANONYMOUS;
		
		if (isset($_COOKIE["user_ID"])) {
			
			$masked_ID = $_COOKIE["user_ID"];
			$user_ID   = MaskedCookieData::getUser_ID($masked_ID);
		}
		
		return $user_ID;
	}
}

?>