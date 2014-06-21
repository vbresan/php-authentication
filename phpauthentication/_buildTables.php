<?php


include_once(dirname(__FILE__) . "/class/Properties.class.php");
include_once(dirname(__FILE__) . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

$m = epManager::instance();

$ea1 = $m->create("User");
$ea1->setUserName("anonymous");
$m->commit($ea1);

$ea2 = $m->create("LastLogin");
$m->find($ea2);

$ea3 = $m->create("TempVerification");
$m->find($ea3);

$ea4 = $m->create("TempInvitation");
$m->find($ea4);

$ea5 = $m->create("MaskedCookieData");
$m->find($ea5);

echo "Done!";

?>