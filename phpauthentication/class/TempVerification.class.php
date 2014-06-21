<?php


include_once(dirname(__FILE__) . "/Properties.class.php");

class TempVerification {
	
    /**
     * @var int
     * @orm int(12)
     */
    public $user_ID = null;

    /**
     * @var string
     * @orm char(21)
     */
    public $verificationCode = null;
    
    /**
     * @var int
     * @orm datetime
     */
    public $creationTime = null;     
    
    /**
     * @return string
     */
    public function getLink() {
    	
    	$link = Properties::getProperty("VerificationMail", "Link");
    	$link = str_replace("{\$code}", $this->verificationCode, $link);
    	
    	return $link; 
    }
} 

?>