<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 * @property Post $Post
 */
class PostsController extends AppController {

	/**
	 * This controller uses Post, Answer, User, Tag, PostTag, Vote models
	 *
	 * @var array
	 */
	public $uses = array('Post', 'Answer', 'User', 'Tag', 'PostTag', 'Vote');
	
	
	/**
	 * This controller uses Auth, Session, Markdownify, Markdown, RequestHandler components
	 *
	 * @var array
	 */
	public $components = array('Auth', 'Session', 'Markdownify', 'Markdown', 'RequestHandler');

	/**
	 * beforeFilter method (Cakephp in-built method)
	 * used for filteration
	 * 
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this -> Auth -> allow('display', 'view', 'api_ask', 'api_index', 'api_view', 'api_answer', 'api_edit', 'api_delete');
		if ($this -> Auth -> loggedIn())
			$this -> Auth -> allow();
		
	}

	/**
	 * view method
	 *
	 * @param string $public_key
	 * @return void
	 */
	public function view($public_key) {
		$this -> Post -> recursive = 2;
		$question = $this -> Post -> findByPublicKey($public_key);
		if (empty($question)) {
			throw new NotFoundException(__('Invalid post'));
		}

		$this -> Post -> recursive = 3;
		$answers = $this -> Answer -> find('all', array('conditions' => array('related_id' => $question['Post']['id'])));

		if (!empty($question)) {
			$views = array('id' => $question['Post']['id'], 'views' => $question['Post']['views'] + 1);
			$this -> Post -> save($views);
		}
		
		$this -> set('title_for_layout', $question['Post']['title']);
		$this -> set('question', $question);
		$this -> set('answers', $answers);
		$this -> set('_serialize', array('question', 'answers'));
	}

	/**
	 * edit method
	 * used to edit questions & answers
	 * 
	 * @param string $public_key
	 * @return void
	 */
	public function edit($public_key) {
		$question = $this -> Post -> findByPublicKey($public_key);
		$this -> set('title_for_layout', $question['Post']['title']);
		$redirect = $question;
		if (empty($redirect['Post']['title'])) {
			$redirect = $this -> Post -> findById($redirect['Post']['related_id']);
		}
		if ($question['Post']['user_id'] != $this -> Auth -> user('id') && !$this -> isAdmin($this -> Auth -> user('id'))) {
			$this -> Session -> setFlash(__('That is not your question to edit.', true), 'error');
			$this -> redirect('/questions/' . $redirect['Post']['public_key'] . '/' . $redirect['Post']['title']);
		}
		if (!empty($question['Post']['title'])) {
			$tags = $this -> PostTag -> find('all', array('conditions' => array('PostTag.post_id' => $question['Post']['id'])));
			$this -> Tag -> recursive = -1;
			foreach ($tags as $key => $value) {
				$tag_names[$key] = $this -> Tag -> find('first', array('conditions' => array('Tag.id' => $tags[$key]['PostTag']['tag_id']), 'fields' => array('Tag.tag')));
				if ($key == 0) {
					$tag_list = $tag_names[$key]['Tag']['tag'];
				} else {
					$tag_list = $tag_list . ', ' . $tag_names[$key]['Tag']['tag'];
				}
			}
			$this -> set('tags', $tag_list);
		}

		if (!empty($this -> request -> data['Post']['tags'])) {
			$this -> Post -> Behaviors -> attach('Tag', array('table_label' => 'tags', 'tags_label' => 'tag', 'separator' => ', '));
		}

		if (!empty($this -> request -> data)) {
			$data = $this -> request -> data;
			$data['Post']['id'] = $question['Post']['id'];
			if ($this -> Post -> save($data)) {
				$this -> redirect('/questions/' . $redirect['Post']['public_key'] . '/' . preg_replace("/[^0-9a-zA-Z-]/", "", str_replace(' ', '-', $redirect['Post']['title'])));
			}
		} else {
			$question['Post']['content'] = $this -> Markdownify -> parseString($question['Post']['content']);
			$this -> set('question', $question);
		}

	}

