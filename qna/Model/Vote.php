<?php
App::uses('AppModel', 'Model');
/**
 * Vote Model
 *
 * @property Post $Post
 * @property User $User
 */
class Vote extends AppModel {
	

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */

	public $belongsTo = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'post_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	/**
	 * castVote method
	 * used to vote the question or answer
	 * 
	 * @param integer $user_id, string $public_key, $string type
	 * @return void
	 */
	public function castVote($user_id, $public_key, $type) {
        /*
            Check if the user voted for this post.
            If they did, setup for an easy redirect. 
        */
            
        $this->Post->recursive = -1;
        $this->User->recursive = -1;
        $post_info = $this->Post->find(
            'first', array(
                'conditions' => array(
                    'Post.public_key' => $public_key
                ),
                'fields' => array(
                    'Post.id', 'Post.votes', 'Post.user_id'
                )
            )
        );
        $existing_vote = $this->find(
            'first', array(
                'conditions' => array(
                    'Vote.user_id' => $user_id,
                    'Vote.post_id' => $post_info['Post']['id'],
                    'Vote.type' => $type
                )
            )
        );
        $one_vote_up = $this->find(
            'first', array(
                'conditions' => array(
                    'Vote.type' => 'up',
                    'Vote.user_id' => $user_id,
                    'Vote.post_id' => $post_info['Post']['id']
                ),
                'fields' => array(
                    'Vote.id'
                )
            )
        );
        $one_vote_down = $this->find(
            'first', array(
                'conditions' => array(
                    'Vote.type' => 'down',
                    'Vote.user_id' => $user_id,
                    'Vote.post_id' => $post_info['Post']['id']
                ),
                'fields' => array(
                    'Vote.id'
                )
            )
        );
        if(!empty($existing_vote)) {
            return 'exists';
        }else{
            $this->create();
            $data['Vote']['user_id'] = $user_id;
            $data['Vote']['post_id'] = $post_info['Post']['id'];
            $data['Vote']['type'] = $type;
            
            if($type == 'up') {
                if(empty($one_vote_down)) {
                    $vote = array(
                        'id' => $post_info['Post']['id'],
                        'votes' => $post_info['Post']['votes'] + 1
                    );
                }else {
                    $vote = array(
                        'id' => $post_info['Post']['id'],
                        'votes' => $post_info['Post']['votes'] + 2
                    );
					$data['Vote']['id'] = $one_vote_down['Vote']['id'];
                }
            }elseif($type == 'down') {
                if(empty($one_vote_up)) {
                    $vote = array(
                        'id' => $post_info['Post']['id'],
                        'votes' => $post_info['Post']['votes'] - 1
                    );
                }else {
                    $vote = array(
                        'id' => $post_info['Post']['id'],
                        'votes' => $post_info['Post']['votes'] - 2
                    );
					$data['Vote']['id'] = $one_vote_up['Vote']['id'];
                }
                
            }
			$this->save($data);
            $this->Post->save($vote);
        }
    }
	
}
