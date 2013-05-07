<?php
/**
 * SubMenuWidget Widget
 *
 * Creates a widget that shows this page's child pages
 *
 * @package rockharbor
 * @subpackage rockharbor.libs.widgets
 */
class SubMenuWidget extends Widget {

	public $settings = array(
		'base_id' => 'sub_menu',
		'name' => 'Submenu',
		'description' => 'Shows a submenu of the current page\'s children pages.'
	);

}