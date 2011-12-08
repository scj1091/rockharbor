<?php
/**
 * PostType
 * 
 * A PostType class is a wrapper for adding a custom post type.
 * 
 * ### Filters
 * `beforeSave($data)` Called before a post is saved. Post data is passed as `$data`
 * 
 * @package rockharbor
 * @subpackage rockharbor.libs
 */
class PostType {

/**
 * Default options (merged with custom options)
 * 
 * @var array
 */
	private $_options = array(
		'name' => null,
		'plural' => null,
		'slug' => null,
		'archive' => false,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
		)
	);

/**
 * Post type options
 * 
 * ### Options:
 * - string `name` Your friendly post type name (i.e., Sermon Message)
 * - string `plural` The pluralized version of the name
 * - string `slug` The slug version of the name (i.e., sermon_message)
 * - boolean `archive` Whether or not to add archive capabilities. Archive post
 * types get a shortcode `[$slug]` that generates a loop right where the shortcode
 * is. Useful for creating a page that shows a loop of custom post types
 * - array `supports` List of features the post type supports (see: `register_post_type()`) 
 * 
 * @var array
 * @see register_post_type()
 */	
	public $options = array();
	
/**
 * The theme class
 * 
 * @var RockharborThemeBase 
 */
	protected $theme = null;

/**
 * Default archive query used in shortcode. Can be overridden by shortcode
 * attributes
 * 
 * @var array
 */
	public $archiveQuery = array();

/**
 * Creates the post type
 * 
 * @param RockharborThemeBase $theme
 */
	public function __construct($theme = null) {
		global $post;
		
		$this->options = array_merge($this->_options, $this->options);
		
		if (!$theme || !$this->options['name'] || !$this->options['slug']) {
			return;
		}
		
		$this->theme = $theme;
		
		if (!$this->options['plural']) {
			// ghetto pluralize
			$this->options['plural'] = $this->options['name'].'s';
		}
		
		register_post_type($this->options['slug'], array(
			'label' => __($this->options['plural'], 'rockharbor'),
			'singular_label' =>__($this->options['name'], 'rockharbor'),
			'description' => __('Add '.$this->options['name'], 'rockharbor'),
			'public' => true,
			'hierarchical' => false,
			'supports' => $this->options['supports'],
			'rewrite' => array('slug' => $this->options['slug'], 'with_front' => false),
			'labels' => array(
				'name' => $this->options['plural'],
				'singular_name' => $this->options['name'],
				'add_new' => 'Add New',
				'add_new_item' =>'Add New '.$this->options['name'],
				'edit_item' => 'Edit '.$this->options['name'],
				'new_item' => 'New '.$this->options['name'],
				'view_item' => 'View '.$this->options['name'],
				'search_items' => 'Search '.$this->options['plural'],
				'not_found' => 'No '.$this->options['plural'].' found.',
				'not_found_in_trash' => 'No '.$this->options['plural'].' found in trash.',
				'parent_item_colon' => 'Parent'.$this->options['name'].':',
				'all_items' => 'All '.$this->options['plural']
			)
		));
		
		// load css
		wp_register_style($this->options['slug'], $theme->info('base_url').'/css/'.$this->options['slug'].'.css');
		wp_enqueue_style($this->options['slug']);
			
		// filters and actions if we're adding or editing this post type
		if ((isset($_GET['post']) && get_post_type($_GET['post']) == $this->options['slug'] )
			|| (isset($_GET['post_type']) && $_GET['post_type'] == $this->options['slug'])
			|| (isset($_POST['post_type']) && $_POST['post_type'] == $this->options['slug'])
			) {
			add_action('admin_init', array($this, 'adminInit'));
			add_filter('wp_insert_post_data', array($this, '_proxyOnSave'), 1, 1);
		}
		
		// add shortcode if this is an archive type
		if ($this->options['archive']) {
			// shortcode to show the archive
			add_shortcode($this->options['slug'], array($this, 'shortcode'));
		}
	}

/**
 * Shortcode to display this post type's posts within a page. #YAWPH 
 * 
 * The shortcode itself runs a loop within the current loop and restores the
 * original when it's done. This allows us to show the content of whatever page
 * shows the archives in addition to the archives.
 * 
 * Passing attributes add to the query.
 * 
 * ### Default query:
 * - `numberofposts` -1 Shows all posts
 * 
 * @param array $attrs Shortcode attributes
 * @return string Post type's archives
 */
	public function shortcode($attrs = array()) {
		global $wp_query;
		$attrs = shortcode_atts($this->archiveQuery, $attr);
		
		$_old_query = $wp_query;
		
		$query = array(
			'post_type' => $this->options['slug']
		);
		$query += $attrs;
		
		$wp_query = new WP_Query($query);
		$wp_query->query($query);
		
		// we have to gobble this up and return it so it doesn't just print everywhere
		ob_start();
		// loop within loop
		while(have_posts()) {
			the_post();
			get_template_part($this->options['slug']);
		}
		$return = ob_get_clean();
		
		// back to the old query
		$wp_query = $_old_query;
		
		return $return;
	}
	
/**
 * Called on admin initiation
 */
	public function adminInit() {}
	
/**
 * Called when a post is saved
 *
 * @param array $data Post data
 * @return array Modified post data from `beforeSave()`
 */
	public function _proxyOnSave($data) {
		if (method_exists($this, 'beforeSave')) {
			return call_user_func(array($this, 'beforeSave'), $data);
		}
	}
	
/**
 * Called to modify the query executed when showing a post type archive page
 * 
 * @param array $query The existing query
 * @return array Modified query
 */
	public function query($query) {}
}