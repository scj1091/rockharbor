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
	
	public function syncAmazon() {
		include_once $this->theme->info('base_path') . DS . 'vendors' . DS . 'S3.php';
		
		if (!class_exists('S3')) {
			$message = 'Missing Amazon S3 class.';
			$this->theme->set('message', $message);
			echo $this->theme->render('admin'.DS.'sync_amazon');
			return;
		}
		
		$s3options = get_option('tantan_wordpress_s3');
		$storage = new S3($s3options['key'], $s3options['secret']);
		S3::$useExceptions = true;
		$bucket = $s3options['bucket'];
		
		if (isset($_POST) && !empty($_POST)) {
			/**
			 * Okay, so the current TanTanS3 plugin stores items differently depending
			 * on if this is a subsite or not, so we'll need to use their "method"
			 * to find the new path.
			 * 
			 * This *needs* to be cleaned up (see #11)
			 */
			include_once WP_PLUGIN_DIR.DS.'tantan-s3-cloudfront'.DS.'wordpress-s3'.DS.'class-plugin.php';
			$tanTan = new TanTanWordPressS3Plugin();
			add_filter('option_siteurl', array($tanTan, 'upload_path'));
			$uploadDir = wp_upload_dir();
			remove_filter('option_siteurl', array($tanTan, 'upload_path'));
			$parts = parse_url($uploadDir['url']);
			$prefix = substr($parts['path'], 1) .'/';
			
			$errors = array();
			foreach ($_POST['objects'] as $object => $val) {
				$moved = 0;
				if ($val != 0) {
					// copy to proper wp uploads path
					$success = false;
					try {
						$success = $storage->copyObject($bucket, 'messages/'.$object, $bucket, $prefix.$object, S3::ACL_PUBLIC_READ);
					} catch (S3Exception $e) {
						$errors[] = $e->getMessage();
					}
					if ($success) {
						// save to wp db
						$attachment = array(
							'post_mime_type' => 'video/mp4', // hardcode for now
							'guid' => get_site_url()."/$prefix$object",
							'post_title' => $object,
							'post_name' => $object,
							'post_date' => current_time('mysql'),
							'post_date_gmt' => current_time('mysql', 1)
						);
						$id = wp_insert_attachment($attachment);
						if ($id > 0) {
							// delete from old path
							try {
								$storage->deleteObject($bucket, 'messages/'.$object);
							} catch (S3Exception $e) {
								$errors[] = $e->getMessage();
							}
							$moved++;
							// tan tan junk
							delete_post_meta($id, 'amazonS3_info');
							add_post_meta($id, 'amazonS3_info', array(
								'bucket' => $bucket,
								'key' => $prefix.$object
							));
						}
					}
				}
			}
			
			$errorMsg = null;
			if (!empty($errors)) {
				$errorMsg = '<br /><br />Errors:<br />'.implode('<br />', $errors);
			}
			$this->theme->set('message', "$moved objects added to the media library.$errorMsg");
		}
		
		$results = $storage->getBucket($s3options['bucket'], 'messages');	
		$objects = array();
		foreach ($results as $result) {
			if ($result['name'] == 'messages/') {
				continue;
			}
			$objects[] = str_replace('messages/', '', $result['name']);
		}

		$this->theme->set('objects', $objects);
		echo $this->theme->render('admin'.DS.'sync_amazon');
	}

/**
 * Adds any additional menus 
 */
	public function adminMenus() {
		add_theme_page(__('Theme Options', 'rockharbor'), __('Theme Options', 'rockharbor'), 'edit_theme_options', 'theme_options', array($this, 'admin'));
		add_media_page(__('Sync Messages', 'rockharbor'), __('Sync Messages', 'rockharbor'), 'upload_files', 'sync_s3', array($this, 'syncAmazon'));
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
		add_meta_box('featured-image-link', 'Featured Image Link', array($this, 'featuredImageLinkMetaBox'), 'page', 'side');
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
		if ($this->theme->id != BLOG_ID_CURRENT_SITE) {
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
 * Renders the featured image link meta box
 */
	public function featuredImageLinkMetaBox() {
		global $post;
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		echo $this->theme->render('admin'.DS.'featured_image_link_meta_box');
	}
	
}