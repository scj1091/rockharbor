<?php
/**
 * Message 
 * 
 * Handles everything needed for message archives
 * 
 * @package rockharbor
 * @subpackage rockharbor.libs
 */
class Message extends PostType {
	
/**
 * Post type options
 * 
 * @var array
 */
	public $options = array(
		'name' => 'Message',
		'plural' => 'Messages',
		'slug' => 'message',
		'archive' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
			'excerpt'
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
		
		register_taxonomy('series', $this->name, array(
			'label' => __('Series', 'rockharbor'),
			'sort' => true,
			'rewrite' => array('slug' => 'series', 'with_front' => false)
		));
		
		register_taxonomy('teacher', $this->name, array(
			'label' => __('Teachers', 'rockharbor'),
			'sort' => true,
			'rewrite' => array('slug' => 'teachers', 'with_front' => false)
		));
		
		register_taxonomy_for_object_type('post_tag', $this->name);
	}

/**
 * Called after a post is saved. Adds enclosures
 * 
 * @param type $data Post data
 * @param type $postId Post id
 */
	function afterSave($data, $postId) {
		do_enclose($_POST['meta']['video_url'], $postId);
		do_enclose($_POST['meta']['audio_url'], $postId);
	}

/**
 * Inits extra admin goodies
 */
	public function adminInit() {
		add_meta_box('message_details', 'Message Details', array($this, 'detailsMetaBox'), $this->name, 'side');
		add_meta_box('media_details', 'Media', array($this, 'mediaMetaBox'), $this->name, 'normal');
		remove_meta_box('tagsdiv-series', $this->name, 'side');
	}

/**
 * Message details meta box
 */
	public function detailsMetaBox() {
		global $post;
		$taxes = get_terms('series', array(
			'hide_empty' => false
		));
		$series = array();
		foreach ($taxes as $tax) {
			$series[$tax->term_id] = $tax->name;
		}
		$this->theme->set('series', $series);
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		echo $this->theme->render('message_details_meta_box');
	}

/**
 * Meta box for audio/video
 */	
	public function mediaMetaBox() {
		global $post;
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		echo $this->theme->render('message_media_meta_box');
	}
}