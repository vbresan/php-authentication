<?php


include_once(dirname(__FILE__) . "/Message.class.php");
include_once(dirname(__FILE__) . "/CurrentUser.class.php");

class JSONMessage extends Message {
	
	/**
	 * 
	 * @param string $type
	 */
	public function __construct($type) {
		parent::__construct($type);
		
		header("Content-type: application/json");
	}
	
	/**
	 * @param boolean $error
	 * @param string  $message
	 */
	public function display($error, $message = '') {
		
		$this->queue($error, $message);
		
		$messageArray = array();
		foreach ($this->getMessageQueue() as $message) {
			$messageArray[] = $message;
		}
		
		$data = array(
			"type"     => $this->getType(),
			"error"    => $this->getError(),
			"userName" => CurrentUser::getUserName(),
			"message"  => $messageArray,
		);
		
		echo json_encode($data);	
	}
	
	/**
	 * @param string $userName
	 */
	public function displayUserName($userName) {
		
		$data = array(
			"type"     => $this->getType(),
			"error"    => false,
			"userName" => $userName,
			"message"  => array(),
		);
		
		echo json_encode($data);
	}

	/**
	 * @param User $user
	 */
	public function displayPrivateData($user) {
		
		$data = array(
			"type"        => $this->getType(),
			"error"       => false,
			"userName"    => CurrentUser::getUserName(),
			"message"     => array(),
			"privateData" => array(
								"eMailAddress" => $user->getEMailAddress(),
								"gender"	   => $user->getGender(),
								"birthYear"    => $user->getBirthYear(),
							 ),
		);
		
		echo json_encode($data);		
	}	
	
	/**
	 * @param int $remainingInvitations
	 */
	public function displayRemainingInvitations($remainingInvitations) {
		
		$data = array(
			"type"                 => $this->getType(),
			"error"                => false,
			"userName"             => CurrentUser::getUserName(),
			"message"              => array(),
			"remainingInvitations" => $remainingInvitations,
		);
		
		echo json_encode($data);
	}	
}

?>