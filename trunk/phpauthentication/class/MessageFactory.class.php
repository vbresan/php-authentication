<?php


include_once(dirname(__FILE__) . "/Properties.class.php");
include_once(dirname(__FILE__) . "/Message.class.php");
include_once(dirname(__FILE__) . "/XMLMessage.class.php");
include_once(dirname(__FILE__) . "/JSONMessage.class.php");

class MessageFactory {

	/**
	 * 
	 * @return Message
	 */
	public static function getMessage($type) {
		
		$format = Properties::getProperty("General", "MessageFormat");
		if ($format == "JSON") {
			return new JSONMessage($type);
		} else {
			return new XMLMessage($type);
		}
	}
}

?>