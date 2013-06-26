<?php
/**
 * Post Model
 *
 * @property Post $Post
 * @property Vote $Vote
 */
class Answer extends AppModel {
	
	/**
	  * Model Name
	  *
	  * @var string
	  */
	var $name = 'Answer';
	
	/**
	  * Database table name
	  *
	  * @var string
	  */
	var $useTable = 'posts';
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	/**
 	 * belongsTo associations
     *
 	 * @var array
 	 */
	var $belongsTo = array(
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'fields' => array('User.username', 'User.public_key')
			)
		);
	
}
?>