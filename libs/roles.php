<?php

/**
 * ROCKHARBOR Role class. Handles custom roles
 * 
 * @package rockharbor
 */
class Roles {
	
/**
 * The theme
 * 
 * @var RockharborThemeBase 
 */
	public $theme = null;

/**
 * Role setup
 * 
 * @param RockharborThemeBase $theme 
 */
	function __construct($theme = null) {
		$this->theme = $theme;
		add_filter('init', array($this, 'init'));
	}
	
	public function init() {
		global $wp_roles;
		if (!$wp_roles->is_role('production')) {
			// to update the role, I had to run `remove_role()` first #YAWPH
			add_role('production', 'Production Staff', array(
				'upload_files' => true,
				'edit_posts' => false,
				'read' => true
			));
		}
		
		$user = $this->theme->userMetaToData(get_current_user_id());
		if (!empty($user['show_only_owned_posts'])) {
			add_filter('pre_get_posts', array($this, 'limitPostsToUser'));
		}
	}

/**
 * Limits posts that users see to their own
 * 
 * @param WP_Query $query The query object
 * @return WP_Query
 */
	public function limitPostsToUser($query) {
		if ($query->is_admin) {
			global $user_ID;
			$query->set('author', $user_ID);
		}
		return $query;
	}
	
}