<?php


session_start();

ini_set("log_errors", 1);
ini_set("display_errors", 0);

include_once(dirname(__FILE__) . "/class/CurrentUser.class.php");
include_once(dirname(__FILE__) . "/class/MessageFactory.class.php");
include_once(dirname(__FILE__) . "/class/Properties.class.php");
include_once(dirname(__FILE__) . "/class/Session.class.php");

include_once(dirname(__FILE__) . "/lib/i18n.lib.php");
include_once(dirname(__FILE__) . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

$m = epManager::instance();

/*******************************************************************************
 * Functions
 ******************************************************************************/

/**
 * @param int $user_ID
 */
function deleteLastLogin($user_ID) {
	
	global $m;
	
	$search = $m->create("LastLogin");
	$search->setUser_ID($user_ID);
	
	$lastLogins = $m->find($search);
	foreach ($lastLogins as $lastLogin) {
		
		$m->delete($lastLogin);
	}
}

/**
 * @param int $user_ID
 */
function deleteUser($user_ID) {
	
	global $m;
	
	$user = $m->get("User", $user_ID);
	$m->delete($user);
}

/*******************************************************************************
 * Start
 ******************************************************************************/

$message = MessageFactory::getMessage("UnregisterUser");

$user_ID = CurrentUser::getUser_ID(); 

if ($user_ID != CurrentUser::$ID_ANONYMOUS) {

	deleteLastLogin($user_ID);
	deleteUser($user_ID);
	
	Session::destroy();
	
	$message->display(false, msg("UNREGISTER_OK"));
	
} else {

	$message->display(true, msg("ERR_SHOULD_LOG_IN2"));
}

?>