<?php
/**
 * Includes
 */
require_once 'html_helper.php';
require_once 'shortcodes.php';

/**
 * ROCKHARBOR Theme base class. All child themes should extend this base class
 * to make use of the overall site functionality
 * 
 * @package rockharbor
 */
class RockharborThemeBase {
	
/**
 * List of options for this theme
 * 
 * @var array
 */
	protected $themeOptions = array(
		'slug' => 'rockharbor'
	);

/**
 * Directory path to current theme
 * 
 * @var string
 */
	protected $themePath = null;
	
/**
 * Uri path to current theme
 * 
 * @var string
 */
	protected $themeUrl = null;

/**
 * Directory path to base theme
 * 
 * @var string
 */
	protected $basePath = null;
	
/**
 * Uri path to base theme
 * 
 * @var string
 */
	protected $baseUrl = null;	
	
/**
 * Blog title
 * 
 * @var string
 */
	protected $name = null;
	
/**
 * Vars set for the next render
 * 
 * @var array
 */
	protected $_vars = array();
	
/**
 * The blog id
 * 
 * @var integer
 */
	protected $id = null;

/**
 * Sets up the theme
 */
	public function __construct() {
		global $wpdb;
		
		$this->themePath = rtrim(get_stylesheet_directory(), DS);
		$this->themeUrl = rtrim(get_stylesheet_directory_uri(), '/');
		$this->basePath = rtrim(get_template_directory(), DS);
		$this->baseUrl = rtrim(get_template_directory_uri(), '/');
		$this->name = get_bloginfo('name');
		$this->id = $wpdb->blogid;
		
		$this->Html = new HtmlHelper($this);
		$this->Shortcodes = new Shortcodes($this);
		
		// theme settings
		add_filter('wp_get_nav_menu_items', array($this, 'getNavMenu'));
		add_action('widgets_init', array($this, 'registerSidebars'));
		add_action('after_setup_theme', array($this, 'after'));
		
		// forced gallery settings
		add_filter('use_default_gallery_style', array($this, 'removeCss'));
		add_filter('img_caption_shortcode', array($this, 'wrapAttachment'), 1, 3);
		
		// other
		add_filter('the_content', array($this, 'content'));
		add_filter('save_post', array($this, 'onSave'), 1, 2);
		
		// make images link to their file by default
		update_option('image_default_link_type', 'file');
		
		// social comment plugin css
		if (!defined('SOCIAL_COMMENTS_CSS')) {
			define('SOCIAL_COMMENTS_CSS', $this->themeUrl.'/css/comments.css');
		}
		
		// admin section
		add_action('admin_init', array($this, 'admin_init'));
		add_action('save_post', array($this, 'saveMeta'));
		add_action('admin_menu', array($this, 'admin_menu'));
	}

/**
 * Aggregates posts from all sites that have the meta 'cross_post_<THISBLOGID>'
 * and includes them in The Loop with this blog's posts
 */
	function aggregatePosts() {
		// save page count before we overwrite WP_Query
		$page = get_query_var('page');
		unset($GLOBALS['wp_query']);
		$GLOBALS['wp_query'] =& new WP_Query();
		
		global $wpdb, $wp_query, $table_prefix;
				
		$blogs = $wpdb->get_results("SELECT * FROM $wpdb->blogs WHERE archived = '0' AND deleted = '0'", ARRAY_A);
		
		$group = "GROUP BY ID";
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM (";
		// primary table - this blog
		$query .= "SELECT * FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON (post_id = ID) LEFT JOIN $wpdb->blogs ON (blog_id = $this->id) $group";
		foreach ($blogs as $blog) {
			if ($blog['blog_id'] == $this->id) {
				continue;
			}
			// other blogs merged into the query
			$query .= " UNION (SELECT * FROM";
			$wpdb->set_blog_id($blog['blog_id']);
			$query .= " $wpdb->posts LEFT JOIN $wpdb->postmeta ON (post_id = ID AND meta_key = 'cross_post_$this->id')";
			$query .= " LEFT JOIN $wpdb->blogs ON (blog_id = {$blog['blog_id']})";
			$query .= " WHERE meta_value = 1 $group)";
		}
		
		// conditions affecting all queries
		$query .= ") AS q WHERE post_type = 'post' AND post_status = 'publish'";
		$query .= " ORDER BY post_date DESC";
		$offset = ($page ? $page-1 : 0) * get_option('posts_per_page');
		$count = get_option('posts_per_page');
		$query .= " LIMIT $offset, $count";
		$wpdb->set_blog_id($this->id);
		
		$wp_query->posts = $wpdb->get_results($query);
		// for pagination
		$wp_query->query_vars['paged'] = $page;
		$wp_query->post_count = count($wp_query->posts);
		$wp_query->found_posts = $wpdb->get_var('SELECT FOUND_ROWS()');
		$wp_query->max_num_pages = ceil($wp_query->found_posts / get_option('posts_per_page'));
	}

/**
 * Saves extra meta data
 * 
 * All extra custom meta data to be saved should be prefixed with 'meta' in
 * order to be saved by this function
 */
	public function saveMeta() {
		global $post;
		
		if (isset($_POST['meta'])) {
			foreach ($_POST['meta'] as $name => $value) {
				update_post_meta($post->ID, $name, $value);
			}
		}
	}

/**
 * Renders the cross_post meta box on the edit posts page
 */
	public function crossPostMetaBox() {
		global $wpdb, $post;
		$blogs = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->blogs WHERE archived = '0' AND deleted = '0'"), ARRAY_A);
		if ($this->isChildTheme()) {
			// main blog only
			$blogs = array($blogs[0]);
		} else {
			// any blog, excluding self
			unset($blogs[0]);
		}
		$this->set('data', $this->metaToData($post->ID));
		$this->set('blogs', $blogs);
		echo $this->render('cross_post');
	}
	
/**
 * Inits plugin options to white list our options
 */
	public function admin_init() {
		register_setting($this->info('slug').'_options', $this->info('slug').'_options');
		// add meta boxes for cross-posting
		add_meta_box('cross-post', 'Cross-site Posting', array($this, 'crossPostMetaBox'), 'post', 'side');
	}

/**
 * Loads up the menu page
 */
	public function admin_menu() {
		add_theme_page(__('Theme Options', 'rockharbor'), __('Theme Options', 'rockharbor'), 'edit_theme_options', 'theme_options', array($this, 'admin'));
	}

/**
 * Renders the theme options panel
 */
	public function admin() {
		$out = $this->render('theme_options');
		echo $out;
	}
	
/**
 * All content that passes through `the_content()` filters through this function
 * 
 * @param string $content Content
 * @return string The content
 */
	public function content($content) {
		if (empty($content)) {
			return $content;
		}
		// remove all static width/height from images (let css do the work)
		libxml_use_internal_errors(true);
		$doc = DOMDocument::loadHTML($content);
		foreach($doc->getElementsByTagName('img') as $image){
			foreach(array('width', 'height') as $attribute_to_remove){
				if($image->hasAttribute($attribute_to_remove)){
					$image->removeAttribute($attribute_to_remove);
				}
			}
		}
		$content = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $doc->saveHTML()));
		
