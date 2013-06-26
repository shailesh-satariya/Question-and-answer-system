<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
/**
 * User Model
 *
 * @property Post $Post
 * @property Vote $Vote
 */
class User extends AppModel {

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your username.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'This username has already been taken.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'between' => array(
				'rule' => array('between', 5, 20),
				'message' => 'The username must be between 6 and 20 characters.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'This email has already been in use.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'between' => array(
				'rule' => array('between', 6, 20),
				'message' => 'The password must be between 6 and 20 characters.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'matchPasswords' => array(
				'rule' => 'matchPasswords',
				'message' => 'The passwords do not match.',
			),
		),
		'password_confirmation'=>array(
		    'Not empty'=>array(
		        'rule'=>'notEmpty',
		        'message'=>'Please confirm your password'
		    )
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Vote' => array(
			'className' => 'Vote',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	/**
	 * ask matchPasswords
	 * used check whether password matches to the confirm password
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function matchPasswords($data) {
	    if ($data['password'] == $this->data['User']['password_confirmation']) {
	        return true;
	    }
	    $this->invalidate('password_confirmation', 'Your passwords do not match');
	    return false;
	}
	
	/**
	 * beforeSave method (Cakephp inbuilt merhod)
	 * used to perform tasks requred before saving the user data 
	 * 
	 * @param array $options
	 * @return boolean
	 */
	public function beforeSave( $options = array()) {
	    if (isset($this->data['User']['password'])) {
	        $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
	    }
	    return true;
	}

	/**
	 * adminCheck method
	 * used to check whether user is admin or not 
	 * 
	 * @param integer $user_id
	 * @return boolean
	 */
	 public function adminCheck($user_id) {
        $rights = $this->find(
            'first', array(
                'conditions' => array('User.id' => $user_id),
                'fields' => array('User.role')
            )

        );
        if($rights['User']['role'] == 'admin') {
            return true;
        }
		return false;
    }
	
	/**
	 * findByApiKey method
	 * used to find user data from api key 
	 * 
	 * @param string $api_key, array $fields
	 * @return array
	 */
	public function findByApiKey($api_key = '', $fields = null) {
		$this->recursive = -1;
		return $this->find(
       		'first', array(
				'conditions' => array(
                    'User.api_key' => $api_key,
				),
				'fields' => $fields
			)
		);
	}

}
