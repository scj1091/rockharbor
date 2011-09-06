<?php

class HtmlHelperTest extends PHPUnit_Framework_TestCase {
	
	function setUp() {
		$this->Base = new RockharborThemeBase();
	}
	
	function testImage() {
		$image = $this->Base->Html->image('test.png');
		$tags = array(
			'tag' => 'img',
			'attributes' => array(
				'src' => 'regexp:/wp-content\/themes\/(.)+\/img\/test.png/i'
			)
		);
		$this->assertTag($tags, $image);
		
		$image = $this->Base->Html->image('test.png', array(
			'alt' => 'Some picture or something'
		));
		$tags = array(
			'tag' => 'img',
			'attributes' => array(
				'src' => 'regexp:/wp-content\/themes\/(.)+\/img\/test.png/i',
				'alt' => 'Some picture or something'
			)
		);
		$this->assertTag($tags, $image);
		
		$image = $this->Base->Html->image('test.png', array(
			'alt' => 'huh?',
			'parent'
		));
		$tags = array(
			'tag' => 'img',
			'attributes' => array(
				'src' => 'regexp:/wp-content\/themes\/(.)+\/img\/test.png/i',
				'alt' => 'huh?'
			)
		);
		$this->assertTag($tags, $image);
	}
	
	function testTag() {
		$result = $this->Base->Html->tag('p', 'Some content', array(
			'onclick' => 'javascript:void();'
		));
		$expected = array(
			'tag' => 'p',
			'attributes' => array(
				'onclick' => 'javascript:void();'
			),
			'content' => 'Some content'
		);
		$this->assertTag($expected, $result);
		
		$result = $this->Base->Html->tag('p', array(
			'onclick' => 'javascript:void();'
		));
		$expected = array(
			'tag' => 'p',
			'attributes' => array(
				'onclick' => 'javascript:void();'
			)
		);
		$this->assertTag($expected, $result);
		
		$result = $this->Base->Html->tag('span', 'Hello, world!');
		$expected = array(
			'tag' => 'span',
			'content' => 'Hello, world!'
		);
		$this->assertTag($expected, $result);
	}
	
}