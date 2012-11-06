<?php
/**
 * Holds all shortcode functions
 * 
 * @package rockharbor
 */
class Shortcodes {
	
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
	public function __construct($theme) {
		$this->theme = $theme;
		
		add_action('init', array($this, 'addEditorButtons'));
		add_shortcode('videoplayer', array($this, 'video'));
		add_shortcode('defaultfeature', array($this, 'defaultFeature'));
		add_shortcode('directions', array($this, 'directions'));
		add_shortcode('calendar', array($this, 'calendar'));
		add_shortcode('ebulletin-archive', array($this, 'ebulletinArchive'));
	}
	
/**
 * Renders an ebulletin archive (generated via mailchimp)
 * 
 * @param array $attr Attributes sent by WordPress defined in the editor
 * @return string 
 */
	public function ebulletinArchive($attr) {
		$id = $this->theme->options('mailchimp_folder_id');
		if (empty($id)) {
			return null;
		}
		$this->theme->set(compact('id'));
		return $this->theme->render('ebulletin_archive');
	}
	
/**
 * Renders the full calendar
 * 
 * @param array $attr Attributes sent by WordPress defined in the editor
 * @return string
 */
	public function calendar($attr) {
		$attrs = shortcode_atts(array(
			'id' => $this->theme->options('core_calendar_id')
		), $attr);
		$id = $attrs['id'];
		$this->theme->set(compact('id'));
		return $this->theme->render('calendar');
	}
	
/**
 * Renders the directions link
 * 
 * @param array $attr Attributes sent by WordPress defined in the editor
 * @return string
 */
	public function directions($attr) {
		$this->theme->set(shortcode_atts(array(
			'title' => 'Get directions',
			'link' => null
		), $attr));
		return $this->theme->render('directions');
	}

/**
 * Renders default featured graphics
 * 
 * @param array $attr Attributes sent by WordPress defined in the editor
 * @return string
 */
	public function defaultFeature($attr) {
		$this->theme->set(shortcode_atts(array(
			'link1' => null,
			'link2' => null
		), $attr));
		return $this->theme->render('default_feature');
	}

/**
 * Renders a video
 * 
 * ### Attrs:
 * - string $src The video source
 * 
 * @param array $attr Attributes sent by WordPress defined in the editor
 * @return string
 */
	public function video($attr) {
		$this->theme->set(shortcode_atts(array(
			'src' => null
		), $attr));
		return $this->theme->render('video');
	}

/**
 * Adds TinyMCE buttons for shortcodes
 * 
 * @return void
 */

	public function addEditorButtons() {
		// Don't bother doing this stuff if the current user lacks permissions
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
			return;
		}
		// Add only in Rich Editor mode
		if (get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', array($this, 'addEditorPlugins'));
			add_filter('mce_buttons', array($this, 'registerButtons'));
		}
	}
	
/**
 * Registers shortcode buttons
 *
 * @param array $buttons
 * @return array
 */
	public function registerButtons($buttons) {
	   array_push($buttons, '|', 'videoShortcode');
	   array_push($buttons, '|', 'columns');
	   return $buttons;
	}
/**
 * Adds plugin
 *
 * @param array $plugin_array
 * @return array
 */
	public function addEditorPlugins($plugin_array) {
	   $plugin_array['videoShortcode'] = $this->theme->info('base_url').'/js/mceplugins/video_plugin.js';
	   $plugin_array['columns'] = $this->theme->info('base_url').'/js/mceplugins/columns_plugin.js';
	   return $plugin_array;
	}


}