	/**
	 * ask method
	 * used to ask a question
	 * 
	 * @return void
	 */
	public function ask() {
		if (!empty($this -> request -> data)) {
			if (!empty($user)) {
				$userId = $user['User']['id'];
			} else {
				$userId = $this -> Auth -> user('id');
			}
			$post = $this -> __postSave('question', $userId, $this -> request -> data);
			$this -> redirect('/questions/' . $post['public_key'] . '/' . preg_replace("/[^0-9a-zA-Z-]/", "", preg_replace("/[^0-9a-zA-Z-]/", "", str_replace(' ', '-', $post['title']))));
		}
		$this -> set('title_for_layout', 'Ask a question');
	}
	
	/**
	 * answer method
	 * this method is used to answer a question
	 * 
	 * @param string $public_key
	 * @return void
	 */
	public function answer($public_key) {
		$question = $this -> Post -> findByPublicKey($public_key);
		if (empty($question)) {
			throw new NotFoundException();
		}
		if (!empty($this -> request -> data)) {
			if (!empty($user)) {
				$userId = $user['User']['id'];
			} else {
				$userId = $this -> Auth -> user('id');
			}
			$post = $this -> __postSave('answer', $userId, $this -> request -> data, $question['Post']['id']);
			$this -> redirect('/questions/' . $question['Post']['public_key'] . '/' . preg_replace("/[^0-9a-zA-Z-]/", "", preg_replace("/[^0-9a-zA-Z-]/", "", str_replace(' ', '-', $question['Post']['title']))));
		} else {
			$this -> redirect('/questions/' . $question['Post']['public_key'] . '/' . preg_replace("/[^0-9a-zA-Z-]/", "", preg_replace("/[^0-9a-zA-Z-]/", "", str_replace(' ', '-', $question['Post']['title']))));
		}
	}
	
	/**
	 * __postSave method
	 * used to save questions & answers
	 * 
	 * @param string $type,  integer $userId, array $data, integer $relaredId
	 * @return void
	 */
	public function __postSave($type, $userId, $data, $relatedId = null) {
		$data['Post']['type'] = $type;
		$data['Post']['user_id'] = $userId;
		$data['Post']['public_key'] = uniqid();
		if ($type == 'answer') {
			$data['Post']['related_id'] = $relatedId;
		}
		if (!empty($data['Post']['tags'])) {
			$this -> Post -> Behaviors -> attach('Tag', array('table_label' => 'tags', 'tag_label' => 'tag', 'separator' => ','));
		}

		$data['Post']['content'] = str_replace('<code>', '<code class="prettyprint">', $data['Post']['content']);
		$data['Post']['status'] = 'open';

		if (($res = $this -> Post -> save($data))) {

			$post = $data['Post'];
			$post['id'] = $this -> Post -> id;

			/**
			 * Hack to normalize data.
			 * Note this should be added to the Tag Behavior at some point.
			 * This was but in because the behavior would delete the relations as soon as they put them in.
			 */
			if (!empty($data['Post']['tags'])) {

				$this -> Post -> Behaviors -> detach('Tag');
				$tags = array('id' => $this -> Post -> id, 'tags' => '');

				$this -> Post -> save($tags);
			}

			return $post;
		} else {
			return false;
		}
	}

