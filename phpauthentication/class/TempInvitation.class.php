<?php

class TempInvitation {
	
    /**
     * @var string
     * @orm char(10)
     */
    public $registrationCode = null;
    
    /**
     * @var string
     * @orm char(64)
     */
    public $eMailAddress = null;    
    
    /**
     * @var int
     * @orm datetime
     */
    public $creationTime = null;  
}

?>