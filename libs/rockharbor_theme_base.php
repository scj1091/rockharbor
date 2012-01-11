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
	protected $disableComments = array(
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
		
		// tagline is the same for all - vision statement
		update_option('blogdescription', 'We are a church of communities living out the gospel together.');
		update_option('blogname', 'RH '.$this->info('short_name'));
		update_option('image_default_link_type', 'file');
		// change rss feed to point to feedburner link
		add_filter('feed_link', array($this, 'updateRssLink'), 10, 2);
		
		add_action('admin_init', array($this, 'adminInit'));
		add_filter('default_content', array($this, 'setDefaultComments'), 1, 2);
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
		
		// social comment plugin css
		if (!defined('SOCIAL_COMMENTS_CSS')) {
			define('SOCIAL_COMMENTS_CSS', $this->baseUrl.'/css/comments.css');
		}
		
		// admin section
		add_filter('save_post', array($this, 'onSave'), 1, 2);
		add_action('admin_menu', array($this, 'adminMenu'));
		add_filter('wp_delete_file', array($this, 'deleteS3File'));
		add_filter('wp_update_attachment_metadata', array($this, 'deleteLocalFile'), 10, 2);
		
		// start session
		if (!session_id()) {
			session_start();
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
				require_once str_replace('-', '_', $postType).'.php';
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
 * Pulls events from the public calendar and normalizes it into a standard array
 * that the element can read
 * 
 * @param integer $id The ministry id
 * @return array Normalized event array
 */
	public function getCoreHomepageEvents($id = null) {
		if (!$id) {
			$id = 0;
		}
		$response = wp_remote_get("https://core.rockharbor.org/homes/publicCalendar/$id/0/0/json", array('sslverify' => false));
		if (is_wp_error($response)) {
			$response = array(
				'body' => json_encode(array())
			);
		}
		$items = json_decode($response['body'], true);
		
		$events = array();
		foreach ($items as $item) {
			$events[] = array(
				'Event' => array(
					'id' => $item['Events']['event_id'],
					'name' => $item['Events']['event_name'],
					'type' => 'event'
				),
				'Date' => array(
					array(
						'start_date' => $item['EventDates']['start_date'],
						'end_date' => $item['EventDates']['end_date']
					)
				)
			);
		}
		return $events;
	}

/**
 * Gets all involvement for ministries. Can include specific events as well.
 * Normalizes the array to match the return that `getCoreHomepageEvents()`
 * returns
 * 
 * @param string $ids Comma delimited list of ministry IDs to pull involvement from
 * @param string $event_ids Comma delimited list of events to include as well
 * @param string $group_ids Comma delimited list of groups to include as well
 * @param string $team_ids Comma delimited list of teams to include as well
 * @return array 
 */
	public function getCoreMinistryEvents($ids = null, $event_ids = null, $group_ids = null, $team_ids = null) {
		if (empty($ids)) {
			$ids = 0;
		}
		if (empty($event_ids)) {
			$event_ids = 0;
		}
		if (empty($group_ids)) {
			$group_ids = 0;
		}
		if (empty($team_ids)) {
			$team_ids = 0;
		}
		$response = wp_remote_get("https://core.rockharbor.org/ministries/involvement/$ids/$event_ids/$group_ids/$team_ids/json", array('sslverify' => false));
		if (is_wp_error($response)) {
			$response = array(
				'body' => json_encode(array(
					'InvolvementEvents' => array(),
					'InvolvmentTeams' => array(),
					'InvolvmentGroups' => array()
				))
			);
		}
		$items = json_decode($response['body'], true);
		
		$events = array();
		$days = array(
			'' => 'sunday',
			'SUN' => 'sunday',
			'MON' => 'monday',
			'TUE' => 'tuesday',
			'WED' => 'wednesday',
			'THU' => 'thursday',
			'FRI' => 'friday',
			'SAT' => 'saturday'
		);
		foreach ($items['InvolvementEvents'] as $item) {
			$events[] = array(
				'Event' => array(
					'id' => $item['Events']['event_id'],
					'name' => $item['Events']['event_name']	,
					'type' => 'event'				
				),
				'Date' => array(
					array(
						'start_date' => $item['EventDates']['start_date']
					)
				)
			);
		}
		foreach ($items['InvolvementTeams'] as $item) {
			$events[] = array(
				'Event' => array(
					'id' => $item['Teams']['team_id'],
					'name' => $item['Teams']['team_name'],
					'type' => 'team'					
				),
				'Date' => array(
					array(
						'start_date' => date('Y-m-d', strtotime('next '.$days[$item['Teams']['meetingDay']]))
					)
				)
			);
		}
		foreach ($items['InvolvementGroups'] as $item) {
			$events[] = array(
				'Event' => array(
					'id' => $item['Groups']['group_id'],
					'name' => $item['Groups']['group_name'],
					'type' => 'group'
				),
				'Date' => array(
					array(
						'start_date' => date('Y-m-d', strtotime('next '.$days[$item['Groups']['meetingDay']]))
					)
				)
			);
		}
		return $events;
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
		$group = "GROUP BY ID";
		$query = "SELECT SQL_CALC_FOUND_ROWS $fields FROM (";
		// primary table - this blog
		$query .= "SELECT $fields FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON (post_id = ID) LEFT JOIN $wpdb->blogs ON (blog_id = $this->id) $group";
		foreach ($blogs as $blog) {
			if ($blog['blog_id'] == $this->id) {
				continue;
			}
			// other blogs merged into the query
			$query .= " UNION (SELECT $fields FROM";
			$wpdb->set_blog_id($blog['blog_id']);
			$query .= " $wpdb->posts LEFT JOIN $wpdb->postmeta ON (post_id = ID AND meta_key = 'cross_post_$this->id')";
			$query .= " LEFT JOIN $wpdb->blogs ON (blog_id = {$blog['blog_id']})";
			$query .= " WHERE meta_value = 1 $group)";
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
		global $post;
		$blogs = $this->getBlogs();
		if ($this->id != BLOG_ID_CURRENT_SITE) {
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
 * Renders the meta box for core events on pages
 */
	public function coreMetaBox() {
		global $post;
		$this->set('data', $this->metaToData($post->ID));
		echo $this->render('core_meta_box');
	}

/**
 * Renders the featured image link meta box
 */
	public function featuredImageLinkMetaBox() {
		global $post;
		$this->set('data', $this->metaToData($post->ID));
		echo $this->render('featured_image_link_meta_box');
	}
	
/**
 * Inits plugin options to white list our options
 */
	public function adminInit() {
		global $post;
		register_setting($this->info('slug').'_options', $this->info('slug').'_options');
		// add meta boxes for cross-posting
		add_meta_box('cross-post', 'Cross-site Posting', array($this, 'crossPostMetaBox'), 'post', 'side');
		add_meta_box('core', 'CORE', array($this, 'coreMetaBox'), 'page', 'side');
		if (isset($_GET['post']) && ($_GET['post'] == get_option('page_on_front'))) {
			add_meta_box('featured-image-link', 'Featured Image Link', array($this, 'featuredImageLinkMetaBox'), 'page', 'side');
		}
		// load in s3 url modifying filter. Since the plugin doesn't load the url modifier,
		// images in the media archive would not show from S3 but rather the local disk
		$lib = 'tantan-s3-cloudfront'.DS.'wordpress-s3.php';
		$class = WP_PLUGIN_DIR.DS.'tantan-s3-cloudfront'.DS.'wordpress-s3'.DS.'class-plugin-public.php';
		$tantan = get_option('tantan_wordpress_s3', false);
		if (file_exists($lib) && $tantan !== false && !empty($tantan['key'])) {
			require_once ($class);
			new TanTanWordPressS3PluginPublic();
		}
		wp_register_script('admin_scripts', $this->info('base_url').'/js/admin.js');
		wp_enqueue_script('admin_scripts');
	}
	
/**
 * Delete file from S3 when deleted from local disk
 *
 * Wordpress sometimes sends a partial path and sometimes sends and unescaped path
 * (depending on the OS), so this function normalizes it and removes the object
 * from the S3 bucket and returns a true absolute path to the file for `unlink()`
 * to do it's job.
 *
 * @param string $file Some sort of path
 * @return string Absolute path to file
 */
	public function deleteS3File($file) {
		$lib = 'tantan-s3-cloudfront'.DS.'wordpress-s3.php';
		$tantanlib = WP_PLUGIN_DIR.DS.'tantan-s3-cloudfront'.DS.'wordpress-s3'.DS.'lib.s3.php';
		$tantan = get_option('tantan_wordpress_s3', false);
		// the plugin doesn't add itself to the list of active ones, so is_plugin_active doesn't work
		if (!file_exists($tantanlib) || !$tantan || empty($tantan['key'])) {
			return $file;
		}
		$uploadpaths = wp_upload_dir();
		$hasBase = strpos($uploadpaths['basedir'], $file) > 0;
		if (!class_exists('TanTanS3')) {
			require_once $tantanlib;
		}
		$s3options = get_option('tantan_wordpress_s3');
		$s3 = new TanTanS3($s3options['key'], $s3options['secret']);
		$s3->setOptions($s3options);

		// strip path and unslashed path, since WP doesn't store the path correctly,
		// so try to normalize it so we can strip and fix it
		$partial = str_replace(get_bloginfo('siteurl'), '', $uploadpaths['baseurl']);
		$url = substr($file, stripos($file, $partial));

		// delete from bucket
		$s3->deleteObject($s3options['bucket'], substr($url, 1));

		// basedir already contains $partial
		$file = str_replace($partial, '', $url);
		$file = str_replace('/', DS, $file);
		$file = rtrim($uploadpaths['basedir'], '/').$file;
		return $file;
	}
	
/**
 * If we're using s3, delete the local files since they're in the cloud
 * 
 * @param array $data Attachment data
 * @param integer $postID Post id
 * @return array
 */
	public function deleteLocalFile($data, $postID) {
		$tantan = get_option('tantan_wordpress_s3', false);
		// the plugin doesn't add itself to the list of active ones, so is_plugin_active doesn't work
		if (!$tantan || empty($tantan['key'])) {
			return $data;
		}
		if (file_exists($data['file'])) {
			unlink($data['file']);
		}
		$uploadpaths = wp_upload_dir();
		foreach ($data['sizes'] as $thumbkey => $info) {
			$thumbPath = $uploadpaths['basedir'].$uploadpaths['subdir'].DS.$info['file'];
			if (file_exists($thumbPath)) {
				unlink($thumbPath);
			}
		}
		return $data;
	}

/**
 * Sets the default comments as 'open' or 'closed' depending on if the post type
 * is in `$disabledComments`. #YAWPH
 * 
 * @param string $content Default post content
 * @param StdClass $post Post object
 * @return string Default content
 */
	public function setDefaultComments($content = '', $post) {
		if (in_array($post->post_type, $this->disableComments)) {
			$post->comment_status = 'closed';
			$post->ping_status = 'closed';
		}
		return $content;
	}

/**
 * Loads up the menu page
 */
	public function adminMenu() {
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
	public function render($view, $emptyVars = true) {
		global $theme;
		extract($this->_vars);
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
	public function onSave($post_id, $post) {
		do_enclose($post->post_content, $post_id);
		$this->saveMeta();
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