	/**
	 * display method
	 * used for search & display questions 
	 * 
	 * @param string $type, integer $page
	 * @return void
	 */
	public function display($type = 'recent', $page = 1) {
		$this -> set('title_for_layout', ucwords($type) . ' Questions');
		$this -> Post -> recursive = 1;
		
		if (isset($this -> passedArgs['type'])) {
			$search = $this->passedArgs['search'];
			if ($search == 'yes') {
				$type = array('needle' => $this -> passedArgs['type']);
			} else {
				$type = $this -> passedArgs['type'];
			}
			$page = $this -> passedArgs['page'];
		} elseif (!empty($this -> request -> data['Post'])) {
			$type = $this -> request -> data['Post'];
			$search = 'yes';
		} else {
			$search = 'no';
		}

		if ($page <= 1) {
			$page = 1;
		} else {
			$previous = $page - 1;
			$this -> set('previous', $previous);
		}

		$questions = $this -> Post -> customSearch($type, $page, $search);
		$count = $this -> Post -> customSearchCount($type, $search);
		
		$rpp = 15; //Records Per Page
		
		if ($count['0']['0']['count'] % $rpp == 0) {
			$end_page = $count['0']['0']['count'] / $rpp;
		} else {
			$end_page = floor($count['0']['0']['count'] / $rpp) + 1;
		}

		if (($count['0']['0']['count'] - ($page * $rpp)) > 0) {
			$next = $page + 1;
			$this -> set('next', $next);
		}

		$keywords = array('hot', 'week', 'month', 'recent', 'solved', 'unsolved');
		if (($search == 'no') && (!in_array($type, $keywords))) {
			$this -> Session -> setFlash(__('Invalid search type.', true), 'error');
			$this -> redirect('/');
		}

		if (empty($questions)) {
			if (isset($type['needle'])) {
				$this -> Session -> setFlash(__('No results for', true) . ' "' . $type['needle'] . '"!', 'error');
			} else {
				$this -> Session -> setFlash(__('No results for', true) . ' "' . $type . '"!', 'error');
			}
			if ($this -> Post -> find('count') > 0) {
				$this -> redirect('/');
			}
		}

		if ($search == 'yes') {
			$this -> set('type', $type['needle']);
		} else {
			$this -> set('type', $type);
		}
		$this -> set('questions', $questions);
		$this -> set('_serialize', array('questions'));
		$this -> set('end_page', $end_page);
		$this -> set('current', $page);
		$this -> set('search', $search);
	}
	
	
	/**
	 * markCorrect method
	 * used for mark the answer as correct answer & mark the question as solved question 
	 * 
	 * @param string $public_key
	 * @return void
	 */
	public function markCorrect($public_key) {
		$answer = $this -> Post -> findByPublicKey($public_key);

		/**
		 * Check to make sure the Post is an answer
		 */
		if ($answer['Post']['type'] != 'answer') {
			$this -> Session -> setFlash('You Can not do this');
			$this -> redirect('/');
		}

		$question = $this -> Post -> findById($answer['Post']['related_id']);
		/**
		 * Check to make sure the logged in user is authorized to edit this Post
		 */
		if ($question['Post']['user_id'] != $this -> Auth -> user('id')) {
			$this -> Session -> setFlash('You are not allowed to edit that.');
			$this -> redirect('/questions/' . $question['Post']['public_key'] . '/' . preg_replace("/[^0-9a-zA-Z-]/", "", str_replace(' ', '-', $question['Post']['url_title'])));
		}

		/**
		 * Set the Post as correct, and its question as closed.
		 */
		$quest = array('id' => $question['Post']['id'], 'status' => 'closed');
		$answ = array('id' => $answer['Post']['id'], 'status' => 'correct');

		$this -> Post -> save($answ);
		$this -> Post -> save($quest);
		$this -> redirect('/questions/' . $question['Post']['public_key'] . '/' . preg_replace("/[^0-9a-zA-Z-]/", "", str_replace(' ', '-', $question['Post']['title'])) . '#a_' . $answer['Post']['public_key']);

	}
	
	
	/**
	 * delete method
	 * used to delete questions & answers
	 * Only for admin
	 *  
	 * @param string $public_key
	 * @return void
	 */
	public function delete($public_key) {
		if ($this -> Auth -> user('role') == 'admin') {
			$question = $this -> Post -> findByPublicKey($public_key);
			if (empty($question)) {
				$this -> Session -> setFlash('Question / Answer not found.');
				$this->redirect('/');
			}
			$qid = $question['Post']['related_id'];
			if ($question['Post']['type'] == 'answer') {
				$related_question = $this -> Post -> findById($question['Post']['related_id']);
				$this->Post->delete($question['Post']['id']);
				$this -> Session -> setFlash('Answer is deleted.');
				$this -> redirect('/questions/' . $related_question['Post']['public_key'] . '/' . preg_replace("/[^0-9a-zA-Z-]/", "", str_replace(' ', '-', $related_question['Post']['title'])));
			
			} elseif ($question['Post']['type'] == 'question') {
				$result = $this->Post->deleteAll(array( 'Post.related_id' => $question['Post']['id']));
				$this->PostTag->delete(array( 'post_id' => $question['Post']['id']));
				$this->Post->delete($question['Post']['id']);
            	$this -> Session -> setFlash('Question is deleted.');		
				$this->redirect('/');		
			}
		} else {
			$this -> Session -> setFlash('You are not authorized.');		
			$this->redirect('/');
		}
	}
	
