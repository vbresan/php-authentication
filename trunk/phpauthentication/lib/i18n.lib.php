<?php

include_once(dirname(__FILE__) . "/messages.php");
include_once(dirname(__FILE__) . "/../class/Properties.class.php");

$defaultLanguage = Properties::getProperty("General", "DefaultLanguage");
$LANG = getLanguage();

/*******************************************************************************
 * Functions
 ******************************************************************************/

/**
 * @return string
 */
function getLanguage() {
	
	global $defaultLanguage;
	global $messages;
	
	$language = "";

	if (isset($_POST["lang"])) {
		$language = trim($_POST["lang"]);
	} else if (isset($_GET["lang"])) {
		$language = trim($_GET["lang"]);
	}
	
	if (strlen($language) && isset($messages[$language])) {
		return $language;
	} else {
		return $defaultLanguage;
	}
}

/**
 * @param string $s
 * @return string
 */
function msg($s) {
	
    global $LANG;
    global $messages;
    
    if (isset($messages[$LANG][$s])) {
        return $messages[$LANG][$s];
    } else {
        error_log("l10n error: LANG: " . "$lang, message: '$s'");
    }
}

?>