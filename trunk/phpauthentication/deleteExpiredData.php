<?php


ini_set("log_errors", 1);
ini_set("display_errors", 0);

include_once(dirname(__FILE__) . "/class/Properties.class.php");
include_once(dirname(__FILE__) . "/class/TempInvitation.class.php");
include_once(dirname(__FILE__) . "/class/TempVerification.class.php");

include_once(dirname(__FILE__) . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

$m = epManager::instance();

/*******************************************************************************
 * Functions
 ******************************************************************************/

/**
 * @param int $currentTime
 */
function deleteExpiredTempInvitations($currentTime) {
	
	global $m;
	
	$daysToExpire = Properties::getProperty("DaysToExpire", "TempInvitation");
	if ($daysToExpire == 0) {
		return;
	}
	
	$expireTimestamp = $currentTime - $daysToExpire * 24 * 60 * 60;
	
	$tempInvitations = $m->find("from TempInvitation as t where t.creationTime < ?", $expireTimestamp);
	foreach ($tempInvitations as $tempInvitation) {
		
		$m->delete($tempInvitation);
	}	
}

/**
 * @param int $currentTime
 */
function expireTempVerification($currentTime) {
	
	global $m;
	
	$daysToExpire = Properties::getProperty("DaysToExpire", "TempVerification");
	if ($daysToExpire == 0) {
		return;
	}
	
	$expireTimestamp = $currentTime - $daysToExpire * 24 * 60 * 60;
	
	$tempVerifications = $m->find("from TempVerification as t where t.creationTime < ?", $expireTimestamp);
	foreach ($tempVerifications as $tempVerification) {
		
		$user_ID = $tempVerification->getUser_ID();
		$users   = $m->find("from User as u where u.oid = ? and u.isVerified = 0", $user_ID);
		foreach ($users as $user) {
			$m->delete($user);
		}
		
		$m->delete($tempVerification);
	}	
}

/*******************************************************************************
 * Start
 ******************************************************************************/

$currentTime  = time();

deleteExpiredTempInvitations($currentTime);
expireTempVerification($currentTime);

?>