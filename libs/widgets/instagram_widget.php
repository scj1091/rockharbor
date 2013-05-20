<?php
/**
 * InstagramWidget Widget
 *
 * Creates a widget that shows an Instagram feed
 *
 * @package rockharbor
 * @subpackage rockharbor.libs.widgets
 */
class InstagramWidget extends Widget {

	public $settings = array(
		'base_id' => 'instagram',
		'name' => 'Instagram',
		'description' => 'Displays a feed of Instagram images.'
	);

/**
 * Renders the frontend widget
 *
 * @param array $args Options provided at registration
 * @param array $data Database values provided in backend
 */
	public function widget($args, $data) {
		$defaults = array(
			'title' => 'Instagram',
			'columns' => 2,
			'username' => null,
			'limit' => 4,
			'before_content' => '',
			'after_content' => ''
		);
		$data = array_merge($defaults, $data);

		// fetch feed
		$data['results'] = $this->getFeed($data['username'], $data['limit']);

		parent::widget($args, $data);
	}

/**
 * Renders the form for the widget
 *
 * @param array $data Database values
 */
	public function form($data) {
		$defaults = array(
			'title' => 'Instagram',
			'columns' => 2,
			'username' => null,
			'limit' => 4,
			'before_content' => '',
			'after_content' => ''
		);
		$data = array_merge($defaults, $data);
		parent::form($data);
	}

/**
 * Gets an instagram feed
 *
 * @param string $username Username
 * @param integer $limit Number of images to return
 * @return array
 */
	protected function getFeed($username = null, $limit = 5) {
		if (empty($username)) {
			return array();
		}

		$results = get_transient('InstagramWidget::getFeed');
		if ($results === false) {
			$request = new WP_Http();
			$response = $request->get("http://ink361.com/feed/user/$username");
			$results = array();
			if (!is_a($response, 'WP_Error')) {
				try {
					$resultsXml = new SimpleXMLElement($response['body']);
					foreach ($resultsXml->channel[0]->item as $node) {
						// ink361 sends <a href="ink361.com"><img src="image.jpg"></a>
						try {
							$imageNode = new DOMDocument();
							$imageNode->loadHTML($node->description);
							$img = $imageNode->getElementsByTagName('img');
							$results[] = $img->item(0)->getAttribute('src');
							if (count($results) == $limit) {
								break;
							}
						} catch  (Exception $e) {}
					}
					set_transient('InstagramWidget::getFeed', $results, HOUR_IN_SECONDS);
				} catch (Exception $e) {}
			}
		}
		return $results;
	}

}