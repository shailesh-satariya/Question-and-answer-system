<?php
App::uses('AppController', 'Controller');
/**
 * Votes Controller
 *
 * @property Vote $Vote
 */
class VotesController extends AppController {
		
	/**
	 * This controller uses Post, Vote, User models
	 *
	 * @var array
	 */
	var $uses = array('Post', 'Vote', 'User');
	
	/**
	 * beforeFilter method (Cakephp in-built method)
	 *
	 * @return void
	 */
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	/**
	 * vote method
	 * used to vote the question or answer
	 * 
	 * @param string $public_key, $string type
	 * @return void
	 */
    function vote($public_key, $type) {
        $this->Post->recursive = -1;
        $title = $this->Post->find(
            'first', array(
                'conditions' => array(
                    'Post.public_key' => $public_key
                ),
                'fields' => array('Post.title', 'Post.type', 'Post.related_id', 'Post.public_key',
                                  'Post.user_id')
            )
        );
        $check = $title;
        if($title['Post']['type'] == 'answer') {
            $title = $this->Post->find(
                'first', array(
                    'conditions' => array(
                        'Post.id' => $title['Post']['related_id']
                    ),
                    'fields' => array('Post.title', 'Post.public_key', 'Post.user_id')
                )
            );
        }
        if(!isset($_SESSION['Auth']['User']['id'])) {
                $this->Session->setFlash('You must be logged in to do that!');
                $this->redirect('/questions/' . $title['Post']['public_key'] . '/' . $title['Post']['title']);
        }
        if($check['Post']['user_id'] == $_SESSION['Auth']['User']['id']) {
            $this->Session->setFlash('You cannot vote for yourself.');
            $this->redirect('/questions/' . $title['Post']['public_key'] . '/' . $title['Post']['title']);
        }

    	$vote = $this->Vote->castVote($_SESSION['Auth']['User']['id'], $public_key, $type);
        if($vote == 'exists') {
            $this->Session->setFlash('You have already voted for that!');
            $this->redirect('/questions/' . $title['Post']['public_key'] . '/' . str_replace(' ', '-',  $title['Post']['title']));
        }else {
            $this->Session->setFlash('Voted successfully!');
            $this->redirect('/questions/' . $title['Post']['public_key'] . '/' .str_replace(' ', '-',  $title['Post']['title']));
        }
    }
}
