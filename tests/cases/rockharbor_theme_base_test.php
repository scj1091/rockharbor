<?php
/**
 * TestTheme for access to protected vars
 */
class TestTheme extends RockharborThemeBase {
	
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
	
}

class RockharborThemeBaseTest extends PHPUnit_Framework_TestCase {
	
	function setUp() {
		$this->Base = new TestTheme();
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
	
}