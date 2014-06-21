<?php


include_once(dirname(__FILE__) . "/Message.class.php");
include_once(dirname(__FILE__) . "/CurrentUser.class.php");

class XMLMessage extends Message {
	
	/**
	 * 
	 * @param string $type
	 */
	public function __construct($type) {
		parent::__construct($type);
		
		header("Content-type: text/xml; charset=UTF-8");
	}
	
	/**
	 * @param boolean $error
	 * @param string  $message
	 */
	public function display($error, $message = '') {
		
		$this->queue($error, $message);
		
		echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		echo '<XMLMessage type="' . $this->getType() . '">' . "\n";
		echo "\t" . '<Error>'     . ($this->getError() ? 'true' : 'false') . '</Error>'    . "\n";
		echo "\t" . '<UserName>'  . CurrentUser::getUserName()                  . '</UserName>' . "\n";
		
		foreach ($this->getMessageQueue() as $message) {
			echo "\t" . '<Message>' .  $message . '</Message>' . "\n";
		}
		
		echo '</XMLMessage>' . "\n";
	}
	
	/**
	 * @param string $userName
	 */
	public function displayUserName($userName) {
		
		echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		echo '<XMLMessage type="' . $this->getType() . '">' . "\n";
		echo "\t" . '<Error>'     . 'false'    . '</Error>'    . "\n";
		echo "\t" . '<UserName>'  .  $userName . '</UserName>' . "\n";
		echo "\t" . '<Message>'   .  ''        . '</Message>'  . "\n";
		echo '</XMLMessage>' . "\n";
	}

	/**
	 * @param User $user
	 */
	public function displayPrivateData($user) {
		
		echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		echo '<XMLMessage type="' . $this->getType() . '">' . "\n";
		echo "\t" . '<Error>'     . 'false'                     . '</Error>'    . "\n";
		echo "\t" . '<UserName>'  .  CurrentUser::getUserName() . '</UserName>' . "\n";
		echo "\t" . '<Message>'   .  ''                         . '</Message>'  . "\n";
		echo "\t" . '<PrivateData>' . "\n";
		
		echo "\t\t" . '<EMailAddress>' . $user->getEMailAddress() . '</EMailAddress>' . "\n";
		echo "\t\t" . '<Gender>'       . $user->getGender()       . '</Gender>'       . "\n";
		echo "\t\t" . '<BirthYear>'    . $user->getBirthYear()    . '</BirthYear>'    . "\n";
		
		echo "\t" . '</PrivateData>' . "\n";
		echo '</XMLMessage>' . "\n";
	}	
	
	/**
	 * @param int $remainingInvitations
	 */
	public function displayRemainingInvitations($remainingInvitations) {
		
		echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		echo '<XMLMessage type="' . $this->getType() . '">' . "\n";
		echo "\t" . '<Error>'     . 'false'                     . '</Error>'    . "\n";
		echo "\t" . '<UserName>'  .  CurrentUser::getUserName() . '</UserName>' . "\n";		
		echo "\t" . '<Message>'   .  ''                         . '</Message>'  . "\n";
		echo "\t" . '<RemainingInvitations>' .  $remainingInvitations . '</RemainingInvitations>' . "\n";		
		echo '</XMLMessage>' . "\n";
	}	
}

?>
