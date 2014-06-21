<?php


include_once(dirname(__FILE__) . "/../lib/password.lib.php");

class User {
	
    /**
     * @var string
     * @orm char(64) unique(userName)
     */
    public $userName = null;

    /**
     * @var string
     * @orm char(32)
     */
    public $password = null;

    /**
     * @var string
     * @orm char(64) unique(eMailAddress)
     */
    public $eMailAddress = null;

    /**
     * @var char
     * @orm char(1)
     */
    public $gender = null;

    /**
     * @var int
     * @orm int(12)
     */
    public $birthYear = null;

    /**
     * @var int
     * @orm datetime
     */
    public $memberSince = null;

    /**
     * @var boolean
     * @orm bool
     */
    public $isVerified = null;
    
    /**
     * @var int
     * @orm int(12)
     */    
    public $remainingInvitations = null;

    /**
     * 
     * @param $userName string
     * @return boolean
     */
    public function isUserNameOK($userName) {
    	
    	return strcmp($this->userName, $userName) == 0; 
    }

    /**
     * 
     * @param $password string
     * @return boolean
     */
    public function isPasswordOK($password) {
    	
		$password = saltPassword($password, $this->memberSince);
		return strcmp($this->password, $password) == 0;    	
    }
}

?>