	/**
	 * api_index method
	 * rest method
	 * used for retriving questions
	 * 
	 * @return void
	 */
	public function api_index() {
		$this -> Post -> recursive = 1;
		$this->Post->Behaviors->attach('Containable', array('autoFields' => false));
		$this -> paginate = array(
			'contain' => array('User','Tag.tag', 'Answer.public_key'), 
			'model' => array('Post', 'User', 'Tag'),
			'conditions' => array('type' => "question"),
			'fields' => array('Post.id',  'Post.public_key',  'Post.title',  'Post.content', 'Post.status', 'Post.modified', 'Post.votes', 'Post.views',
									'User.username', 'User.public_key'
			)
		);
		$this -> set('questions', $this -> paginate());
		$this -> set('_serialize', array('questions'));
	}
	
	/**
	 * api_view method
	 * rest method
	 * used for retriving specific question with its all answers
	 * 
	 * @return void
	 */
	public function api_view($public_key = null) {
		$this -> Post -> recursive = 1;
		$post = $this -> Post -> find('all', array(
				'contain' => array('User','Tag.tag'),
				'conditions' => array('Post.public_key' => $public_key ),
				'fields' => array('Post.id',  'Post.public_key',  'Post.title',  'Post.content', 'Post.status', 'Post.modified', 'Post.votes', 'Post.views',
									'User.username', 'User.public_key'
									)
				));
		if (empty($post)) {
			throw new NotFoundException();
		} else {
			$question['Question'] = $post [0]['Post'];
			$question['User'] = $post [0]['User'];
			if(!empty($post [0]['Tag'])){
				foreach ($post [0]['Tag'] as $key => $value) {
					$question['Tag'][$key]['tag'] = $post[0]['Tag'][$key]['tag'];
				}
			}
			$this -> Post -> recursive = 0;
			$answers = $this -> Answer -> find('all', array(
				'conditions' => array('Answer.related_id' => $question['Question']['id']),
				'fields' => array('Answer.content', 'Answer.status','Answer.modified','Answer.votes',
									'User.username', 'User.public_key')
				)
			);

			if (!empty($question)) {
				$views = array('id' => $question['Question']['id'], 'views' => $question['Question']['views' ] + 1);
				$this -> Post -> save($views);
			}

			$search = array('Post');
			$replace = array('Question');
			$resArray = str_replace($search,$replace,json_encode($question));
			$question = json_decode($resArray, true);

			$replace = array('Answer');
			$resArray = str_replace($search,$replace,json_encode($answers));
			$answers = json_decode($resArray, true);

			
			$this -> set('question', $question);
			$this -> set('answers', $answers);
			$this -> set('_serialize', array('question', 'answers'));
		}
	}
	
	/**
	 * api_ask method
	 * rest method
	 * used for ask question
	 * 
	 * @return void
	 */
	public function api_ask() {
		if (!empty($this -> request -> data) && !empty($this -> request -> data['title']) && !empty($this -> request -> data['content']) && !empty($this -> request -> data['api_key'])) {
			$user = $this -> User -> findByApiKey($this -> request -> data['api_key']);
			if (!empty($user)) {
				$userId = $user['User']['id'];
				$data['Post'] = $this -> request -> data;
				$post = $this -> __postSave('question', $userId, $data);
				if(!$post) {
					throw new InternalErrorException();
				} else {
						
					$this->Post->Behaviors->attach('Containable', array('autoFields' => false));	
					$question = $this->Post->find('first', array(
						'contain' => array('User','Tag.tag', 'Answer.public_key', 'Answer.title', 'Answer.modified'),
						'conditions' => array('Post.id' => $post['id']),
						'fields' => array('Post.id',  'Post.public_key',  'Post.title',  'Post.content', 'Post.status', 'Post.modified', 'Post.votes', 'Post.views',
									'User.username', 'User.public_key'
						)
					));
					
					$search = array('Post');
					$replace = array('Question');
					$resArray = str_replace($search,$replace,json_encode($question));
					$question = json_decode($resArray, true);
					
					$this -> set('question', $question);
					$this -> set('_serialize', array('question'));
				}
			} else {
				throw new ForbiddenException();
			}
		} else {
			throw new ForbiddenException();
		}
	}
	
