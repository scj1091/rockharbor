<?php

/**
 * A helper class to output html
 *
 * @package rockharbor
 */
class HtmlHelper {

/**
 * The theme object
 * 
 * @var RockharborThemeBase 
 */
	protected $theme = null;
	
/**
 * The prefix to put before input names
 * 
 * @var string
 */
	public $inputPrefix = '';
	
/**
 * Array of default data for inputs
 * 
 * @var array
 */
	protected $data = array();
	
/**
 * Sets the theme object for use in this class
 * 
 * @param RockharborThemeBase $theme 
 */
	public function __construct($theme = null) {
		$this->theme = $theme;
	}

/**
 * Creates an image tag
 *
 * ### Attr
 * - boolean $parent If true, will pull from parent theme, otherwise child theme
 * - ...rest Attributes for the img tag
 * 
 * @param string $url The name of the image
 * @param array $attr Attributes and options
 * @return string Image code
 */
	public function image($url, $attr = array()) {
		$_options = array(
			'parent' => false
		);
		$attr += $_options;
		$options = array_intersect_key($attr, $_options);
		$attr = array_diff_key($attr, $options);
		if (empty($url)) {
			return null;
		}
		$path = $options['parent'] ? $this->theme->info('base_url') : $this->theme->info('url');
		return '<img src="'.$path.'/img/'.$url.'" '.$this->parseAttributes($attr).'/>';
	}
	
/**
 * Creates a basic HTML tag.
 * 
 * If `$content` is an array, the tag is treated as a self-closing tag and 
 * `$content` is treated as the attributes.
 * 
 * @param string $tag Tag name
 * @param string $content Content to fill the tag
 * @param array $attr Array of attributes
 * @return string Html code
 */
	public function tag($tag, $content, $attr = array()) {
		$out = "<$tag";
		if (is_array($content)) {
			$attr = $content;
		}
		$out .= ' '.$this->parseAttributes($attr);
		if ($content === $attr) {
			$out .= ' />';
		} else {
			$out .= ">$content</$tag>";
		}
		return $out;
	}

/**
 * Creates an input field
 * 
 * If `$label` is false, no label will be created. If no `$value` is defined,
 * it will look for one in `$this->data` and use it instead.
 * 
 * If `$div` is false, it won't be wrapped in a div. It's a string, the string
 * value will be used as a class.
 * 
 * @param string $name Name of input
 * @param array $options List of options
 */
	public function input($name, $options = array()) {
		$_default = array(
			'type' => 'input',
			'value' => null,
			'name' => $name,
			'label' => $name,
			'options' => array(),
			'before' => '',
			'after' => '',
			'between' => '',
			'div' => true
		);
		$options = array_merge($_default, $options);
		
		if (is_null($options['value']) && isset($this->data)) {
			$data = $this->data;
			if (isset($data[$this->inputPrefix])) {
				$data = $this->data[$this->inputPrefix];
			}
			if (isset($data[$name])) {
				$options['value'] = $data[$name];
			}
		}
		if ($options['value'] === null) {
			unset($options['value']);
		}
		if (!empty($this->inputPrefix)) {
			$options['name'] = "$this->inputPrefix[$name]";
		}
		$options['id'] = $options['name'];
		
		$before = $options['before'];
		unset($options['before']);
		$after = $options['after'];
		unset($options['after']);
		$between = $options['between'];
		unset($options['between']);
		
		$out = '';
		if (!empty($before)) {
			$out .= $before;
		}
		
		if ($options['label'] !== false) {
			$out .= $this->tag('label', $options['label'], array(
				'for' => $options['name'],
				'class' => 'description'
			));
		}
		if (!empty($between)) {
			$out .= $between;
		}
		unset($options['label']);
		$selectOptions = $options['options'];
		unset($options['options']);
		$type = $options['type'];
		unset($options['type']);
		$div = $options['div'];
		unset($options['div']);
		switch ($type) {
			case 'select':
				$selected = $options['value'];
				unset($options['value']);
				$select = '';
				foreach ($selectOptions as $selectValue => $selectLabel) {
					$_attrs = array('value' => $selectValue);
					if ($selectValue == $selected) {
						$_attrs['selected'] = 'selected';
					}
					$select .= $this->tag('option', $selectLabel, $_attrs);
				}
				$out .= $this->tag('select', $select, $options);
			break;
			default:
				$options['type'] = $type;
				$out .= $this->tag('input', $options);
			break;
		}
		if (!empty($after)) {
			$out .= $after;
		}
		unset($options['after']);
		
		if ($div === false) {
			return $out;
		}
		$attrs = array();
		if (is_string($div)) {
			$attrs['class'] = $div;
		}
		return $this->tag('div', $out, $attrs);
	}
	
/**
 * Data for input fields
 * 
 * @param array $data Data to set
 * @return array
 */
	public function data($data = array()) {
		$this->data = array_merge($this->data, $data);
		return $this->data;
	}

/**
 * Quick function to parse attributes
 *
 * @param array $attr Attributes
 * @return string
 */
	public function parseAttributes($attr = array()) {
		$out = array();
		foreach ($attr as $name => $value) {
			$name = esc_attr($name);
			$value = esc_attr($value);
			$out[] = "$name=\"$value\"";
		}
		return implode(' ', $out);
	}

}