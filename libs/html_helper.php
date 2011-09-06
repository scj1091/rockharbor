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
 * Quick function to parse attributes
 *
 * @param array $attr Attributes
 * @return string
 */
	private function parseAttributes($attr = array()) {
		$out = array();
		foreach ($attr as $name => $value) {
			$out[] = "$name=\"$value\"";
		}
		return implode(' ', $out);
	}

}