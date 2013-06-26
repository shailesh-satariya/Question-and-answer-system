<?php
/**
 * PostFixture
 *
 */
class PostFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'related_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'public_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'content' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'views' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20),
		'votes' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fulltext_index' => array('column' => array('title', 'content'), 'type' => 'fulltext')
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'type' => 'question',
			'related_id' => '0',
			'public_key' => '517e80423cadc',
			'title' => 'Test Question 2',
			'content' => 'Test Question 2',
			'status' => 'closed',
			'created' => '2013-04-29 16:14:27',
			'modified' => '2013-05-20 20:15:19',
			'user_id' => '2',
			'views' => '287',
			'votes' => '1'
		),
		array(
			'id' => '2',
			'type' => 'answer',
			'related_id' => '1',
			'public_key' => '517ee7be84e12',
			'title' => '',
			'content' => 'Test Answer 1',
			'status' => 'open',
			'created' => '2013-04-29 16:34:27',
			'modified' => '2013-05-01 16:12:17',
			'user_id' => '1',
			'views' => '0',
			'votes' => '1'
		),
		array(
			'id' => '3',
			'type' => 'answer',
			'related_id' => '1',
			'public_key' => '517ee7be84e13',
			'title' => '',
			'content' => '<p><strong>Test Answer 3</strong></p>',
			'status' => 'open',
			'created' => '2013-04-29 16:44:27',
			'modified' => '2013-05-20 20:12:26',
			'user_id' => '1',
			'views' => '0',
			'votes' => '0'
		),
		array(
			'id' => '22',
			'type' => 'answer',
			'related_id' => '1',
			'public_key' => '517ee7be84e14',
			'title' => '',
			'content' => 'new post 3',
			'status' => 'open',
			'created' => '2013-04-29 23:35:58',
			'modified' => '2013-05-03 13:12:37',
			'user_id' => '2',
			'views' => '3',
			'votes' => '1'
		),
		array(
			'id' => '23',
			'type' => 'answer',
			'related_id' => '1',
			'public_key' => '517eec6d14991',
			'title' => '',
			'content' => 'new post 4',
			'status' => 'open',
			'created' => '2013-04-29 23:55:57',
			'modified' => '2013-05-03 18:22:30',
			'user_id' => '2',
			'views' => '0',
			'votes' => '1'
		),
		array(
			'id' => '24',
			'type' => 'answer',
			'related_id' => '1',
			'public_key' => '517eecc948614',
			'title' => '',
			'content' => 'new post',
			'status' => 'correct',
			'created' => '2013-04-29 23:57:29',
			'modified' => '2013-05-06 20:31:17',
			'user_id' => '2',
			'views' => '0',
			'votes' => '0'
		),
		array(
			'id' => '25',
			'type' => 'answer',
			'related_id' => '1',
			'public_key' => '517eecdfd13c5',
			'title' => '',
			'content' => '<p><strong>New Answer</strong></p>',
			'status' => 'open',
			'created' => '2013-04-29 23:57:51',
			'modified' => '2013-05-04 20:56:06',
			'user_id' => '2',
			'views' => '0',
			'votes' => '0'
		),
		array(
			'id' => '26',
			'type' => 'question',
			'related_id' => '0',
			'public_key' => '517eed00a7e52',
			'title' => 'Test Question 2',
			'content' => 'This question is related to java and php',
			'status' => 'closed',
			'created' => '2013-04-29 23:58:25',
			'modified' => '2013-05-12 22:40:36',
			'user_id' => '2',
			'views' => '56',
			'votes' => '0'
		),
		array(
			'id' => '27',
			'type' => 'question',
			'related_id' => '0',
			'public_key' => '517eed1c21e84',
			'title' => 'Test Question 2',
			'content' => 'This question is related to java and php',
			'status' => 'open',
			'created' => '2013-04-29 23:58:53',
			'modified' => '2013-04-29 23:58:53',
			'user_id' => '2',
			'views' => '0',
			'votes' => '0'
		),
		array(
			'id' => '28',
			'type' => 'question',
			'related_id' => '0',
			'public_key' => '517eed4bbcd39',
			'title' => 'Test Question 2',
			'content' => '<p>This question is related to myysql and php</p>',
			'status' => 'closed',
			'created' => '2013-04-29 23:59:40',
			'modified' => '2013-05-10 15:09:31',
			'user_id' => '2',
			'views' => '16',
			'votes' => '0'
		),
	);

}
