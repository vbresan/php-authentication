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
 * Start
 ******************************************************************************/

$message = MessageFactory::getMessage("LogOut");

Session::destroy();

$message->display(false, msg("LOG_OUT_OK"));

?>