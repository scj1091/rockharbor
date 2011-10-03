<?php
/**
 * Staff class. Handles everything needed to create a Staff post type, save
 * the shortcode which is found in `/libs/shortcodes.php`
 * 
 * @package rockharbor
 */
class Staff {
	
/**
 * The theme object
 * 
 * @var RockharborThemeBase 
 */
	protected $theme = null;
	
/**
 * Sets the theme object for use in this class and instantiates the Staff post
 * type and related needs
 * 
 * @param RockharborThemeBase $theme 
 */
	public function __construct($theme = null) {
		$this->theme = $theme;
		
		register_taxonomy('department', 'staff', array(
			'label' => __('Department', 'rockharbor'),
			'sort' => true,
			'rewrite' => array('slug' => 'department')
		));
		
		register_post_type('staff', array(
			'label' => __('Staff', 'rockharbor'),
			'singular_label' =>__('Staff', 'rockharbor'),
			'description' => __('Add staff', 'rockharbor'),
			'public' => true,
			'hierarchical' => false,
			'supports' => array(
				'editor',
				'thumbnail'
			),
			'rewrite' => array('slug' => 'staff', 'with_front' => false)
		));
		
		add_action('admin_init', array($this, 'adminInit'));
		add_filter('wp_insert_post_data', array($this, 'beforeSave'), 1, 2);
		
		// include css
		wp_register_style('staff', $theme->info('base_url').'/css/staff.css');
		wp_enqueue_style('staff');
		
		
		$theme->archiveTemplates += array('staff' => 'Staff');
	}

/**
 * Modifies the query for showing the archive pages
 * 
 * @param array $query The existing query
 * @return array Modified query
 */
	public function query($query) {
		$query['numberofposts'] = -1;
		return $query;
	}
	
/**
 * Automatically adds a title for this post based on the meta
 */
	public function beforeSave($data) {
		if ($data['post_type'] == 'staff') {
			$data['post_name'] = strtolower($_POST['meta']['first_name'].'-'.strtolower($_POST['meta']['last_name']));
			$data['post_title'] = $_POST['meta']['first_name'].' '.$_POST['meta']['last_name'];
		}
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