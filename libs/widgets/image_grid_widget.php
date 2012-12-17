<?php
/**
 * ImageGridWidget Widget
 * 
 * Creates a widget with responsive images in a grid
 * 
 * @package rockharbor
 * @subpackage rockharbor.libs.widgets
 */
class ImageGridWidget extends Widget {
	
	public $settings = array(
		'base_id' => 'image_grid',
		'name' => 'Image Grid',
		'description' => 'Widget for showing images in a grid.'
	);
	
/**
 * Renders the frontend widget
 * 
 * This widget uses the widget settings to create a grid of images.
 * 
 * @param array $args Options provided at registration
 * @param array $data Database values provided in backend
 */
	public function widget($args, $data) {
		$defaults = array(
			'columns' => 2,
			'images' => array(),
			'image_links' => array(),
			'before_content' => '',
			'after_content' => ''
		);
		$data = array_merge($defaults, $data);
		
		parent::widget($args, $data);
	}

/**
 * Renders the form for the widget
 * 
 * @param array $data Database values
 */
	public function form($data) {
		$defaults = array(
			'columns' => 2,
			'images' => array(),
			'image_links' => array(),
			'before_content' => '',
			'after_content' => ''
		);
		$data = array_merge($defaults, $data);
		parent::form($data);
	}
	
}