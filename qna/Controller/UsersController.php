<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	/**
	 * This controller uses User, Post models
	 *
	 * @var array
	 */
	public $uses = array('User', 'Post');
	
	/**
	 * This controller uses Auth, Session, Security components
	 *
	 * @var array
	 */
	public $components = array('Auth', 'Session');
	
	/**
	 * This controller uses Thumbnail, TrickyFileInput, Session, Security helpers
	 *
	 * @var array
	 */
	public $helpers = array('Thumbnail', 'TrickyFileInput', 'Session');
	
	
	/**
	 * Allowed image types
	 *
	 * @var array
	 */
	public $allowedTypes = array(
    	'image/jpeg',
    	'image/gif',
    	'image/png',
    	'image/pjpeg',
    	'image/x-png'
  	);
	
	/**
	 * beforeFilter method (Cakephp in-built method)
	 * use for filteration 
	 * @return void
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this-> Auth-> allow('register', 'login', 'view', 'avatar', 'userList');
		
		if($this -> Auth -> loggedIn())
			$this -> Auth -> allow('user_setting', 'avatar');
	}
	
	/**
	 * login method
	 * used for user login
	 * 
	 * @return void
	 */
	public function login() {
		print_r($this->referer);
		if($this -> Auth -> loggedIn()) {
			 //$this->Session->setFlash('You have already logged in.');
			 $this->redirect($this->Auth->redirectUrl());
		}elseif ($this->request->is('post')) {
	        if ($this->Auth->login()) {
	        	$this->Auth->allow();
				if($this->Session->check('beforeLogin_referer') )
				{
					$url = $this->Session->read('beforeLogin_referer');
					$this->Session->delete('beforeLogin_referer');
					$this->redirect($url);
				}
				else
				{
    				$this->redirect($this->referer($this->Auth->redirect(), true));
				}
				   
	        } else {
	            $this->Session->setFlash('Your username/password combination was incorrect');
	        }
	    }
		$currentLoginUrl = strtolower( "/" .$this->name ."/" .$this->action );
		if($this->referer(null, true) != $currentLoginUrl)
		{
    		$this->Session->write('beforeLogin_referer', $this->referer($this->Auth->redirect(), true));  //if referer can't be read, or if its not from local server, use $this->Auth->rediret() instead
		}
		$this -> set('title_for_layout', 'Log In');
	}
	
	/**
	 * logout method
	 * used for user logout
	 *
	 * @return void
	 */
	public function logout() {
		$this->Auth->logout();
		$this->redirect($this->referer($this->Auth->redirect(), true));  
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $public_key
	 * @return void
	 */
	public function view($public_key) {
		$user = $this->User->findByPublicKey($public_key);
		if (empty($user)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->Post-> recursive = -1;
		$questions = $this -> Post -> find('all', array('conditions' => array('Post.user_id' => $user['User']['id'], 'Post.type' => 'question'), 'fields' => array('Post.title', 'Post.public_key'), ));
		$answers = $this -> Post -> query("SELECT Post.public_key, Post.title FROM posts as Post WHERE Post.id IN ( SELECT posts.related_id FROM posts WHERE posts.user_id =" . $user['User']['id'] . ")");
		$this -> set('title_for_layout', $user['User']['username']);
		$this -> set('user', $user);
		$this -> set('questions', $questions);
		$this -> set('answers', $answers);
		
	}

	/**
	 * register method
	 * used for user registration
	 *
	 * @return void
	 */
	public function register() {
		if ($this -> Auth -> loggedIn()) {
			$this -> Session -> setFlash('You are already registered.');
			$this -> redirect($this -> referer());
		}
		if ($this -> request -> is('post')) {
			$this -> User -> create();
			$data = $this -> request -> data;
			$data['User']['public_key'] = uniqid();
			$data['User']['role'] = "user";
			$data['User']['api_key'] = md5(microtime().rand());
			if ($this -> User -> save($data)) {
				$this -> Session -> setFlash(__('You have succesfully registered. Now you can login.'));
				$this -> redirect('/users/login');
			} else {
				$this -> Session -> setFlash(__('The user could not be saved. Please, try again.'));
			}
		}

		$this -> set('title_for_layout', 'User Registration');
	}

	/**
	 * user_settings method
	 * used to edit user_settings
	 *
	 * @param string $public_key
	 * @return void
	 */
	public function user_settings($public_key) {
		if($this->Auth->user('public_key') != $public_key) {
			$this->Session->setFlash('Those are not your settings to change.');
			$this->redirect('/');	
		}
		$this->recursive = -1;
		
		$user = $this->User->find(
			'first', array(
				'conditions' => array(
					'public_key' => $public_key
				)
			)
		);
		
		if(!empty($this->request->data)) {
			
			
			$data['User']['location'] = $this->request->data['User']['location'];
			$data['User']['website'] = $this->request->data['User']['website'];
			$data['User']['info'] = $this->request->data['User']['info'];
			$data['User']['id'] = $this->Auth->user('id');
			if($this->Auth->password($this->request->data['User']['current_password']) == $user['User']['password']) {
					
				$data['User']['password'] = $this->request->data['User']['password'];
				$data['User']['password_confirmation'] = $this->request->data['User']['password_confirmation'];			
				$this->User->id = $this->Auth->user('id');
				
				if($this->User->save($data))
					$this->Session->setFlash('Settings updated!');
				else 
					$this->Session->setFlash('Something is wrong. Settings are not updated');
					
			}elseif(empty($this->request->data['User']['old_password'])) {
				if($this->User->save($data))
					$this->Session->setFlash('Settings updated, except password.');
				else 
					$this->Session->setFlash('Something is wrong. Settings are not updated');	
			}else {
				$this->Session->setFlash('Old Password incorrect.  Settings remain unchanged.');
				$this->redirect(Controller::referer('/'));
			}
			
		}
		
		$user = $this->User->find(
			'first', array(
				'conditions' => array(
					'public_key' => $public_key
				)
			)
		);
		
		$this -> set('title_for_layout', $user['User']['username']);
		$this->set('user_info', $user);
		
	}
	
	/**
	 * avatar method
	 * used to upload user image avatar
	 *
	 * @return void
	 */
	 public function avatar() {
		if(!empty($this->request->data['Upload']['file'])) {
			/* check all image parameters */
			
			$this->__checkImgParams();
						
			$user = $this->User->findById($this->Auth->user('id'));
			$uploadPath = WWW_ROOT . 'img/uploads/users/';
			$uploadFile = $uploadPath . $this->Auth->user('public_key') . '-' . $this->request->data['Upload']['file']['name'];
			
		
			
			$directory = dir($uploadPath); 
			if(!empty($user['User']['image'])) {
				unlink(WWW_ROOT . $user['User']['image']);
			}
			$directory->close();

			
			if(move_uploaded_file($this->request->data['Upload']['file']['tmp_name'], $uploadFile)) {
				
				$data['User']['image'] = '/img/uploads/users/' . $this->Auth->user('public_key') . '-' . $this->request->data['Upload']['file']['name'];
				$data['User']['api_key'] = '538bfe80756c5c00a1984a4bd57c4b45';
				
				$data['User']['id'] = $user['User']['id'];
				//$this->User->id = $data['User']['id'];
				$this->User->save($data);
				
				$this->Session->setFlash('Your profile picture has been set!');
				$this->redirect(Controller::referer('/'));
			}
			else {
					
				$this->Session->setFlash('Something went wrong uploading your avatar...');
				$this->redirect(Controller::referer('/'));
			}
		} else {
			$this->Session->setFlash('We didn\'t catch that avatar, please try again...');
			$this->redirect(Controller::referer('/'));
		}
	}
	
	/**
	 * __checkImgParams method
	 * used to check image parameters
	 * 
	 * @return void
	 */
	function __checkImgParams() {
		/* check file type */
		$this->__checkType($this->request->data['Upload']['file']['type']);
		
	
		
		/* check file size */
		$this->__checkSize($this->request->data['Upload']['file']['size']);
		
		
		
		/* check image dimensions */
		$this->__checkDimensions($this->request->data['Upload']['file']['tmp_name']);
		
			
	}
	
	/**
	 * __checkType method
	 * used to check image type
	 * 
	  * @param string $type
	 * @return void
	 */
	function __checkType($type = null) {
		$valid = false;
    	foreach($this->allowedTypes as $allowedType) {
      		if(strtolower($type) == strtolower($allowedType)){
        		$valid = true;
      		}
    	}
		if(!$valid) {
			$this->Session->setFlash('You tried to upload an invalid type!  Please upload your pictures in jpeg, gif, or png format!');
			$this->redirect(Controller::referer('/'));
		}
	}
	
	/**
	 * __checkSize method
	 * used to check image size
	 * 
	 * @param integer $size
	 * @return void
	 */
	function __checkSize($size = null) {
	    if($size > 1024 * 1024 * 2) {
			$this->Session->setFlash('You tried to upload an image that was too large!  Images must be under 2MB.');
			$this->redirect(Controller::referer('/'));
		}
	}
	
	/**
	 * __checkDimensions method
	 * use to check image dimensions
	 * 
	 * @param string $filepath
	 * @return void
	 */
	function __checkDimensions($filePath) {
		$size = getimagesize($filePath);
				
		if(!$size) {
			$this->Session->setFlash('We could not check that image\'s size, so we can\'t upload it.');
			$this->redirect(Controller::referer('/'));
		}

		if($size[0] > 800 || $size[1] > 800) {
			$this->Session->setFlash('Images cannot be any larger than 800 by 800 pixels.');
			$this->redirect(Controller::referer('/'));
		}
		
	}

	/**
	 * userList method
	 * used to retrive all users
	 *
	 * @param integer $page
	 * @return void
	 */
	public function userList($username = '', $page = 1) {
		if ($page < 1 || !is_numeric($page)) {
			$page = 1;
		}
		$this -> User -> recursive = 0;
		if($this->request->is('post')) {
			$username = $this->request->data['User']['username'];
			$list = $this -> User -> find('all', array(
				'conditions' => array(
					'username like'	 => '%' . $username . '%'
				)
			));
		} else {
			$list = $this -> User -> find('all', array(
				'conditions' => array(
					'username like'	 => '%' . $username . '%'
				)
			));
		}
		
		foreach ($list as $key => $value) {
			$users[$key]['username'] = $list[$key]['User']['username'];
			$users[$key]['public_key'] = $list[$key]['User']['public_key'];
			$users[$key]['image'] = $list[$key]['User']['image'];
		}
		
		$user_count = count($users);
		if (($user_count - ($page * 100)) > 0) {
			$this -> set('next', $page + 1);
		}
		if ($page >= 2) {
			$this -> set('previous', $page - 1);
		}
		if (($user_count % 100) == 0) {
			$end_page = $user_count / 100;
		} else {
			$end_page = floor($user_count / 100) + 1;
		}
		$loop_fuel = (($page * 100) - 100) - 1;
		$this -> set('end_page', $end_page);
		$this -> set('current', $page);
		$this -> set('users', $users);
		$this -> set('username', $username);
		$this -> set('loop_fuel', $loop_fuel);
	}

}