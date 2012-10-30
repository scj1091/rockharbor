<?php

/**
 * ROCKHARBOR Admin class. All admin (backend) related tasks should go here.
 * 
 * @package rockharbor
 */
class Admin {
		
/**
 * The theme
 * 
 * @var RockharborThemeBase 
 */
	public $theme = null;

/**
 * Registers all shortcodes
 * 
 * @param RockharborThemeBase $theme 
 */	
	public function __construct($theme = null) {
		$this->theme = $theme;
		
		if (!$this->theme->isChildTheme()) {
			add_action('admin_menu', array($this, 'adminMenus'));
		}
		add_action('admin_init', array($this, 'init'));
	}
	
/**
 * Initializes admin specific code 
 * 
 * @return void 
 */
	public function init() {
		// register options
		register_setting($this->theme->info('slug').'_options', $this->theme->info('slug').'_options');
		
		// default options
		update_option('blogdescription', 'We are a church of communities living out the gospel together.');
		update_option('blogname', 'RH '.$this->theme->info('short_name'));
		update_option('image_default_link_type', 'file');
		
		// add meta boxes for cross-posting
		add_meta_box('cross-post', 'Cross-site Posting', array($this, 'crossPostMetaBox'), 'post', 'side');
		add_meta_box('core', 'CORE', array($this, 'coreMetaBox'), 'page', 'side');
		
		// add tags to 'page' post type
		add_meta_box('tagsdiv-post_tag', __('Page Tags'), 'post_tags_meta_box', 'page', 'side');
		register_taxonomy_for_object_type('post_tag', 'page'); 
		
		// special admin front page only tasks
		if (isset($_GET['post']) && ($_GET['post'] == get_option('page_on_front'))) {
			$this->frontPage();
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
		wp_register_script('admin_scripts', $this->theme->info('base_url').'/js/admin.js');
		wp_enqueue_script('admin_scripts');
		
		add_filter('default_content', array($this, 'setDefaultComments'), 1, 2);
		
		// exit if we're in a child theme, otherwise the following code is executed
		// twice which causes problems #YAWPH
		if ($this->theme->isChildTheme()) {
			return;
		}
		
		add_action('edit_user_profile', array($this, 'userPage'));
		add_action('show_user_profile', array($this, 'userPage'));
		add_action('personal_options_update', array($this, 'onUserSave'));
		add_action('edit_user_profile_update', array($this, 'onUserSave'));
		
		add_filter('save_post', array($this, 'onSave'), 1, 2);
		add_filter('wp_delete_file', array($this, 'deleteS3File'));
		add_filter('wp_update_attachment_metadata', array($this, 'deleteLocalFile'), 10, 2);
	}
	
/**
 * Adds any additional menus 
 */
	public function adminMenus() {
		add_theme_page(__('Theme Options', 'rockharbor'), __('Theme Options', 'rockharbor'), 'edit_theme_options', 'theme_options', array($this, 'admin'));
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
 * Any meta boxes that go on the front page only belong here
 * 
 * @return void 
 */
	public function frontPage() {
		add_meta_box('front-page-options', 'Front Page Options', array($this, 'frontPageOptions'), 'page');
	}
	
/**
 * Any meta boxes that go on the user profile page
 * 
 * @param WP_User $user The user object
 * @return void 
 */
	public function userPage($user) {
		// only show these options to administrators
		if (current_user_can('delete_users')) {
			$this->theme->set('userId', $user->ID);
			$this->theme->set('data', $this->theme->userMetaToData($user->ID));
			echo $this->theme->render('admin'.DS.'user_details');
		}
	}

/**
 * Saves user meta
 * 
 * @param int $userId The user id
 */
	public function onUserSave($userId) {
		// only save these options if logged in user is an administrator
		if (current_user_can('delete_users')) {
			if (isset($_POST['usermeta'])) {
				foreach ($_POST['usermeta'] as $name => $value) {
					update_user_meta($userId, $name, $value);
				}
			}
		}
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
		if (in_array($post->post_type, $this->theme->disableComments)) {
			$post->comment_status = 'closed';
			$post->ping_status = 'closed';
		}
		return $content;
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
 * Renders the theme options panel
 */
	public function admin() {
		$out = $this->theme->render('admin'.DS.'theme_options');
		echo $out;
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
		$blogs = $this->theme->getBlogs();
		if ($this->theme->info('id') != BLOG_ID_CURRENT_SITE) {
			// main blog only
			$blogs = array($blogs[0]);
		} else {
			// any blog, excluding self
			unset($blogs[0]);
		}
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		$this->theme->set('blogs', $blogs);
		echo $this->theme->render('admin'.DS.'cross_post');
	}

/**
 * Renders the meta box for core events on pages
 */
	public function coreMetaBox() {
		global $post;
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		echo $this->theme->render('admin'.DS.'core_meta_box');
	}

/**
 * Renders the options meta box for the front page
 */
	public function frontPageOptions() {
		global $post;
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		echo $this->theme->render('admin'.DS.'front_page_options');
	}
	
}