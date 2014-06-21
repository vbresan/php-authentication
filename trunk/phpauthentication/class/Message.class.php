<?php

abstract class Message {
	
	/**
	 * @var string
	 */
	private $type = "Unknown";
	
	/**
	 * @var array
	 */
	private $messageQueue = array(
								"error" => array(),		// boolean 
								"message" => array()	// string
							);

	/**
	 * 
	 * @param string $type
	 */
	public function __construct($type) {
		$this->type = $type;
	}

	/**
	 * 
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * @param boolean $error
	 * @param string  $message
	 */
	public function queue($error, $message = '') {

		$this->messageQueue["error"][]   = $error;
		$this->messageQueue["message"][] = $message;
	}

	/**
	 * @return boolean
	 */
	public function getError() {
		
		$result = false;
		
		foreach ($this->messageQueue["error"] as $error) {
			$result |= $error;
		}
		
		return (boolean) $result;
	}

	/**
	 * 
	 * @return array
	 */
	public function getMessageQueue() {
		return $this->messageQueue["message"];
	}
	
	/**
	 * @param boolean $error
	 * @param string  $message
	 */
	abstract public function display($error, $message = '');
	
	/**
	 * @param string $userName
	 */
	abstract public function displayUserName($userName);

	/**
	 * @param User $user
	 */
	abstract public function displayPrivateData($user);
	
	/**
	 * @param int $remainingInvitations
	 */
	abstract public function displayRemainingInvitations($remainingInvitations);
}

?>