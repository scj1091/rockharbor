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
		register_setting('rockharbor_options', 'rockharbor_options');

		// default options
		update_option('blogdescription', 'We are a church of communities living out the gospel together.');
		update_option('blogname', 'RH '.$this->theme->info('name'));
		update_option('image_default_link_type', 'file');

		// add meta boxes
		add_meta_box('media-options', 'Media', array($this, 'mediaMetaBox'), 'page', 'normal');
		add_meta_box('media-options', 'Media', array($this, 'mediaMetaBox'), 'post', 'normal');
		add_meta_box('template-options', 'Template Options', array($this, 'templateOptionsMetaBox'), 'page', 'side');
		add_meta_box('cross-post', 'Cross-site Posting', array($this, 'crossPostMetaBox'), 'post', 'side');
		add_meta_box('core', 'CORE', array($this, 'coreMetaBox'), 'page', 'side');

		// add tags to 'page' post type
		add_meta_box('tagsdiv-post_tag', __('Page Tags'), 'post_tags_meta_box', 'page', 'side');
		register_taxonomy_for_object_type('post_tag', 'page');

		// special admin front page only tasks
		if (isset($_GET['post']) && ($_GET['post'] == get_option('page_on_front'))) {
			$this->frontPage();
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
		add_filter('wp_update_attachment_metadata', array($this, 'transferToS3'), 10, 2);
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
		$s3Key = $this->theme->options('s3_access_key');
		$s3KeySecret = $this->theme->options('s3_access_secret');
		$bucket = $this->theme->options('s3_bucket');

		require_once VENDORS . DS . 'S3.php';
		$S3 = new S3($s3Key, $s3KeySecret);

		$path = $this->getS3Path($file);

		// delete from bucket
		$S3->deleteObject($bucket, $path);
		
		return $file;
	}

/**
 * Transfers a recently uploaded file to S3, and deletes the local copy
 *
 * @param array $data Attachment data
 * @param integer $postID Post id
 * @return array
 */
	public function transferToS3($data, $postID) {
		$s3Key = $this->theme->options('s3_access_key');
		$s3KeySecret = $this->theme->options('s3_access_secret');
		$bucket = $this->theme->options('s3_bucket');

		require_once VENDORS . DS . 'S3.php';
		$S3 = new S3($s3Key, $s3KeySecret);

		$type = get_post_mime_type($postID);
		$file = array(
			'type' => $type,
			'file' => $data['file'],
			'size' => filesize($data['file']),
		);
		$s3filePath = $this->getS3Path($data['file']);

		if (file_exists($data['file'])) {
			if ($S3->putObject($file, $bucket, $s3filePath, $S3::ACL_PUBLIC_READ)) {
				unlink($data['file']);
			}
		}

		foreach ($data['sizes'] as $thumbkey => $info) {
			$fullThumbPath = str_replace(basename($data['file']), $info['file'], $data['file']);
			$s3ThumbPath = $this->getS3Path($fullThumbPath);

			$file = array(
				'type' => $type,
				'file' => $fullThumbPath,
				'size' => filesize($fullThumbPath),
			);

			if (file_exists($fullThumbPath)) {
				if ($S3->putObject($file, $bucket, $s3ThumbPath, $S3::ACL_PUBLIC_READ)) {
					unlink($fullThumbPath);
				}
			}
		}
		return $data;
	}

/**
 * Takes a fully qualified local path and creates a partial path for S3.
 *
 * This is necessary because, historically S3 objects were maintained by a plugin
 * which modified the upload path
 *
 * @param type $fullPath Full local path to file
 * @return string Partial path
 */
	public function getS3Path($fullPath = '') {
		global $current_blog;

		$uploadpaths = wp_upload_dir();
		$partial = str_replace(get_bloginfo('siteurl'), '', $uploadpaths['baseurl']);
		$path = substr($fullPath, stripos($fullPath, $partial));

		$subsitePath = null;
		if ($current_blog && $this->theme->info('id') > 1) {
			// the s3 plugin that is currently used stores files under the domain
			$subsitePath = '/'.substr($current_blog->domain, 0, strpos($current_blog->domain, '.'));
		}

		return $subsitePath . $path;
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
				// the 'custom fields' meta box duplicates this meta as
				// $metaid => array($key => $value) so we need to ignore those
				// on save
				if (is_numeric($name) && is_array($value)) {
					continue;
				}
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
 * Renders the meta box for core events on pages
 */
	public function mediaMetaBox() {
		global $post;
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		echo $this->theme->render('admin'.DS.'media_meta_box');
	}

/**
 * Renders the options meta box for the front page
 */
	public function frontPageOptions() {
		global $post;
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		echo $this->theme->render('admin'.DS.'front_page_options');
	}

/**
 * Renders the template options meta box
 */
	public function templateOptionsMetabox() {
		global $post;
		$this->theme->set('data', $this->theme->metaToData($post->ID));
		echo $this->theme->render('admin'.DS.'template_options_meta_box');
	}

}