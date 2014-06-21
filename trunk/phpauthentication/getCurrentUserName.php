<?php


session_start();

ini_set("log_errors", 1);
ini_set("display_errors", 0);

include_once(dirname(__FILE__) . "/class/CurrentUser.class.php");
include_once(dirname(__FILE__) . "/class/MessageFactory.class.php");

/*******************************************************************************
 * Start
 ******************************************************************************/

$message = MessageFactory::getMessage("GetCurrentUserName");
$message->displayUserName(CurrentUser::getUserName());

?>
