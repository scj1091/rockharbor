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
	
	function testParseAttributes() {
		$results = $this->Base->Html->parseAttributes(array(
			'name' => 'title',
			'attr' => 'value"'
		));
		$this->assertEquals($results, ' name="title" attr="value&quot;"');
	}
	
	function testData() {
		$data = $this->Base->Html->data();
		$this->assertEmpty($data);
		
		$data = $this->Base->Html->data(array(
			'value' => 'something'
		));
		$this->assertEquals($data, array(
			'value' => 'something'
		));
		
		$data = $this->Base->Html->data(array(
			'value' => 'overridden'
		));
		$this->assertEquals($data, array(
			'value' => 'overridden'
		));
	}
	
	function testInput() {
		$input = $this->Base->Html->input('myname');
		$labelTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'label',
				'attributes' => array(
					'class' => 'description',
					'for' => 'myname'
				),
				'content' => 'myname'
			)
		);
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'name' => 'myname',
					'id' => 'myname',
					'type' => 'text'
				)
			)
		);
		$this->assertTag($labelTag, $input);
		$this->assertTag($inputTag, $input);
		
		$input = $this->Base->Html->input('myname', array('label' => false));
		$labelTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'label',
				'attributes' => array(
					'class' => 'description',
					'for' => 'myname'
				),
				'content' => 'myname'
			)
		);
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'name' => 'myname',
					'id' => 'myname',
					'type' => 'text'
				)
			)
		);
		$this->assertNotTag($labelTag, $input);
		$this->assertTag($inputTag, $input);
		
		$input = $this->Base->Html->input('myname', array('label' => 'Some label'));
		$labelTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'label',
				'attributes' => array(
					'class' => 'description',
					'for' => 'myname'
				),
				'content' => 'Some label'
			)
		);
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'name' => 'myname',
					'id' => 'myname',
					'type' => 'text'
				)
			)
		);
		$this->assertTag($labelTag, $input);
		$this->assertTag($inputTag, $input);
		
		$this->Base->Html->data(array(
			'myname' => 'test value'
		));
		$input = $this->Base->Html->input('myname', array('label' => false));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'name' => 'myname',
					'id' => 'myname',
					'type' => 'text',
					'value' => 'test value'
				)
			)
		);
		$this->assertTag($inputTag, $input);
		
		$this->Base->Html->data(array(
			'myname' => 'test value'
		));
		$input = $this->Base->Html->input('myname', array('label' => false, 'value' => 'overridden value'));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'name' => 'myname',
					'id' => 'myname',
					'type' => 'text',
					'value' => 'overridden value'
				)
			)
		);
		$this->assertTag($inputTag, $input);
		
		$this->Base->Html->inputPrefix = 'test_prefix';
		$this->Base->Html->data(array(
			'myname' => 'test value',
			'other' => 'value'
		));
		$input = $this->Base->Html->input('myname', array('label' => false, 'type' => 'password'));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'name' => 'test_prefix[myname]',
					'id' => 'testprefixmyname',
					'type' => 'password',
					'value' => 'test value'
				)
			)
		);
		$this->assertTag($inputTag, $input);
		
		$this->Base->Html->inputPrefix = 'test_prefix';
		$this->Base->Html->data(array(
			'myname' => 'test value',
			'other' => 'value'
		));
		$input = $this->Base->Html->input('other', array('label' => false, 'type' => 'select', 'options' => array('other' => 12, 'nothing' => 'something')));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'select',
				'attributes' => array(
					'name' => 'test_prefix[other]',
					'id' => 'testprefixother',
				),
				'children' => array(
					'less_than' => 3,
					'greater_than' => 1,
					'only' => array(
						'tag' => 'option'
					)
				)
			)
		);
		$this->assertTag($inputTag, $input);
		
		$input = $this->Base->Html->input('myname', array('label' => false, 'div' => false, 'value' => 'test'));
		$inputTag = array(
			'tag' => 'input',
			'attributes' => array(
				'name' => 'test_prefix[myname]',
				'id' => 'testprefixmyname',
				'type' => 'text',
				'value' => 'test'
			)
		);
		$this->assertTag($inputTag, $input);
		
		$input = $this->Base->Html->input('myname', array('label' => false, 'div' => 'classname', 'value' => 'test'));
		$inputTag = array(
			'tag' => 'div',
			'attributes' => array(
				'class' => 'classname'
			),
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'name' => 'test_prefix[myname]',
					'id' => 'testprefixmyname',
					'type' => 'text',
					'value' => 'test'
				)
			)
		);
		$this->assertTag($inputTag, $input);
	}
	
	function testInputExtra() {
		$input = $this->Base->Html->input('myname', array('before' => '<div>', 'between' => '</div>', 'after' => '<p>test</p>'));
		$labelTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'div',
				'child' => array(
					'tag' => 'label',
					'attributes' => array(
						'class' => 'description',
						'for' => 'myname'
					),
					'content' => 'myname'
				)
			)
		);
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'name' => 'myname',
					'id' => 'myname',
					'type' => 'text'
				)
			)
		);
		$paraTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'p',
				'content' => 'test'
			)
		);
		$this->assertTag($labelTag, $input);
		$this->assertTag($inputTag, $input);
		$this->assertTag($paraTag, $input);
	}
	
	function testInputTextarea() {
		$input = $this->Base->Html->input('myname', array('type' => 'textarea', 'class' => 'classname'));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'textarea',
				'attributes' => array(
					'name' => 'myname',
					'id' => 'myname',
					'class' => 'classname'
				)
			)
		);
		$this->assertTag($inputTag, $input);
		
		$input = $this->Base->Html->input('myname', array('type' => 'textarea', 'value' => 'something', 'class' => 'classname'));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'textarea',
				'content' => 'something',
				'attributes' => array(
					'name' => 'myname',
					'id' => 'myname',
					'class' => 'classname'
				)
			)
		);
		$this->assertTag($inputTag, $input);
	}
	
	function testCheckbox() {
		$input = $this->Base->Html->input('name', array('type' => 'checkbox', 'value' => 'something'));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'type' => 'checkbox',
					'name' => 'name',
					'value' => 'something',
					'id' => 'name'
				)
			)
		);
		$this->assertTag($inputTag, $input);
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'type' => 'hidden',
					'name' => 'name',
					'value' => '0',
					'id' => 'namehidden'
				)
			)
		);
		$this->assertTag($inputTag, $input);
		
		$this->Base->Html->data(array(
			'name' => 1
		));
		$input = $this->Base->Html->input('name', array('type' => 'checkbox'));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'type' => 'checkbox',
					'name' => 'name',
					'value' => 1,
					'id' => 'name',
					'checked' => 'checked'
				)
			)
		);
		$this->assertTag($inputTag, $input);
		
		$this->Base->Html->inputPrefix = 'meta';
		$input = $this->Base->Html->input('name', array('type' => 'checkbox', 'value' => 'nothing'));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'type' => 'checkbox',
					'name' => 'meta[name]',
					'value' => 'nothing',
					'id' => 'regexp:/[a-z0-9]/i'
				)
			)
		);
		$this->assertTag($inputTag, $input);
		
		$this->Base->Html->data(array(
			'name1' => 'nothing'
		));
		$this->Base->Html->inputPrefix = 'meta';
		$input = $this->Base->Html->input('name1', array('type' => 'checkbox', 'value' => 'nothing'));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'type' => 'checkbox',
					'name' => 'meta[name1]',
					'value' => 'nothing',
					'id' => 'regexp:/[a-z0-9]/i',
					'checked' => 'checked'
				)
			)
		);
		$this->assertTag($inputTag, $input);
	}
	
	function testSubmit() {
		$input = $this->Base->Html->input('Go!', array('type' => 'submit', 'value' => 'thiswillgoaway'));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'type' => 'submit',
					'name' => 'Go!',
					'id' => 'regexp:/[a-z0-9]/i',
					'value' => 'Go!'
				)
			)
		);
		$this->assertTag($inputTag, $input);
	}
	
	function testHidden() {
		$input = $this->Base->Html->input('hideme', array('type' => 'hidden', 'value' => 'myval'));
		$inputTag = array(
			'tag' => 'input',
			'attributes' => array(
				'type' => 'hidden',
				'name' => 'hideme',
				'value' => 'myval',
				'id' => 'regexp:/[a-z0-9]/i',
			)
		);
		$this->assertTag($inputTag, $input);
	}
	
	function testRadio() {
		$this->Base->Html->data(array(
			'name' => 'nothing'
		));
		$input = $this->Base->Html->input('name', array('type' => 'radio', 'value' => 'something'));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'type' => 'radio',
					'name' => 'name',
					'value' => 'something',
					'id' => 'name'
				)
			)
		);
		$this->assertTag($inputTag, $input);
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'label',
				'attributes' => array(
					'class' => 'description',
					'for' => 'name'
				),
				'content' => 'name'
			)
		);
		$this->assertTag($inputTag, $input);
		
		$input = $this->Base->Html->input('name', array('type' => 'radio', 'value' => 'nothing'));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'type' => 'radio',
					'name' => 'name',
					'value' => 'nothing',
					'id' => 'name',
					'checked' => 'checked'
				)
			)
		);
		$this->assertTag($inputTag, $input);
	}
	
	function testTaxInput() {
		$this->Base->Html->data(array(
			'tax_input' => array(
				'something' => 'nothing'
			)
		));
		$input = $this->Base->Html->input('tax_input[something]', array('label' => false));
		$inputTag = array(
			'tag' => 'div',
			'child' => array(
				'tag' => 'input',
				'attributes' => array(
					'name' => 'tax_input[something]',
					'id' => 'taxinputsomething',
					'type' => 'text',
					'value' => 'nothing'
				)
			)
		);
		$this->assertTag($inputTag, $input);
	}
}