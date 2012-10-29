<?php
/**
 * TestTheme for access to protected vars
 */
class TestTheme extends RockharborThemeBase {
	
	public $themeOptions = array(
		'slug' => 'testtheme',
		'short_name' => 'test theme'
	);
	
	public function setTestPaths() {
		$testPath = dirname(dirname(__FILE__)).DS;
		$this->themePath = $testPath.'test_child_theme';
		$this->basePath = $testPath.'test_parent_theme';
	}
	
	public function getVars() {
		return $this->_vars;
	}
	
	public function clearVars() {
		$this->_vars = array();
	}
	
	public function v($var, $value) {
		$this->{$var} = $value;
	}
	
}

class RockharborThemeBaseTest extends PHPUnit_Framework_TestCase {
	
	function setUp() {
		$this->Base = new TestTheme();
	}
	
	function testSupports() {
		$this->assertFalse($this->Base->supports('anything'));
		
		$this->Base->themeOptions['supports'] = array('staff');
		$this->assertTrue($this->Base->supports('staff'));
	}
	
	function testIsChildTheme() {
		$base = new RockharborThemeBase();
		$this->assertFalse($base->isChildTheme());
		
		$this->Base->setTestPaths();
		$this->assertTrue($this->Base->isChildTheme());
	}
	
	function testEmail() {
		$_POST = array(
			'action' => 'email',
			'type' => 'story',
			'field' => 'value'
		);
		$theme = $this->getMock('TestTheme', array('_mail', 'info', 'options'), array(), 'MockTestTheme', false);
		$html = $this->getMock('HtmlHelper', array('validateCaptcha'), array(), 'MockHtmlHelper');
		$html->expects($this->any())
			->method('validateCaptcha')
			->will($this->returnValue(true));
		$theme->expects($this->any())
			->method('info')
			->with($this->stringContains('_email'))
			->will($this->returnValue('noreply@rockharbor.org'));
		$theme->expects($this->any())
			->method('options')
			->will($this->returnValue('jharris@rockharbor.org,jeremy@42pixels.com'));
		$theme->expects($this->any())
			->method('_mail')
			->will($this->returnValue(true));
		$theme->__construct();
		$theme->Html = $html;
		$theme->v('name', 'Test Theme');
		$results = $theme->email();
		$expected = array(
			'to' => 'jharris@rockharbor.org,jeremy@42pixels.com',
			'subject' => '[Test Theme] Story Email',
			'body' => '<h1>Story Email</h1><table><tr><td><strong>field</strong></td><td>&nbsp;&nbsp;</td><td>value</td></tr></table>',
			'headers' => "From: noreply@rockharbor.org\r\nX-Mailer: PHP/".phpversion()."\r\nContent-type: text/html; charset=utf-8"
		);
		$this->assertEquals($expected, $results);
	}
	
	function testInfo() {
		$info = $this->Base->info();
		$this->assertRegExp('/rockharbor/', $info['base_path']);
		
		$url = $this->Base->info('base_url');
		$this->assertRegExp('/rockharbor/', $url);
		
		$var = $this->Base->info('short_name');
		$this->assertEquals('test theme', $var);
		
		$var = $this->Base->info('missing_var');
		$this->assertNull($var);
	}
	
	function testSet() {
		$this->Base->set('test', 'something');
		$vars = $this->Base->getVars();
		$this->assertEquals($vars['test'], 'something');
		
		$this->Base->set(array(
			'again' => 'nothing',
			'othervar' => 'lack of creativity'
		));
		$expected = array(
			'test' => 'something',
			'again' => 'nothing',
			'othervar' => 'lack of creativity'
		);
		$this->assertEquals($this->Base->getVars(), $expected);
	}
	
	function testRender() {
		$this->Base->setTestPaths();
		
		$this->Base->set('var', 'content');
		$content = $this->Base->render('test');
		$this->assertEquals($content, 'child content');
		$vars = $this->Base->getVars();
		$this->assertEquals($vars['var'], 'content');
		
		$content = $this->Base->render('test2');
		$this->assertEquals($content, 'content or something');
		
		$content = $this->Base->render('theme_name');
		$this->assertEquals($content, $this->Base->info('name'));
	}
	
	function testWrapAttachment() {
		$result = $this->Base->wrapAttachment('', array(), '<img src="someimage.png" />');
		$tags = array(
			'tag' => 'div',
			'attributes' => array(
				'align' => 'alignnone',
				'id' => 'regexp:/attachment_[a-z0-9]+/i'
			),
			'child' => array(
				'tag' => 'img',
				'attributes' => array(
					'src' => 'someimage.png'
				)
			)
		);
		$this->assertTag($tags, $result);
	}
	
	function testOptions() {
		delete_option($this->Base->themeOptions['slug'].'_options');
		
		$all = $this->Base->options();
		$this->assertTrue(is_array($all));
		
		// missing option
		$val = $this->Base->options('someoption');
		$this->assertNull($val);
		
		// set
		$val = $this->Base->options('someoption', 'somevalue');
		$this->assertEquals($val, 'somevalue');
		
		// get
		$val = $this->Base->options('someoption');
		$this->assertEquals($val, 'somevalue');
		
		// delete
		$this->Base->options('someoption', null);
		$val = $this->Base->options('someoption');
		$this->assertNull($val);
		$options = get_option($this->Base->themeOptions['slug'].'_options');
		$this->assertEmpty($options);
		
		delete_option($this->Base->themeOptions['slug'].'_options');
	}
	
}