<?php
/**
 * Curriculum
 *
 * Post type for curriculum
 *
 * @package rockharbor
 * @subpackage rockharbor.libs
 */
class Curriculum extends PostType {

/**
 * Post type options
 *
 * @var array
 */
	public $options = array(
		'name' => 'Curriculum',
		'plural' => 'Curriculum',
		'slug' => 'curriculum',
		'archive' => true,
		'supports' => array(
			'title'
		)
	);

/**
 * Default archive query
 *
 * @var array
 */
	public $archiveQuery = array(
		'numberofposts' => 12,
		'orderby' => 'title',
		'order' => 'ASC'
	);

/**
 * Sets the theme object for use in this class and instantiates the post
 * type and related needs
 *
 * @param RockharborThemeBase $theme
 */
	public function __construct($theme = null) {
		parent::__construct($theme);

		register_taxonomy('curriculum_category', $this->name, array(
			'label' => __('Category', 'rockharbor'),
			'sort' => true,
			'rewrite' => array('slug' => 'curriculum-category', 'with_front' => false)
		));

		register_taxonomy_for_object_type('post_tag', $this->name);
	}

/**
 * Shows the curriculum archive
 *
 * @param array $attrs
 * @return string
 */
	public function shortcode($attrs = array()) {
		global $wpdb, $item;

		$taxes = get_terms('curriculum_category', array(
			'hide_empty' => false
		));

		ob_start();
		foreach ($taxes as $item) {
			get_template_part('content', 'curriculum-category');
		}
		$output = ob_get_clean();

		return $output;
	}

/**
 * Inits extra admin goodies
 */
	public function adminInit() {
		add_meta_box('curriculum_details', 'Curriculum Details', array($this, 'detailsMetaBox'), $this->name, 'side');
		add_meta_box('curriculum_media', 'Media', array($this, 'mediaMetaBox'), $this->name, 'normal');
		remove_meta_box('tagsdiv-curriculum_category', $this->name, 'side');
	}

/**
 * Message details meta box
 */
	public function detailsMetaBox() {
		global $post;
		$taxes = get_terms('curriculum_category', array(
			'hide_empty' => false
		));
		$categories = array();
		foreach ($taxes as $tax) {
			$categories[$tax->slug] = $tax->name;
		}
		$this->theme->set('categories', $categories);
		$data = $this->theme->metaToData($post->ID);
		$selectedCategory = wp_get_post_terms($post->ID, 'curriculum_category');
		$data['tax_input']['curriculum_category'] = $selectedCategory[0]->slug;
		$this->theme->set('data', $data);
		echo $this->theme->render('curriculum'.DS.'curriculum_details_meta_box');
	}

/**
 * Meta box for audio/video
 */
	public function mediaMetaBox() {
		global $post;
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		echo $this->theme->render('curriculum'.DS.'curriculum_media_meta_box');
	}
}