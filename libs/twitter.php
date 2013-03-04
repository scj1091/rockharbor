<?php
/**
 * Includes
 */
require_once VENDORS . DS . 'tmhOAuth' . DS . 'tmhOAuth.php';

class Twitter {

/**
 * Constructor
 *
 * @param RockharborThemeBase $theme
 */
	public function __construct($theme = null) {
		$this->theme = $theme;
	}

/**
 * Gets a Twitter search feed
 *
 * @param string $searchTerm Search term
 * @param integer $limit Maximum items to show
 * @return array Array of statuses
 */
	public function fetchFeed($searchTerm = null, $limit = 5) {
		$authToken = $this->theme->options('twitter_oauth_token');
		$authTokenSecret = $this->theme->options('twitter_oauth_token_secret');

		if (empty($authToken) || empty($authTokenSecret)) {
			return array();
		}

		$oauth = new tmhOAuth(array(
			'consumer_key' => TWITTER_CONSUMER_KEY,
			'consumer_secret' => TWITTER_CONSUMER_SECRET
		));

 		$oauth->config['user_token'] = $authToken;
		$oauth->config['user_secret'] = $authTokenSecret;

		$searchTerm = urlencode($searchTerm);
		$code = $oauth->request('GET', $oauth->url('1.1/search/tweets'), array(
			'q' => urlencode($searchTerm),
			'count' => $limit
		));

		if ($code == 200) {
			$response = json_decode($oauth->response['response'], true);
			return $response['statuses'];
		}
		return array();
	}

/**
 * Removes stored oauth tokens
 *
 * @return boolean
 */
	public function clearOauthTokens() {
		$this->theme->options('twitter_oauth_token', null);
		$this->theme->options('twitter_oauth_token_secret', null);
		return true;
	}

/**
 * Returns the oauth callback
 *
 * @return string
 */
	public function oauthCallback($nonce = null) {
		if (is_null($nonce)) {
			$nonce = wp_create_nonce('action-nonce');
		}
		return ($this->theme->info('base_url')."/action.php?action=oauth&_wpnonce=$nonce");
	}

/**
 * Gets Twitter oauth request tokens
 *
 * @return array Request tokens
 */
	public function oauthRequestToken() {
		if (!defined('TWITTER_CONSUMER_KEY') || !defined('TWITTER_CONSUMER_SECRET')) {
			return array();
		}

		$oauth = new tmhOAuth(array(
			'consumer_key' => TWITTER_CONSUMER_KEY,
			'consumer_secret' => TWITTER_CONSUMER_SECRET
		));

		$code = $oauth->request('POST', $oauth->url('oauth/request_token', ''), array(
			'oauth_callback' => $this->oauthCallback()
		));

		if ($code == 200) {
			$_SESSION['oauth'] = $oauth->extract_params($oauth->response['response']);
			return $_SESSION['oauth'];
		} else {
			return array();
		}
	}

/**
 * Gets access tokens from a previous oauth request and stores them in the
 * options table
 *
 * @param string $verifier Verifier string, sent by Twitter
 * @return array Tokens
 */
	public function oauthAccessToken($verifier = null) {
		$requestTokens = $_SESSION['oauth'];
		$oauth = new tmhOAuth(array(
			'consumer_key' => TWITTER_CONSUMER_KEY,
			'consumer_secret' => TWITTER_CONSUMER_SECRET
		));
		$oauth->config['user_token'] = $requestTokens['oauth_token'];
		$oauth->config['user_secret'] = $requestTokens['oauth_token_secret'];

		$code = $oauth->request('POST', $oauth->url('oauth/access_token', ''), array(
			'oauth_verifier' => $verifier
		));

		if ($code == 200) {
			$response = $oauth->extract_params($oauth->response['response']);
			$this->theme->options('twitter_oauth_token', $response['oauth_token']);
			$this->theme->options('twitter_oauth_token_secret', $response['oauth_token_secret']);
			unset($_SESSION['oauth']);
			return $response;
		} else {
			return array();
		}
	}

}
