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
 * Overrides `shortcode()` method to look up and group by categories instead
 * of messages
 * 
 * @param array $attrs Shortcode attributes
 * @return string Post type's archives
 */
	public function shortcode($attrs = array()) {
		global $wp_query, $wp_rewrite, $wpdb, $item;
		
		$_old_query = $wp_query;
		
		$page = (get_query_var('paged')) ? get_query_var('paged')-1 : 0;
		// if on the first page, show the first message in addition to other series
		$termsperpage = $page == 0 ? 17 : 16;
		$offset = $page*$termsperpage;
		
		// get all series and include their first post date, last post date, 
		// total count and other relevant information
		$sql = "SELECT SQL_CALC_FOUND_ROWS
		
				# fields
				`$wpdb->terms`.`term_id`,
				`$wpdb->terms`.`name`,
				`$wpdb->terms`.`slug`,
				MIN(`$wpdb->posts`.`post_date`) AS series_start_date,
				MAX(`$wpdb->posts`.`post_date`) AS series_end_date,
				COUNT(`$wpdb->posts`.`ID`) AS series_message_count
				
				FROM `$wpdb->term_taxonomy` 
				
				# join term relationship
				LEFT JOIN `$wpdb->term_relationships` ON (`$wpdb->term_relationships`.`term_taxonomy_id` = `$wpdb->term_taxonomy`.`term_taxonomy_id`)
				
				# bring in post info for counts
				LEFT JOIN `$wpdb->posts` ON (`$wpdb->posts`.`ID` = `$wpdb->term_relationships`.`object_id`)
				
				# bring in series info
				LEFT JOIN `$wpdb->terms` ON (`$wpdb->terms`.`term_id` = `$wpdb->term_taxonomy`.`term_id`)
				
				WHERE `$wpdb->term_taxonomy`.`taxonomy` = 'series'
				AND `$wpdb->posts`.`post_status` = 'publish'
				
				GROUP BY `$wpdb->terms`.`term_id`
				
				ORDER BY series_end_date DESC
				
				LIMIT $offset,$termsperpage
				;";
		$series = $wpdb->get_results($sql);
		$count = $wpdb->get_results('SELECT FOUND_ROWS();');
		$count = $count[0]->{'FOUND_ROWS()'};
		
		ob_start();
		foreach ($series as $num => $seriesItem) {
			$last = get_posts(array(
				'series' => $seriesItem->slug,
				'post_type' => $this->options['slug'],
				'orderby' => 'post_date',
				'order' => 'DESC',
				'numberposts' => 1
			));
			if (!empty($last)) {
				$seriesItem->last = $last[0];
			}
			
			$item = $seriesItem;
			
			if ($num == 0 && $page == 0) {
				get_template_part('content', 'series-first');
				echo '<div class="series-collection">';
			} else {
				if ($num == 0) {
					echo '<div class="series-collection">';
				}
				get_template_part('content', 'series');
			}
		}
		echo '</div>';
		
		$this->theme->set('wp_rewrite', $wp_rewrite);
		$wp_query->max_num_pages = ceil($count / $termsperpage);
		$this->theme->set('wp_query', $wp_query);
		$return = ob_get_clean();
		
		// back to the old query
		$wp_query = $_old_query;
		
		return $return;
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
			$series[$tax->slug] = $tax->name;
		}
		$this->theme->set('series', $series);
		$data = $this->theme->metaToData($post->ID);
		$selectedSeries = wp_get_post_terms($post->ID, 'series');
		$data['tax_input']['series'] = $selectedSeries[0]->slug;
		$this->theme->set('data', $data);
		echo $this->theme->render('message'.DS.'message_details_meta_box');
	}

/**
 * Meta box for audio/video
 */	
	public function mediaMetaBox() {
		global $post;
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		echo $this->theme->render('message'.DS.'message_media_meta_box');
	}
}