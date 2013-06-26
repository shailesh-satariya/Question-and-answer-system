<?php
App::uses('AppModel', 'Model');
/**
 * PostTag Model
 *
 * @property Post $Post
 * @property Tag $Tag
 */
class PostTag extends AppModel {

	/**
	 * Primary key field
	 *
	 * @var string
	 */
	var $name = 'PostTag';
	
	/**
	 * Database table name
	 *
	 * @var string
	 */
	var $useTable = 'post_tags';
	
}
