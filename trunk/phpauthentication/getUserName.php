<?php


session_start();

ini_set("log_errors", 1);
ini_set("display_errors", 0);

include_once(dirname(__FILE__) . "/lib/i18n.lib.php");
include_once(dirname(__FILE__) . "/class/MessageFactory.class.php");
include_once(dirname(__FILE__) . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

/*******************************************************************************
 * Functions
 ******************************************************************************/

/**
 * 
 * @param int $id
 * @return string
 */
function getUserName($id) {
	
	$m = epManager::instance();
	
	$user = $m->get("User", $id);
	if ($user) {
		return $user->getUserName();
	} else {
		return "";
	}	
}

/*******************************************************************************
 * Start
 ******************************************************************************/

$message = MessageFactory::getMessage("GetUserName");

if (isset($_POST["id"]) && is_numeric($_POST["id"])) {
	
	$userName = getUserName($_POST["id"]);
	if (strlen($userName) != 0) {
		$message->displayUserName($userName);
	} else {
		$message->display(true, msg("ERR_USER_ID"));
	}
} else {
	$message->display(true, msg("ERR_USER_ID"));
}

?>
