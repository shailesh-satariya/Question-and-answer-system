<?php
App::import('Controller', 'Post');

/**
 * PostsController Test Case
 *
 */
class PostsControllerTest extends ControllerTestCase {

	public $components = 'Auth';

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
 * testView method
 *
 * @return void
 */
	public function testView() {
		$result = $this->testAction('/posts/view/517eed00a7e52');
        debug($result);
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAsk() {
		
		$data = array(
            'Post' => array(
            	'user_id' => '1',
                'title' => 'New Tilte',
                'content' => 'New Content',
                'tag' => 'newtag',
            ),
        );
        $result = $this->testAction(
            '/posts/add',
            array('data' => $data, 'method' => 'post')
        );
        debug($result);
	}
	
	

/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
	}

/**
 * testDisplay method
 *
 * @return void
 */
	public function testDisplay() {
		
	}


}
