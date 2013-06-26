<?php
App::uses('User', 'Model');

/**
 * User Test Case
 *
 */
class UserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user',
		'app.post',
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
		$this->User = ClassRegistry::init('User');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->User);

		parent::tearDown();
	}

/**
 * testMatchPasswords method
 *
 * @return void
 */
	public function testMatchPasswords() {
	}

/**
 * testAdminCheck method
 *
 * @return void
 */
	public function testAdminCheck() {
	}

/**
 * testFindByApiKey method
 *
 * @return void
 */
	public function testFindByApiKey() {
	}

}
