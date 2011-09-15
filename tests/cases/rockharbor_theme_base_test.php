<?php
/**
 * TestTheme for access to protected vars
 */
class TestTheme extends RockharborThemeBase {
	
	public $_action = false;
	
	public $themeOptions = array(
		'slug' => 'testtheme',
		
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
	
	public function action() {
		$this->_action = true;
	}
	
}

class RockharborThemeBaseTest extends PHPUnit_Framework_TestCase {
	
	function setUp() {
		$this->Base = new TestTheme();
	}
	
	function testIsChildTheme() {
		$base = new RockharborThemeBase();
		$this->assertFalse($base->isChildTheme());
		
		$this->Base->setTestPaths();
		$this->assertTrue($this->Base->isChildTheme());
	}
	
	function testAction() {
		$_POST['action'] = 'action';
		$base = new TestTheme();
		$this->assertFalse($base->_action);
		
		$base->allowedActions = array('action');
		$base->__construct();
		$this->assertTrue($base->_action);
	}
	
	function testEmail() {
		$_POST = array(
			'action' => 'email',
			'type' => 'story',
			'field' => 'value'
		);
		$name = $this->Base->info('name');
		$theme = $this->getMock('TestTheme', array('_mail', 'info', 'options'), array(), 'MockTestTheme', false);
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
		$results = $theme->email();
		$expected = array(
			'to' => 'jharris@rockharbor.org,jeremy@42pixels.com',
			'subject' => '['.$name.'] Story Email',
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
		$content = $this->Base->render('test', false);
		$this->assertEquals($content, 'child content');
		$vars = $this->Base->getVars();
		$this->assertEquals($vars['var'], 'content');
		
		$content = $this->Base->render('test');
		$vars = $this->Base->getVars();
		$this->assertEquals($vars, array());
		
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
	
	function testContent() {
		$result = $this->Base->content('<p>A misclosed tag with an image: <img src="someimage.png" height="100000000" width="80" />');
		$tags = array(
			'tag' => 'p',
			'child' => array(
				'tag' => 'img',
				'attributes' => array(
					'src' => 'someimage.png'
				)
			)
		);
		$this->assertTag($tags, $result);
		
		$result = $this->Base->content('test with no html');
		$tags = array(
			'tag' => 'p',
			'content' => 'test with no html'
		);
		$this->assertTag($tags, $result);
		
		$result = $this->Base->content('');
		$this->assertEmpty($result);
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