		return $content;
	}

/**
 * After callback. Called after theme setup
 */
	public function after() {
		add_theme_support('post-thumbnails');
		add_theme_support('automatic-feed-links');
		load_theme_textdomain('rockharbor', $this->basePath.'/languages');
		
		register_nav_menus(array(
			'main' => __('Main Navigation', 'rockharbor'),
			'footer' => __('Footer Navigation', 'rockharbor')
		));
	}
	
/**
 * Returns all theme info
 * 
 * @return array
 */
	public function info($var = null) {
		$vars = array(
			'path' => $this->themePath,
			'url' => $this->themeUrl,
			'base_path' => $this->basePath,
			'base_url' => $this->baseUrl,
			'name' => $this->name,
			'slug' => $this->themeOptions['slug'],
			'id' => $this->id
		);
		if ($var === null || !isset($vars[$var])) {
			return $vars;
		}
		return $vars[$var];
	}
	
/**
 * Checks if this is a child theme
 * 
 * @return boolean
 */
	public function isChildTheme() {
		return $this->themePath !== $this->basePath;
	}
	
/**
 * Sets a var to use when the view is loaded
 * 
 * @param string $var The var name
 * @param mixed $value Value
 */	
	public function set($var, $value = null) {
		if (is_array($var)) {
			foreach ($var as $name => $val) {
				$this->_vars[$name] = $val;
			}
		} else {
			$this->_vars[$var] = $value;
		}
	}

