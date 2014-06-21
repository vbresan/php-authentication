<?php

class Properties {

	private static $instance = null;
	
	private $properites = null;
	
	/**
	 *
	 */
	private function __construct() {
		
		$this->properites = parse_ini_file(dirname(__FILE__) . "/../properties.ini", true);
	}
	
	/**
	 * @return Properties
	 */
	private static function getInstance() {
		
		if (Properties::$instance == null) {
			Properties::$instance = new Properties();
		} 
		
		return Properties::$instance;
	}	
	
	/**
	 * @param string $section
	 * @param string $key
	 * @return mixed
	 */
	public static function getProperty($section, $key) {
		
		$instance = Properties::getInstance();
		
		return $instance->properites[$section][$key];
	}
	
}

?>