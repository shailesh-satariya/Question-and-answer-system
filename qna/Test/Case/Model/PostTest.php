<?php
App::uses('Post', 'Model');

/**
 * Post Test Case
 *
 */
class PostTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.post',
		'app.user',
		'app.vote',
		'app.answer',
		'app.tag',
		'app.post_tag'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Post = ClassRegistry::init('Post');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Post);

		parent::tearDown();
	}

/**
 * testCustomSearch method
 *
 * @return void
 */
	public function testCustomSearch() {
	}

/**
 * testCustomSearchCount method
 *
 * @return void
 */
	public function testCustomSearchCount() {
	}

}
