<?php
/**
 * Staff 
 * 
 * Handles everything needed to create a Staff post type.
 * 
 * @package rockharbor
 * @subpackage rockharbor.libs
 */
class Staff extends PostType {

/**
 * Post type options
 * 
 * @var array
 */
	public $options = array(
		'name' => 'Staff',
		'plural' => 'Staff',
		'slug' => 'staff',
		'archive' => true,
		'supports' => array(
			'editor',
			'thumbnail'
		)
	);
	
/**
 * Default archive query
 * 
 * @var array
 */
	public $archiveQuery = array(
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC'
	);
	
/**
 * Sets the theme object for use in this class and instantiates the Staff post
 * type and related needs
 * 
 * @param RockharborThemeBase $theme 
 */
	public function __construct($theme = null) {
		parent::__construct($theme);
		
		register_taxonomy('department', 'staff', array(
			'label' => __('Department', 'rockharbor'),
			'sort' => true,
			'rewrite' => array('slug' => 'department')
		));
	}

/**
 * Automatically adds a title for this post based on the meta
 *
 * @param integer $data Post data
 * @return array Modified post data
 */
	public function beforeSave($data) {
		$data['post_name'] = strtolower($_POST['meta']['first_name'].'-'.strtolower($_POST['meta']['last_name']));
		$data['post_title'] = $_POST['meta']['first_name'].' '.$_POST['meta']['last_name'];
		return $data;
	}

/**
 * Inits extra admin goodies
 */
	public function adminInit() {
		add_meta_box('staff_details', 'Details', array($this, 'detailsMetaBox'), 'staff', 'normal');
		remove_meta_box('tagsdiv-department', 'staff', 'side');
	}
	
/**
 * Renders the meta box for core events on pages
 */
	public function detailsMetaBox() {
		global $post;
		$taxes = get_terms('department', array(
			'hide_empty' => false
		));
		$departments = array();
		foreach ($taxes as $tax) {
			$departments[$tax->term_id] = $tax->name;
		}
		$this->theme->set('departments', $departments);
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		echo $this->theme->render('staff_details_meta_box');
	}
}