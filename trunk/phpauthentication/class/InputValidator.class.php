<?php

include_once(dirname(__FILE__) . "/Properties.class.php");

class InputValidator {
	
	/**
	 * 
	 * @param string $userName
	 * @return boolean
	 */
	private static function isStandardUserNameValid($userName) {
		
		$MAX_LENGTH = 64;
		
		$minLength = Properties::getProperty("UserName", "MinLength");
		$maxLength = Properties::getProperty("UserName", "MaxLength");
		
		if ($maxLength > $MAX_LENGTH) {
			$maxLength = $MAX_LENGTH;
		}
		
		return strlen($userName) >= $minLength &&
		       strlen($userName) <= $maxLength &&
		       ctype_alnum($userName);		
	}

	/**
	 * @param string $password
	 * @return boolean
	 */
	public static function isPasswordValid($password) {
		
		$PASSWORD_MD5_LEN = 32;
		
		return strlen($password) == $PASSWORD_MD5_LEN &&
		       ctype_alnum($password);
	}

	/**
	 * @param string $eMailAddress
	 * @return boolean
	 */
	public static function isEMailAddressValid($eMailAddress) {
		
		return eregi("^[+_a-z0-9-]+(\.[+_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $eMailAddress);
	}

	/**
	 * Genders: 0 = not set; 1 = male; 2 = female
	 * 
	 * @param string $gender
	 * @return boolean
	 */
	public static function isGenderValid($gender) {
		
		return $gender >= 0 && $gender <= 2;
	}
	
	/**
	 * Birth year: 0 = not set; valid ages from 5 till 120 years old
	 * 
	 * @param int $birthYear
	 * @return boolean
	 */
	public static function isBirthYearValid($birthYear) {
		
		$currentYear = date("Y");
		
		return $birthYear == 0 || 
		       (($birthYear >= $currentYear - 120) && ($birthYear <= $currentYear - 5));
	}
	
	/**
	 * @param string $userName
	 * @return boolean
	 */
	public static function isUserNameValid($userName) {
		
		$isValid = InputValidator::isStandardUserNameValid($userName);
		$eMail   = Properties::getProperty("UserName", "AllowEMailAddress"); 
		
		if ($eMail) {
			$isValid = $isValid || InputValidator::isEMailAddressValid($userName);
		}
		
		return $isValid;
	}
}

?>