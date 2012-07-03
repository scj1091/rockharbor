<?php

/**
 * ROCKHARBOR Admin class. All admin (backend) related tasks should go here.
 * 
 * @package rockharbor
 */
class Admin {
		
/**
 * The theme
 * 
 * @var RockharborThemeBase 
 */
	public $theme = null;

/**
 * Registers all shortcodes
 * 
 * @param RockharborThemeBase $theme 
 */	
	public function __construct($theme = null) {
		$this->theme = $theme;
		
		$this->init();
	}
	
/**
 * Initializes admin specific code 
 */
	public function init() {
		
	}
	
}