<?php


session_start();

ini_set("log_errors", 1);
ini_set("display_errors", 0);

include_once(dirname(__FILE__) . "/class/Properties.class.php");
include_once(dirname(__FILE__) . "/class/CurrentUser.class.php");
include_once(dirname(__FILE__) . "/class/MessageFactory.class.php");

include_once(dirname(__FILE__) . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");
include_once(dirname(__FILE__) . "/lib/i18n.lib.php");


$m = epManager::instance();

/*******************************************************************************
 * Start
 ******************************************************************************/

$message = MessageFactory::getMessage("GetPrivateData");

$user_ID = CurrentUser::getUser_ID();

if ($user_ID != CurrentUser::$ID_ANONYMOUS) {
	
	$user = $m->get("User", $user_ID);
	$message->displayPrivateData($user);
	
} else {
	
	$message->display(true, msg("ERR_SHOULD_LOG_IN3"));
}

?>