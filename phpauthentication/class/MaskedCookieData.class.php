<?php


include_once(dirname(__FILE__) . "/CurrentUser.class.php");
include_once(dirname(__FILE__) . "/Properties.class.php");

include_once(dirname(__FILE__) . "/../" . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

class MaskedCookieData {
	
    /**
     * @var int
     * @orm int(12) unique(user_ID)
     */
    public $user_ID = 0;

    /**
     * @var string
     * @orm char(21)
     */
    public $masked_ID = "";
    
    /**
     * @param int $user_ID
     * @param string $masked_ID
     */
    private static function updateExistingEntry($user_ID, $masked_ID) {
    	
    	$m = epManager::instance();
    	
    	$found = $m->find("from MaskedCookieData as m where m.user_ID = ?", $user_ID);
    	if ($found) {
    		
    		try {
    			
    			$found[0]->setMasked_ID($masked_ID);
    			$found[0]->epSetCommittable(true);
    			$m->commit($found[0]);
    			
    			return true;
    			
    		} catch (Exception $e) {
    			return false;
    		}
    	} else {
    		return false;
    	}
    }
    
    /**
     * @param int $user_ID
     * @param string $masked_ID
     */
    private static function createNewEntry($user_ID, $masked_ID) {
		
		$m = epManager::instance();
		
		$data = $m->create("MaskedCookieData");
		$data->setUser_ID($user_ID);
		$data->setMasked_ID($masked_ID);
		
		try {
			
			$m->commit($data);
			
		} catch (Exception $e) {
			// do nothing
		}	    	
    }
    
    /**
     * @param int $user_ID
     */
    public static function save($user_ID) {
 		
		$masked_ID = time() . "-" . rand(1000000000, 9999999999);
		
		if (! MaskedCookieData::updateExistingEntry($user_ID, $masked_ID)) {
			MaskedCookieData::createNewEntry($user_ID, $masked_ID);
		}
		
		return $masked_ID;   	
    }
    
    /**
     * @param int $user_ID
     */
    public static function delete($user_ID) {
		
    	$m = epManager::instance();
    	
		$found = $m->find("from MaskedCookieData as m where m.user_ID = ?", $user_ID);
		foreach ($found as $item) {
			$m->delete($item);
		}
    }
    
    /**
     * @param string $masked_ID
     * @return int
     */
    public static function getUser_ID($masked_ID) {
    		
    	$m = epManager::instance();
    	
		$found = $m->find("from MaskedCookieData as m where m.masked_ID = ?", $masked_ID);
		if ($found) {
			return $found[0]->getUser_ID();
		} else {
			return CurrentUser::$ID_ANONYMOUS;
		}
    }

}

?>