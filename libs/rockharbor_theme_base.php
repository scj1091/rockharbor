<?php
/**
 * Includes
 */
require_once 'basics.php';
require_once 'html_helper.php';
require_once 'shortcodes.php';
require_once 'post_type.php';

/**
 * ROCKHARBOR Theme base class. All child themes should extend this base class
 * to make use of the overall site functionality
 * 
 * @package rockharbor
 */
class RockharborThemeBase {
	
/**
 * List of options for this theme (all required by subsites)
 * 
 * ### Options
 * - `$slug` The slug for this theme (no spaces, special chars, etc)
 * - `$short_name` The short name for this campus, i.e., without RH preceding
 * - `$supports` An array of supported features for this particular site. See
 * the README for more information about the features
 * 
 * @var array
 */
	protected $themeOptions = array(
		'slug' => 'rockharbor',
		'short_name' => 'Central'
	);

/**
 * Post types to disable commenting by default
 * 
 * @var array
 */
	public $disableComments = array(
		'page'
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
 * Array of actions that are allowed to be called via POSTing the `$action` var
 * 
 * @var array
 */
	public $allowedActions = array('email');
	
/**
 * Array of messages
 * 
 * @var array 
 */
	public $messages = array();
	
/**
 * List of possible features for child themes
 * 
 * @var array 
 */
	public $features = array(
		'staff' => 'Staff',
		'message' => 'Message'
	);

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
		
		add_action('init', array($this, 'addFeatures'));
		
		if (is_admin()) {
			require_once $this->basePath . DS . 'libs' . DS . 'admin.php';
			$this->Admin = new Admin($this);
			require_once $this->basePath . DS . 'libs' . DS . 'roles.php';
			$this->Roles = new Roles($this);
		} else {
			$this->setupAssets();
		}
		
		// change rss feed to point to feedburner link
		add_filter('feed_link', array($this, 'updateRssLink'), 10, 2);
		
		add_action('after_setup_theme', array($this, 'after'));
		if ($this->isChildTheme()) {
			// #YAWPH
			// we're in a child theme, so we don't want add filters/actions for 
			// the base class, otherwise we'll end up with duplicate filters/actions 
			return;
		}
		
		// theme settings
		add_filter('wp_get_nav_menu_items', array($this, 'getNavMenu'));
		add_action('widgets_init', array($this, 'registerSidebars'));
		
		// forced gallery settings
		add_filter('use_default_gallery_style', array($this, 'removeCss'));
		add_filter('img_caption_shortcode', array($this, 'wrapAttachment'), 1, 3);
		
		// other
		add_filter('pre_get_posts', array($this, 'rss'));
		add_filter('pre_get_posts', array($this, 'aggregateArchives'));
		
		// social comment plugin css
		if (!defined('SOCIAL_COMMENTS_CSS')) {
			define('SOCIAL_COMMENTS_CSS', $this->baseUrl.'/css/comments.css');
		}
		
		// start session
		if (!session_id()) {
			session_start();
		}
	}

/**
 * Registers and queues assets
 */
	function setupAssets() {
		// register assets
		$base = $this->info('base_url');
		wp_deregister_script('jquery'); // deregister WP's version
		wp_register_script('jquery', "$base/js/jquery-1.7.2.min.js");
		wp_register_script('lightbox', "$base/js/jquery.lightbox.min.js");
		wp_register_script('media', "$base/js/mediaelement-and-player.min.js");
		wp_register_style('reset', "$base/css/reset.css");
		wp_register_style('fonts', "$base/css/fonts.css");
		wp_register_style('lightbox', "$base/css/lightbox.css");
		wp_deregister_style('media');
		wp_register_style('media', "$base/css/mediaelementplayer.css");
		wp_register_style('base', "$base/style.css");
		$base = $this->info('url');
		wp_register_style('child_base', "$base/style.css");
		
		// queue them
		wp_enqueue_style('reset');
		wp_enqueue_style('fonts');
		wp_enqueue_style('lightbox');
		wp_enqueue_style('media');
		wp_enqueue_style('base');

		wp_enqueue_script('jquery');
		wp_enqueue_script('lightbox');
		wp_enqueue_script('media');
		
		// dequeue stuff we don't need
		wp_dequeue_style('thickbox');
		
		// conditional assets
		if (is_singular() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}
		if ($this->isChildTheme()) {
			wp_enqueue_style('child_base');
		}
	}
	
/**
 * Adds features if they are supported by the child theme
 * 
 * If you create custom post types outside of the `init` action, WordPress seems
 * to erase the list of taxonomies you told it to include. It will include all
 * ones specifically assigned to the post type, but skip existing ones. #YAWPH?
 * 
 * @return void 
 */
	function addFeatures() {
		foreach ($this->features as $postType => $className) {
			if ($this->supports($postType)) {
				require_once LIBS . DS . str_replace('-', '_', $postType).'.php';
				$this->{$className} = new $className($this);
			}
		}
	}

/**
 * Changes the rss link in the header to something actually useful
 * 
 * @param string $output Current output
 * @param string $type The feed type
 * @return string Modified feed link
 */
	public function updateRssLink($output, $type) {
		$feedburner = $this->options('feedburner_main');
		if (!empty($feedburner) && stripos($output, 'comments') === false) {
			$output = 'http://feeds.feedburner.com/'.$feedburner;
		}
		return $output;
	}

/**
 * YAWPH that allows us to pull the aggregated posts and use our own template
 * to customize the rss feed (which basically just switches the blogs so posts
 * from other sites are linked correctly)
 * 
 * @param WP_Query $query Query passed by WP
 * @return mixed Auto-echoes template or returns original query
 * @see README
 */
	function rss($query) {
		if (!$query->is_feed) {
			return $query;
		}
		$this->aggregatePosts(get_option('posts_per_rss'));
		load_template(TEMPLATEPATH . DS . 'rss.php');
		die(/*in a fire*/);
	}
	
/**
 * Pulls events from CORE
 * 
 * ### Options:
 * - `$ministry_ids` Comma-delimited list of ministry ids to pull
 * - `$involvement_ids` Comma-delimited list of involvement ids to pull
 * 
 * @param integer $campus_ids The Campus id(s) to pull
 * @param integer $ministry_ids The Ministry id(s) to pull
 * @param integer $involvement_ids The Involvement id(s) to pull
 * @return array Normalized event array
 */
	public function fetchCoreFeed($campus_ids = null, $ministry_ids = null, $involvement_ids = null) {
		$url = 'https://core.rockharbor.org/dates/calendar';
		if (!empty($campus_ids)) {
			$url .= '/Campus:'.$campus_ids;
		}
		if (!empty($ministry_ids)) {
			$url .= '/Ministry:'.$ministry_ids;
		}
		if (!empty($involvement_ids)) {
			$url .= '/Involvement:'.$involvement_ids;
		}
		$url .= '/full.json?start='.strtotime('now').'&end='.strtotime('+60 days');
		$response = wp_remote_get($url, array('sslverify' => false));
		if (is_wp_error($response)) {
			$response = array(
				'body' => json_encode(array())
			);
		}
		return json_decode($response['body'], true);
	}

/**
 * Constructs and sends an email to a predefined option. Passing `$_POST['type']`
 * will look up an option `$_POST['type'].'_email'` to email to. If none is found
 * the function will exit.
 * 
 * @return mixed `false` on failure, an array of what was sent on success. Error
 *	messages are stored in `$errors`
 */
	public function email() {
		if (!$this->Html->validateCaptcha()) {
			$this->messages[] = __('You entered in the wrong CAPTCHA phrase.', 'rockharbor');
			return false;
		}
		if (!isset($_POST['type'])) {
			$_POST['type'] = 'story';
		}
		$to = $this->options($_POST['type'].'_email');
		if (empty($to)) {
			$this->messages[] = 'To address not defined in CMS.';
			return false;
		}
		$from = $this->info('email');
		$subject = '['.$this->name.'] '.ucfirst($_POST['type']).' Email';
		$body = $this->Html->tag('h1', ucfirst($_POST['type']).' Email');
		$body .= '<table>';
		unset($_POST['type'], $_POST['action']);
		foreach ($_POST as $post => $value) {
			$body .= $this->Html->tag('tr',
				$this->Html->tag('td', $this->Html->tag('strong', $post))
				. $this->Html->tag('td', '&nbsp;&nbsp;')
				. $this->Html->tag('td', $value)
			);
		}
		$body .= '</table>';
		$headers = array(
			'From' => $from,
			'X-Mailer' => 'PHP/' . phpversion(),
			'Content-type' => 'text/html; charset=utf-8'
		);
		foreach ($headers as $name => &$value) {
			$value = $name.': '.$value;
		}
		$headers = implode("\r\n", $headers);
		
		if ($this->_mail($to, $subject, $body, $headers)) {
			$this->messages[] = 'Thanks for your message!';
			return compact('to', 'subject', 'body', 'headers');
		}
		$this->messages[] = 'Failed sending email.';
		return false;
	}

/**
 * Sends an email
 * 
 * @param string $to
 * @param string $subject
 * @param string $body
 * @param string $headers 
 */
	protected function _mail($to, $subject, $body, $headers) {
		return mail($to, $subject, $body, $headers);
	}

/**
 * Brings in all of the post types when showing an archive page
 * 
 * @param WP_Query $query
 * @return WP_Query
 */
	public function aggregateArchives($query) {
		if (is_archive()) {
			$query->set('post_type', get_post_types());
		}
		return $query;
	}

/**
 * Aggregates posts from all sites that have the meta 'cross_post_<THISBLOGID>'
 * and includes them in The Loop with this blog's posts
 */
	public function aggregatePosts($count = null) {
		// save page count before we overwrite WP_Query
		$page = get_query_var('page');
		unset($GLOBALS['wp_query']);
		$GLOBALS['wp_query'] =& new WP_Query();

		global $wpdb, $wp_query, $table_prefix;

		$blogs = $this->getBlogs();

		$fields = '`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_name`, `guid`, `post_type`, `blog_id`, `comment_status`, `ping_status`';
		$query = "SELECT SQL_CALC_FOUND_ROWS $fields FROM (";
		// primary table - this blog
		$query .= "SELECT DISTINCT $fields FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON (post_id = ID) LEFT JOIN $wpdb->blogs ON (blog_id = $this->id)";
		$query .= "WHERE post_type = 'post' AND post_status = 'publish'";
		foreach ($blogs as $blog) {
			if ($blog['blog_id'] == $this->id) {
				continue;
			}
			// other blogs merged into the query
			$query .= " UNION DISTINCT (SELECT $fields FROM";
			$wpdb->set_blog_id($blog['blog_id']);
			$query .= " $wpdb->posts LEFT JOIN $wpdb->postmeta ON (post_id = ID AND meta_key = 'cross_post_$this->id')";
			$query .= " LEFT JOIN $wpdb->blogs ON (blog_id = {$blog['blog_id']})";
			$query .= " WHERE meta_value = 1)";
		}

		// conditions affecting all queries
		$query .= ") AS q WHERE post_type = 'post' AND post_status = 'publish'";
		$query .= " ORDER BY post_date DESC";
		$offset = ($page ? $page-1 : 0) * get_option('posts_per_page');
		if (!$count) {
			$count = get_option('posts_per_page');
		}
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
 * After callback. Called after theme setup
 */
	public function after() {
		add_theme_support('post-thumbnails');
		add_theme_support('automatic-feed-links');
		load_theme_textdomain('rockharbor', $this->basePath.'/languages');
		
		register_nav_menus(array(
			'main' => __('Main Navigation', 'rockharbor'),
			'footer' => __('Footer Navigation', 'rockharbor'),
			'global' => __('Global Navigation', 'rockharbor')
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
			'short_name' => $this->themeOptions['short_name'],
			'slug' => $this->themeOptions['slug'],
			'id' => $this->id,
			'email' => get_bloginfo('admin_email')
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
		return get_parent_class($this) !== false;
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
	public function render($view) {
		global $theme;
		extract($this->_vars);
		$file = $this->themePath.DS.'elements'.DS.$view.'.tpl';
		if (!file_exists($file)) {
			$file = str_replace($this->themePath, $this->basePath, $file);
		}
		ob_start();
		include $file;
		$out = ob_get_clean();
		return $out;
	}

/**
 * Gets a file from an enclosure
 * 
 * @param string $type The type of enclosure to get (audio or video)
 * @param integer $postId The post id. Default is current post
 * @return string
 */
	public function getEnclosure($type = 'video', $postId = null) {
		global $post;
		if (empty($postId)) {
			$postId = $post->ID;
		}
		$enclosure = get_post_meta($postId, 'enclosure');
		$file = null;
		if (!empty($enclosure)) {
			foreach ($enclosure as $enclosed) {
				$enclosedSplit = explode("\n", $enclosed);
				if (!empty($enclosedSplit[2]) && strpos($enclosedSplit[2], "$type/") !== false) {
					$file = $enclosedSplit[0];
					break;
				}
			}
		}
		$file = str_replace(array("\r\n", "\r", "\n"), '', $file);
		return $file;
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
 * Converts garbagy output from get_post_custom to a useable data array
 * 
 * @param integer $postId The post to get meta from
 * @return array
 */
	public function metaToData($postId) {
		$meta = get_post_custom($postId);
		$data = array();
		foreach ($meta as $name => $value) {
			$data[$name] = maybe_unserialize($value[0]);
		}
		return $data;
	}

/**
 * Converts user data to a data array for the HtmlHelper
 * 
 * @param int $userId The user id
 * @return array
 */
	public function userMetaToData($userId) {
		$meta = get_userdata($userId);
		$data = array();
		foreach ($meta as $name => $value) {
			$data[$name] = $value;
		}
		return $data;
	}

/**
 * Returns a list of blogs in this network
 * 
 * @return array 
 */
	public function getBlogs() {
		global $wpdb;
		return $wpdb->get_results("SELECT * FROM $wpdb->blogs WHERE archived = '0' AND deleted = '0'", ARRAY_A);
	}
	
/**
 * Checks if this theme or childtheme supports a feature
 * 
 * @param string $feature
 * @return boolean
 */
	public function supports($feature = null) {
		if (!isset($this->themeOptions['supports'])) {
			return false;
		}
		return in_array($feature, $this->themeOptions['supports']);
	}
}
