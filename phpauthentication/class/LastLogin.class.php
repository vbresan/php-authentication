<?php


include_once(dirname(__FILE__) . "/CurrentUser.class.php");
include_once(dirname(__FILE__) . "/Properties.class.php");

include_once(dirname(__FILE__) . "/../" . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

class LastLogin
{
    /**
     * @var int
     * @orm int(12) unique(user_ID)
     */
    public $user_ID = null;

    /**
     * @var int
     * @orm datetime
     */
    public $time = null;

    /**
     * @var string
     * @orm char(15)
     */
    public $ipAddress = null;

	/**
	 * @param int $user_ID
	 */
	private static function create($user_ID) {
		
		$m = epManager::instance();
		
		$lastLogin = $m->create("LastLogin");
		$lastLogin->setUser_ID($user_ID);
		$lastLogin->setTime(time());
		$lastLogin->setIpAddress($_SERVER["REMOTE_ADDR"]);
		$m->commit($lastLogin);
	}    

	/**
	 * @param int $user_ID
	 */
	public static function refresh($user_ID) {
		
		if ($user_ID == CurrentUser::$ID_ANONYMOUS) {
			return;
		}
		
		
		$m = epManager::instance();
		
		$search = $m->create("LastLogin");
		$search->setUser_ID($user_ID);
		
		$lastLogins = $m->find($search);
		if ($lastLogins) {
			
			$lastLogins[0]->setTime(time());
			$lastLogins[0]->setIpAddress($_SERVER["REMOTE_ADDR"]);
			$m->commit($lastLogins[0]);
			
		} else {
			
			LastLogin::create($user_ID);
		}
	}
	
}

?>