/**
 * Adds variables to the view found in `<template_base>/elements` and returns it. 
 * It looks for a child version of the view first. If it can't find it, it looks
 * for the parent version.
 *
 * @param string $view The view name
 * @return string Rendered view
 */	
	public function render($view, $emptyVars = true) {
		extract($this->_vars);
		$theme = $this;
		$file = $this->themePath.DS.'elements'.DS.$view.'.tpl';
		if (!file_exists($file)) {
			$file = str_replace($this->themePath, $this->basePath, $file);
		}
		ob_start();
		include $file;
		$out = ob_get_clean();
		if ($emptyVars) {
			$this->_vars = array();
		}
		return $out;
	}
	
/**
 * Called when generating a menu via `wp_nav_menu`
 *
 * Dynamically adds children to the menu based on the main menu items' children.
 * Only top level menu items can be defined, all others will be removed.
 *
 * @param array $items Items from `wp_get_nav_menu_items`
 * @param array $menu Menu object
 * @param array $args Args used in getting menu items
 * @return array
 * @see `wp_get_nav_menu_items`
 */
	public function getNavMenu($items = array(), $menu = null, $args = array()) {
		if (is_admin()) {
			// don't mess with the backend
			return $items;
		}
		$subMenus = array();
		foreach ($items as $index => $item) {
			if ($item->menu_item_parent) {
				unset($items[$index]);
				continue;
			}
			$children = get_children(array(
				'post_parent' => $item->object_id,
				'post_status' => 'publish',
				'post_type' => 'page',
				'orderby' => 'menu_order',
				'order' => 'ASC'
			));
			foreach ($children as &$child) {
				$child = wp_setup_nav_menu_item($child);
				$child->menu_item_parent = $item->ID;
				$child->post_type = 'nav_menu_item';
				$subMenus[] = $child;
			}
		}
		$items = array_merge($items, $subMenus);
		// restructure the menu based on the new items
		foreach ($items as $index => &$item) {
			$item->menu_order = $index;
		}
		return $items;
	}

/**
 * Registers sidebar/widget/whatevertheyare areas
 */
	public function registerSidebars() {
		register_sidebar(array(
			'name' => __('Left Widgets', 'rockharbor'),
			'id' => 'sidebar-subnav',
			'description' => __('Additional items for after the sub-nav', 'rockharbor'),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));

		register_sidebar(array(
			'name' => __('Right Widgets', 'rockharbor'),
			'id' => 'sidebar-complementary',
			'description' => __('Additional items for after the right-hand navigation', 'rockharbor'),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));
	}

/**
 * Overrides the default WordPress functionality that adds a fixed width to the
 * div that wraps the image
 * 
 * @param string $randomParam Always ''
 * @param array $attr Attribute array
 * @param string $content The image
 * @return string
 * @see img_caption_shortcode()
 */
	public function wrapAttachment($randomParam, $attr, $content = null) {
		$_defaults = array(
			'id'	=> uniqid('attachment_'),
			'align'	=> 'alignnone',
			'caption' => ''
		);
		$attr = array_merge($_defaults, (array)$attr);
		return $this->Html->tag('div', do_shortcode($content), array(
			'align' => esc_attr($attr['align']),
			'id' => $attr['id']
		));
	}

/**
 * Filter to make sure and exclude built-in WP CSS when styling galleries
 * 
 * @return boolean False
 */
	public function removeCss() {
		return false;
	}

/**
 * Gets/sets theme options. If `$var` is false, acts as a getter. If `$var` is
 * null, it will delete the option
 * 
 * @param string $option An option to get. If `null`, all options are returned
 * @param mixed $var The value to set.
 * @return mixed
 */
	public function options($option = null, $var = false) {
		$options = get_option($this->info('slug').'_options');
		
		if ($options === false) {
			$options = array();
		}
		
		if (!is_null($option) && $var !== false) {
			$options[$option] = $var;
			update_option($this->info('slug').'_options', $options);
		}
		
		if (!is_null($option) && is_null($var)) {
			unset($options[$option]);
			update_option($this->info('slug').'_options', $options);
		}

		if (!is_null($option)) {
			return isset($options[$option]) ? $options[$option] : null;
		}
		return $options;
	}
	
/**
 * Called when a post is saved. Forces auto-generation of enclosure meta keys
 *
 * @param integer $post_id Post id
 * @param StdClass $post The post
 */
	function onSave($post_id, $post) {
		do_enclose($post->post_content, $post_id);
	}
	
/**
 * Converts garbagy output from get_post_custom to a useable data array
 * 
 * @param integer $postId The post to get meta from
 * @return array
 */
	protected function metaToData($postId) {
		$meta = get_post_custom($postId);
		$data = array();
		foreach ($meta as $name => $value) {
			$data[$name] = maybe_unserialize($value[0]);
		}
		return $data;
	}
}