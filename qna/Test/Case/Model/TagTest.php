<?php
App::uses('Tag', 'Model');

/**
 * Tag Test Case
 *
 */
class TagTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.tag',
		'app.post',
		'app.user',
		'app.vote',
		'app.answer',
		'app.post_tag'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Tag = ClassRegistry::init('Tag');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Tag);

		parent::tearDown();
	}

/**
 * testGetSuggestions method
 *
 * @return void
 */
	public function testGetSuggestions() {
	}

/**
 * testTagSearch method
 *
 * @return void
 */
	public function testTagSearch() {
	}

}