	/**
	 * api_ask method
	 * rest method
	 * used for answer a question
	 * 
	 * @param string $public_key
	 * @return void
	 */
	public function api_answer($public_key) {
		if (!empty($this -> request -> data) && !empty($this -> request -> data['content']) && !empty($this -> request -> data['api_key'])) {
			$user = $this -> User -> findByApiKey($this -> request -> data['api_key']);
			if (!empty($user)) {
				$question = $this -> Post -> findByPublicKey($public_key);
				if (empty($question)) {
					throw new NotFoundException();
				}
				$userId = $user['User']['id'];
				$data['Post'] = $this -> request -> data;
				
				$post = $this -> __postSave('answer', $userId, $data, $question['Post']['id']);
				if(!$post) {
					throw new InternalErrorException();
				} else {
					$this -> Post -> recursive = 1;
					$this->Post->Behaviors->attach('Containable', array('autoFields' => false));	
					$question = $this -> Post -> find('first', array(
						'contain' => array('User','Tag.tag'),
						'conditions' => array('Post.id' => $post['related_id']  ),
						'fields' => array('Post.id',  'Post.public_key',  'Post.title',  'Post.content', 'Post.status', 'Post.modified', 'Post.votes', 'Post.views',
									'User.username', 'User.public_key'
						)
					));
					
					$answers = $this -> Post -> find('all', array(
						'contain' => array('User'),
						'conditions' => array('Post.related_id' => $question['Post']['id']  ),
						'fields' => array('Post.id',  'Post.public_key', 'Post.content', 'Post.status', 'Post.modified', 'Post.votes', 'Post.views',
									'User.username', 'User.public_key'
						)
					));
					
					$search = array('Post');
					$replace = array('Question');
					$resArray = str_replace($search,$replace,json_encode($question));
					$question = json_decode($resArray, true);
					
					$replace = array('Answer');
					$resArray = str_replace($search,$replace,json_encode($answers));
					$answers = json_decode($resArray, true);
					
					$this -> set('question', $question);
					$this -> set('answers', $answers);
					$this -> set('_serialize', array('question', 'answers'));
					
				}
			} else {
				throw new ForbiddenException();
			}
		} else {
			throw new ForbiddenException();
		}
	}
	
	
	/**
	 * api_edit method
	 * rest method
	 * used to edit questions & answers
	 * 
	 * @param string $public_key
	 * @return void
	 */
	public function api_edit($public_key) {
		
		if (!empty($this -> request -> data)) {
			
			$user = $this -> User -> findByApiKey($this -> request -> data['api_key']);
			if (!empty($user)) {
			
				$question = $this -> Post -> findByPublicKey($public_key);
				if (empty($question)) {
					throw new NotFoundException();
				}
				
				if($question['Post']['user_id'] != $user['User']['id']){
					throw new ForbiddenException();
				}
				
				$data['Post'] = $this -> request -> data;
				$data['Post']['id'] = $question['Post']['id'];
				
				$redirect = $question;
				
				$qid = $question['Post']['related_id'];
				if ($question['Post']['type'] == 'answer') {
					if (empty($this -> request -> data['content'])) {
						throw new ForbiddenException();
					}
					$redirect = $this -> Post -> findById($redirect['Post']['related_id']);
				}

				if ($question['Post']['type'] == 'question') {
					$qid = $question['Post']['id'];
					if (empty($this -> request -> data['title']) && empty($this -> request -> data['content'])) {
						throw new ForbiddenException();
					}			
					
					$tags = $this -> PostTag -> find('all', array('conditions' => array('PostTag.post_id' => $question['Post']['id'])));
					$this -> Tag -> recursive = -1;
					foreach ($tags as $key => $value) {
						$tag_names[$key] = $this -> Tag -> find('first', array('conditions' => array('Tag.id' => $tags[$key]['PostTag']['tag_id']), 'fields' => array('Tag.tag')));
						if ($key == 0) {
							$tag_list = $tag_names[$key]['Tag']['tag'];
						} else {
							$tag_list = $tag_list . ', ' . $tag_names[$key]['Tag']['tag'];
						}
					}
					$this->set('tags', $tag_list);
				}
				if (!empty($data['tags'])) {
					$this->Post->Behaviors->attach('Tag', array('table_label' => 'tags', 'tags_label' => 'tag', 'separator' => ', '));
				}

				if ($this -> Post -> save($data)) {
					$this -> Post -> recursive = 1;
					$this->Post->Behaviors->attach('Containable', array('autoFields' => false));	
					$question = $this -> Post -> find('first', array(
						'contain' => array('User','Tag.tag'),
						'conditions' => array('Post.id' => $qid  ),
						'fields' => array('Post.id',  'Post.public_key',  'Post.title',  'Post.content', 'Post.status', 'Post.modified', 'Post.votes', 'Post.views',
									'User.username', 'User.public_key'
						)
					));
					
					
					$answers = $this -> Post -> find('all', array(
						'contain' => array('User'),
						'conditions' => array('Post.related_id' => $question['Post']['id']  ),
						'fields' => array('Post.id',  'Post.public_key', 'Post.content', 'Post.status', 'Post.modified', 'Post.votes', 'Post.views',
									'User.username', 'User.public_key'
						)
					));
					
					$search = array('Post');
					$replace = array('Question');
					$resArray = str_replace($search,$replace,json_encode($question));
					$question = json_decode($resArray, true);
					
					$replace = array('Answer');
					$resArray = str_replace($search,$replace,json_encode($answers));
					$answers = json_decode($resArray, true);
					
					$this -> set('question', $question);
					$this -> set('answers', $answers);
					$this -> set('_serialize', array('question', 'answers'));
					
				} else {
					throw new InternalErrorException();
				}
			} else {
					throw new ForbiddenException();
			}

		} else {
			throw new ForbiddenException();
		}
	}


