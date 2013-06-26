<?php
App::uses('PostTag', 'Model');

/**
 * PostTag Test Case
 *
 */
class PostTagTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.post_tag'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->PostTag = ClassRegistry::init('PostTag');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->PostTag);

		parent::tearDown();
	}

}
