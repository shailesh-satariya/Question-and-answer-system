<?php
App::uses('Vote', 'Model');

/**
 * Vote Test Case
 *
 */
class VoteTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.vote',
		'app.post',
		'app.user',
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
		$this->Vote = ClassRegistry::init('Vote');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Vote);

		parent::tearDown();
	}

/**
 * testCastVote method
 *
 * @return void
 */
	public function testCastVote() {
	}

}
