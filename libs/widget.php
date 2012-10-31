<?php
/**
 * Widget
 * 
 * A Widget class is a wrapper for adding a widget. All widgets for this theme
 * should extend this class.
 * 
 * ### Views
 * The frontend view is partially rendered here using the settings included 
 * during the registration (think: layout), while the actual widget content is
 * rendered using the `<id>.tpl` element in the `elements/widgets` 
 * directory.
 * 
 * The admin view is rendered using the `<id>.tpl` element in the 
 * `elements/admin/widgets` directory.
 * 
 * @package rockharbor
 * @subpackage rockharbor.libs
 */
class Widget extends WP_Widget {

/**
 * The theme class
 * 
 * @var RockharborThemeBase 
 */
	public $theme = null;
	
/**
 * Settings array
 * 
 * - `$base_id` The base id for all instances of this widget
 * - `$name` Name of widget
 * - `$description` Widget description
 * 
 * @var array
 */
	public $settings = array();
	
/**
 * The unique id for this widget instance, set by `WP_Widget` constructor.
 * 
 * @var string
 */
	public $id = null;
	
/**
 * Creates the widget using settings defined in the subclass
 */
	public function __construct() {
		// Lovely. WP doesn't allow dependency injection. #YAWPH
		global $theme;
		$this->theme = $theme;
		parent::__construct(
			$this->settings['base_id'],
			$this->settings['name'],
			array(
				'description' => $this->settings['description']
			)
		);
	}

/**
 * Renders the front-end widget
 * 
 * @param array $args Options provided at registration
 * @param array $data Database values provided in backend
 */
	public function widget($args, $data) {
		$title = apply_filters('widget_title', $data['title']);
		
		echo $args['before_widget'];
		if (!empty($title)) {
			echo $args['before_title'].$title.$args['after_title'];
		}
		$this->theme->set('data', $data);
		echo $this->theme->render('widgets' . DS . $this->settings['base_id']);
		echo $args['after_widget'];
	}

/**
 * Called when the widget is updated in the backend
 * 
 * @param array $newData New data
 * @param array $oldData Old data
 * @return array 
 */
	public function update($newData, $oldData) {
		$newData['title'] = strip_tags($newData['title']);
		return $newData;
	}

/**
 * Renders the backend widget form
 * 
 * @param array $data Database values provided in backend
 */
	public function form($data) {
		$_defaults = array(
			'title' => 'Widget'
		);
		$data = array_merge($_defaults, $data);
		
		// need to use methods in this class to generate fields
		$this->theme->set('widget', $this);
		$this->theme->set('data', $data);
		echo $this->theme->render('admin' . DS . 'widgets' . DS . $this->settings['base_id']);
	}
	
}