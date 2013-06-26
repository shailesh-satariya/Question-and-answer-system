<?php
App::uses('AppModel', 'Model');
/**
 * Post Model
 *
 * @property User $User
 * @property PostTag $PostTag
 * @property Vote $Vote
 */
class Post extends AppModel {
	
	var $actsAs = array('Tag');

	 /**
	  * Display field
	  *
	  * @var string
	  */
	public $displayField = 'title';

 	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'content' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	/**
 	 * belongsTo associations
     *
 	 * @var array
 	 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
 	/**
	 * hasMany associations
	 *
	 * @var array
	 */
 	public $hasAndBelongsToMany = array(
		'Tag' => array(
			'className' => 'Tag',
			'joinTable' => 'post_tags',
			'foreignKey' => 'post_id',
			'associationForeignKey' => 'tag_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	public $hasMany = array(
		'Vote' => array(
			'className' => 'Vote',
			'foreignKey' => 'post_id',
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
		 'Answer' => array(
            'className'     => 'Answer',
            'foreignKey'    => 'related_id',
            'conditions'    => array('Answer.type' => 'answer'),
            'dependent'=> true
        )
	);
	
	/**
	 * beforeSave method (Cakephp inbuilt merhod)
	 * used to perform tasks requred before saving the questions and answers 
	 * 
	 * @param array $options
	 * @return boolean
	 */
	public function beforeSave($options = array()){
   		foreach (array_keys($this->hasAndBelongsToMany) as $model) {
      		if(isset($this->data[$this->name][$model])) {
       			$this->data[$model][$model] = $this->data[$this->name][$model];
        		unset($this->data[$this->name][$model]);
      		}
    	}
    	return true;
 	 }
	
	/**
	 * customSearch method
	 * used to perform customised search 
	 * 
	 * @param string $type, integer $page, string $search
	 * @return void
	 */
	public function customSearch($type, $page, $search){
		
        $now = date("Y-m-d H:i:s", time());
		$week = date("Y-m-d H:i:s", time() - (7*24*60*60));
		$month = date("Y-m-d H:i:s", time() - (30*24*60*60));
		$rpp = 15;
        $record = ($page * $rpp) - $rpp;


    	if($search == 'no') {
        	if($type == 'recent') {
				return $this->find('all', array(
				'contain' => array(
                    	'User','Tag.tag','Answer.id'
                ),
                'conditions' => array(
                    'Post.type' => 'question'
				),
				'order' => 'Post.created DESC',
				'fields' => array(
					'Post.title', 'Post.views', 'Post.public_key', 'Post.created', 'Post.votes',  
                    'User.username', 'User.public_key', 'User.image'
					),
				'limit' => $record . ',' . $rpp
			));
        }elseif($type == 'unsolved') {
			return $this->find(
                'all', array(
				    'contain' => array(
                        'User', 'Tag.tag', 'Answer.id'
                    ),
				'conditions' => array(
                    'Post.type' => 'question',
                    'Post.status' => 'open'
				),
				'order' => 'Post.created DESC',
				'fields' => array(
					'Post.title', 'Post.views', 'Post.public_key', 'Post.created',  'Post.votes', 
					'User.username', 'User.public_key', 'User.image'
					),
				'limit' => $record . ',' . $rpp
			));
		}elseif($type == 'solved') {
            return $this->find(
                'all', array(
				    'contain' => array(
                        'User', 'Tag.tag', 'Answer.id'
                    ),
				'conditions' => array(
                    'Post.type' => 'question',
                    'Post.status' => 'closed'
				),
				'order' => 'Post.created DESC',
				'fields' => array(
					'Post.title', 'Post.views', 'Post.public_key', 'Post.created',  'Post.votes', 
					'User.username', 'User.public_key', 'User.image'
					),
				'limit' => $record . ',' . $rpp
			));
        }elseif($type == 'hot') {
            return $this->find(
                'all', array(
				    'contain' => array(
                        'User', 'Tag.tag', 'Answer.id'
                    ),
				'conditions' => array(
                    'Post.type' => 'question'
				),
				'order' => 'Post.views DESC',
				'fields' => array(
					'Post.title', 'Post.views', 'Post.public_key', 'Post.created',  'Post.votes', 
					'User.username', 'User.public_key', 'User.image'
					),
				'limit' => $record . ',' . $rpp
			));
        }elseif($type == 'week') {
            return $this->find(
                'all', array(
				    'contain' => array(
                        'User', 'Tag.tag', 'Answer.id'
                    ),
				'conditions' => array(
                    'Post.type' => 'question',
                    'Post.created BETWEEN ? and ?' => array($week, $now)),
				'order' => 'Post.created DESC',
				'fields' => array(
					'Post.title', 'Post.views', 'Post.public_key', 'Post.created',  'Post.votes', 
					'User.username', 'User.public_key', 'User.image'
					),
				'limit' => $record . ',' . $rpp
			));
        }elseif($type == 'month') {
            return $this->find(
                'all', array(
				    'contain' => array(
                        'User', 'Tag.tag', 'Answer.id'
                    ),
				'conditions' => array(
                    'Post.type' => 'question',
                    'Post.created BETWEEN ? and ?' => array($month, $now)
					),
				'order' => 'Post.created DESC',
				'fields' => array(
					'Post.title', 'Post.views', 'Post.public_key', 'Post.created',  'Post.votes', 
					'User.username', 'User.public_key', 'User.image'
					),
				'limit' => $record . ',' . $rpp
			));
        }
    } else {
            $escapedNeedle = $this->getDataSource()->value($type['needle']);

            return $this->find(
                'all', array(
                    'conditions' => array(
                        "MATCH(Post.content, Post.title) against (" . $escapedNeedle . " IN BOOLEAN MODE)",
                        'Post.type' => 'question'
					),
                    'contain' => array(
                        'User', 'Tag.tag', 'Answer.id'
                    ),
                    'fields' => array(
						"match(Post.content, Post.title) against(" . $escapedNeedle . ") as relevance",
                        'Post.title', 'Post.views',  'Post.public_key', 'Post.created', 'Post.votes', 
                        'User.username', 'User.public_key', 'User.image'
						),
                    'order' => 'relevance DESC',
                    'limit' => $record . ',' . $rpp)
            );
        }
	}

	/**
	 * customSearchCount method
	 * used to count customised search results 
	 * 
	 * @param string $type, string $search
	 * @return void
	 */
	public function customSearchCount($type, $search) {
        $now = date("Y-m-d H:i:s", time());
		$week = date("Y-m-d H:i:s", time() - (7*24*60*60));
		$month = date("Y-m-d H:i:s", time() - (30*24*60*60));


        if($search == 'no') {
            if($type == 'recent' || $type == 'hot') {
                return $this->find(
                    'all', array(
                        'fields' => 'COUNT(Post.title) as count',
                        'conditions' => array(
                            'Post.type' => 'question'
						)
					)
                );
            }elseif($type == 'unsolved') {
                return $this->find(
                    'all', array(
                        'fields' => 'COUNT(Post.title) as count',
                        'conditions' => array(
                            'Post.type' => 'question',
                            'Post.status' => 'open'
							)
						)
                );
            }elseif($type == 'solved') {
                return $this->find(
                    'all', array(
                        'fields' => 'COUNT(Post.title) as count',
                        'conditions' => array(
                            'Post.type' => 'question',
                            'Post.status' => 'closed',
                            )
						)
                );
            }elseif($type == 'week') {
                return $this->find(
                    'all', array(
                        'fields' => 'COUNT(Post.title) as count',
                        'conditions' => array(
                            'Post.type' => 'question',
                            'Post.created BETWEEN ? and ?' => array($week, $now),
                         )
					)
                );
            }elseif($type == 'month') {
                return $this->find(
                    'all', array(
                        'fields' => 'COUNT(Post.title) as count',
                        'conditions' => array(
                            'Post.type' => 'question',
                            'Post.created BETWEEN ? and ?' => array($month, $now),
                		)
					)
                );
            }
        }else {
            return $this->find(
                'all', array(
                    'fields' => 'COUNT(Post.title) as count',
                    'conditions' => array(
                        'Post.type' => 'question',
                        "match(content, title) against('" . $type['needle'] . "')",
                     )
				)
            );
        }
    }

}