	/**
	 * api_delete method
	 * rest method
	 * used to delete questions & answers
	 * 
	 * @param string $public_key
	 * @return void
	 */
	public function api_delete($public_key) {
		
		if (!empty($this -> request -> data)) {
			$user = $this -> User -> findByApiKey($this -> request -> data['api_key']);
			if (!empty($user)) {
				$question = $this -> Post -> findByPublicKey($public_key);
				if (empty($question)) {
					throw new NotFoundException();
				}
				
				if($question['Post']['user_id'] != $user['User']['id']){
					throw new ForbiddenException();
				}
				
				$data['Post'] = $this -> request -> data;
				$data['Post']['id'] = $question['Post']['id'];
				
				$redirect = $question;
				
				$qid = $question['Post']['related_id'];
				if ($question['Post']['type'] == 'answer') {
					 if ($this->Post->delete($question['Post']['id'])) {
            			$message = 'Deleted';
        			} else {
            			throw new InternalErrorException();
        			}
				} elseif ($question['Post']['type'] == 'question') {
					$result = $this->Post->deleteAll(array( 'Post.related_id' => $question['Post']['id'])
						
					);
					if($result) {
						if ($this->Post->delete($question['Post']['id'])) {
							$this->PostTag->delete(array( 'post_id' => $question['Post']['id']));
            				$message = 'Deleted';
        				} else {
            				throw new InternalErrorException();
        				}
					} else {
						throw new InternalErrorException();
					}			
				}

				$this->set(array(
         		   'message' => $message,
         		   '_serialize' => array('message')
       			 ));
			} else {
				throw new ForbiddenException();
			}

		} else {
			throw new NotFoundException($this -> request -> data['api_key']);
		}
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this -> request -> is('post')) {
			$this -> Post -> create();
			if ($this -> Post -> save($this -> request -> data)) {
				$this -> Session -> setFlash(__('The post has been saved'));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('The post could not be saved. Please, try again.'));
			}
		}
		$users = $this -> Post -> User -> find('list');
		$this -> set(compact('users'));
	}

}
