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
		add_shortcode('calendar', array($this, 'calendar'));
		add_shortcode('ebulletin-archive', array($this, 'ebulletinArchive'));
		add_shortcode('children-grid', array($this, 'childrenGrid'));
		add_shortcode('service-times', array($this, 'serviceTimes'));
		add_shortcode('address', array($this, 'address'));
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
 * Renders a grid layout of all children pages for the current page
 *
 * ### Attrs:
 * - int `columns` Number of items per row
 * - int `limit` Number of items to show per page
 *
 * @param array $attr Attributes sent by WordPress defined in the editor
 * @return string
 */
	public function childrenGrid($attr) {
		global $wp_query, $wp_rewrite, $post;

		$attrs = shortcode_atts(array(
			'columns' => 4,
			'limit' => 16
		), $attr);

		$_old_query = $wp_query;

		$page = (get_query_var('paged')) ? get_query_var('paged')-1 : 0;
		$offset = $page*$attrs['limit'];
		$query = array(
			'post_parent' => $post->ID,
			'post_type' => $post->post_type,
			'posts_per_page' => $attrs['limit'],
			'paged' => get_query_var('paged')
		);
		$wp_query = new WP_Query($query);
		$wp_query->query($query);

		// we have to gobble this up and return it so it doesn't just print everywhere
		ob_start();
		// loop within loop
		$c = 0;
		$colSize = floor(100/$attrs['columns']) - 1;
		// wrap everything in a div
		echo '<div class="clearfix grid-layout">';
		// wrap the first row
		echo '<div class="clearfix">';
		while (have_posts()) {
			if ($c % $attrs['columns'] == 0) {
				echo '</div><div class="clearfix">';
			}
			$c++;
			the_post();
			$gridArticle = $this->theme->render('grid');
			echo $this->theme->Html->tag('div', $gridArticle, array(
				'style' => "float:left;width:$colSize%;margin-right: 1%;"
			));
		}
		// close last row
		echo '</div>';
		$this->theme->set('wp_rewrite', $wp_rewrite);
		$this->theme->set('wp_query', $wp_query);
		echo $this->theme->render('pagination');
		// close everything else
		echo '</div>';
		$return = ob_get_clean();

		// back to the old query
		$wp_query = $_old_query;

		return $return;
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