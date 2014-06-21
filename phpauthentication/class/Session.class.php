<?php


include_once(dirname(__FILE__) . "/Cookie.class.php");

class Session {
	
	/**
	 * 
	 */
	public static function destroy() {
		
		Cookie::deleteUser_ID();

		// Unset all of the session variables.
		$_SESSION = array();
		
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (isset($_COOKIE[session_name()])) {
		    setcookie(session_name(), "", time() - 42000, "/");
		}
		
		// Finally, destroy the session.
		session_destroy();		
	}
}

?>