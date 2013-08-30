<?php
/**
 * CORE Widget
 *
 * @package rockharbor
 * @subpackage rockharbor.libs.widgets
 */
class CoreWidget extends Widget {

	public $settings = array(
		'base_id' => 'core',
		'name' => 'Core',
		'description' => 'Widget for showing a CORE feed.'
	);

/**
 * Renders the frontend core widget
 *
 * This widget uses the widget settings to pull CORE events. If those settings
 * are not defined, it falls back to the CORE meta for the page. If that isn't
 * defined, nothing is rendered.
 *
 * @param array $args Options provided at registration
 * @param array $data Database values provided in backend
 */
	public function widget($args, $data) {
		global $post;

		$events = array();

		$defaults = array(
			'core_campus_id' => null,
			'core_id' => null,
			'core_involvement_id' => null,
			'limit' => 0
		);
		$data = array_merge($defaults, $data);

		if (empty($data['core_id']) && empty($data['core_involvement_id'])) {
			$metadata = $this->theme->metaToData($post->ID);
			if (empty($metadata['limit']) && !empty($data['limit']))
				$metadata['limit'] = $data['limit'];
			$data = array_merge($data, $metadata);
		}

		if (empty($data['core_campus_id']) && empty($data['core_id']) && empty($data['core_involvement_id'])) {
			return;
		}

		// build url
		$endpoint = $this->getCoreCalendarEndpoint();
		if (!empty($data['core_campus_id'])) {
			$endpoint .= '/Campus:'.$data['core_campus_id'];
		}
		if (!empty($data['core_id'])) {
			$endpoint .= '/Ministry:'.$data['core_id'];
		}
		if (!empty($data['core_involvement_id'])) {
			$endpoint .= '/Involvement:'.$data['core_involvement_id'];
		}
		$start = strtotime('now');
		if (!empty($data['start_date'])) {
			$start = strtotime($data['start_date']);
		}
		$end = strtotime('+60 days', $start);
		if (!empty($data['end_date'])) {
			$end = strtotime($data['end_date']);
		}
		$endpoint .= '/full.json?start='.$start.'&end='.$end;

		$this->theme->set('url', $endpoint);
		$this->theme->set('limit', $data['limit']);
		parent::widget($args, $data);
	}

/**
 * Renders the form for the widget
 *
 * @param array $data Database values
 */
	public function form($data) {
		$defaults = array(
			'core_campus_id' => null,
			'core_id' => null,
			'core_involvement_id' => null,
			'limit' => null,
			'start_date' => null,
			'end_date' => null
		);
		$data = array_merge($defaults, $data);
		parent::form($data);
	}

/**
 * Gets core calendar endpoint
 *
 * @return string CORE calendar endpoint
 */
	public function getCoreCalendarEndpoint() {
		return 'https://core.rockharbor.org/dates/calendar';
	}

}