<?php
/**
 * Includes
 */
require_once LIBS . DS . 'twitter.php';

/**
 * Social Widget
 *
 * @package rockharbor
 * @subpackage rockharbor.libs.widgets
 */
class SocialWidget extends Widget {

/**
 * Widget settings
 *
 * @var array
 */
	public $settings = array(
		'base_id' => 'social',
		'name' => 'Social',
		'description' => 'Widget for showing social connections.'
	);

/**
 * Renders the frontend social widget
 *
 * This widget uses a combination of theme options and widget settings.
 *
 * @param array $args Options provided at registration
 * @param array $data Database values provided in backend
 */
	public function widget($args, $data) {
		$defaults = array(
			'title' => null,
			'twitter_limit' => null
		);
		$data = array_merge($defaults, $data);

		$twitteruser = $this->theme->options('twitter_user');
		$twitterterm = $this->theme->options('twitter_search');
		$facebookUser = $this->theme->options('facebook_user');

		$tweets = $this->getTweets($twitterterm, $data['twitter_limit']);

		if (!empty($facebookUser)) {
			$facebookResults = $this->getFacebook($facebookUser);
		} else {
			$facebookResults = array();
		}

		$this->theme->set(compact('tweets', 'facebookResults', 'facebookUser'));

		parent::widget($args, $data);
	}

/**
 * Renders the form for the widget
 *
 * @param array $data Database values
 */
	public function form($data) {
		$defaults = array(
			'title' => null,
			'twitter_limit' => null
		);
		$data = array_merge($defaults, $data);
		parent::form($data);
	}

/**
 * Gets Facebook info
 *
 * @param string $user Facebook user account
 * @return array
 */
	protected function getFacebook($user) {
		$response = wp_remote_get('http://graph.facebook.com/'.$user);
		return json_decode($response['body'], true);
	}

/**
 * Gets a list of tweets
 *
 * @param string $term Term to search for
 * @param integer $limit Number of tweets to pull
 * @return array
 */
	protected function getTweets($term = 'none', $limit = 5) {
		$tz = get_option('timezone_string');
		if (empty($tz)) {
			$tz = 'America/Los_Angeles';
		}
		date_default_timezone_set($tz);

		return $this->theme->Twitter->fetchFeed($term, $limit);
	}

}