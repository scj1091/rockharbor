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
		add_shortcode('audioplayer', array($this, 'audio'));
		add_shortcode('ebulletin-archive', array($this, 'ebulletinArchive'));
		add_shortcode('service-times', array($this, 'serviceTimes'));
		add_shortcode('address', array($this, 'address'));
		add_shortcode('quick-contact', array($this, 'quickContact'));
		add_shortcode('rh-groupfinder', array($this, 'rhGroupfinder'));
	}

/**
 * Checks if content has a particular shortcode. If `$content` is not defined,
 * the current post's content will be checked.
 *
 * Temporarily overwrites all shortcode tags in order to generate the regexp
 * and search for just the `$shortcode` passed. #YAWPH
 *
 * @param string $shortcode Shortcode name, as it was registered
 * @param string $content The content to search
 * @return boolean
 */
	public function hasShortcode($shortcode = '', $content = null) {
		global $shortcode_tags, $post;
		$_backup_tags = $shortcode_tags;

		// create regexp using only this shortcode
		$shortcode_tags = array($shortcode => array());
		$shortcodeRegexp = get_shortcode_regex();

		// restore all shortcodes
		$shortcode_tags = $_backup_tags;

		if (is_null($content)) {
			$content = $post->post_content;
		}

		return preg_match("/$shortcodeRegexp/s", $content) > 0;
	}

/**
 * Renders quick contact form
 *
 * @param array $attr Attributes sent by WordPress defined in the editor
 * @return string
 */
	public function quickContact($attr) {
		$attrs = shortcode_atts(array(
			'type' => 'feedback'
		), $attr);
		$this->theme->set('type', $attrs['type']);
		return $this->theme->render('quick_contact');
	}

/**
 * Renders address
 *
 * @param array $attr Attributes sent by WordPress defined in the editor
 * @return string
 */
	public function address($attr) {
		$attrs = shortcode_atts(array(
			'campus' => $this->theme->info('id')
		), $attr);
		$this->theme->set('address', $this->theme->options('address', false, $attrs['campus']));
		return $this->theme->render('address');
	}

/**
 * Renders service times
 *
 * @param array $attr Attributes sent by WordPress defined in the editor
 * @return string
 */
	public function serviceTimes($attr) {
		$attrs = shortcode_atts(array(
			'campus' => $this->theme->info('id')
		), $attr);
		$this->theme->set('times', $this->theme->options('service_time', false, $attrs['campus']));
		return $this->theme->render('service_times');
	}

/**
 * Removes a shortcode
 *
 * Useful for when you're taking a video out of the content flow to display
 * elsewhere and don't want the video to be repeated.
 *
 * @param string $shortcode Shortcode to remove
 * @see http://stackoverflow.com/questions/9440423/wordpress-strip-single-shortcode-from-posts
 */
	public function remove($shortcode) {
		add_shortcode($shortcode, '__return_false');
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
 * Renders an audio player
 *
 * ### Attrs:
 * - string $src The audio source
 * - string $campus The campus/blog id
 *
 * @param array $attr Attributes sent by WordPress defined in the editor
 * @return string
 */
	public function audio($attr) {
		$this->theme->set(shortcode_atts(array(
			'src' => null,
			'campus' => null
		), $attr));
		return $this->theme->render('audio');
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
			'src' => null,
			'poster' => null,
			'campus' => null
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
	   array_push($buttons, '|', 'videoShortcode', 'audioShortcode');
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
		$min = WP_DEBUG ? '' : '.min';
		$plugin_array['audioShortcode'] = $this->theme->info('base_url')."/js/mceplugins/audio_plugin{$min}.js";
		$plugin_array['videoShortcode'] = $this->theme->info('base_url')."/js/mceplugins/video_plugin{$min}.js";
		$plugin_array['columns'] = $this->theme->info('base_url')."/js/mceplugins/columns_plugin{$min}.js";
		return $plugin_array;
	}

/**
 * Displays the CCBPress groupfinder, but provides more options
 *
 * @param array $atts
 * @return string
 */
	public function rhGroupfinder($atts) {
		//wp_enqueue_style( 'ccbpress-group-search', CCBPRESS_GROUPS_PLUGIN_URL . 'assets/css/group-search.css', array( 'dashicons' ), '1.0.0' );
		//wp_enqueue_script( 'ccbpress-group-search', CCBPRESS_GROUPS_PLUGIN_URL . 'assets/js/group-search.js', array( 'jquery' ), '1.0.0' );

		// set default attributes
		$atts = shortcode_atts( array(
			'campus_id'			=> null,
			'area_id'			=> null,
			'meet_day_id'		=> null,
			'meet_time_id'		=> null,
			'department_id'		=> null,
			'group_type_id'		=> null,
			'udf_1_id'			=> null,
			'udf_2_id'			=> null,
			'udf_3_id'			=> null,
			'childcare'			=> 0,
			'exclude_full'		=> 0,
			'hide_search_form'	=> 'false',
			'auto_search'		=> 'false',
			'override_dropdown' => null
		), $atts );

		// Allow $_POST values to overwrite shortcode atts and defaults
		//$atts = shortcode_atts($atts, $_POST);

		$shortcode = "[ccbpress_group_search";
		$overrideDropdown = $atts['override_dropdown'];
		unset($atts['override_dropdown']);
		foreach ($atts as $key => $value) {
			// All => null
			if ($value == 'all') {
				$value = null;
			}
			if (!is_null($value)) {
				$shortcode .= " ".$key."=\"".$value."\"";
			}
		}
		if (!is_null($overrideDropdown) && ($overrideDropdown != '')) {
			$overrides = explode(';', $overrideDropdown); // separate the overrides
			foreach ($overrides as $override) {
				list($key, $value) = explode('|', $override);
				add_filter('pre_option_'.$key, function() use ($value) {
					return $value;
				});
			}
		}

		//return apply_filters( 'ccbpress_group_search_output', $atts );
		$shortcode .= ']';
		return do_shortcode($shortcode);
	}

}
