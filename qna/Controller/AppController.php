<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	/**
	 * This controller uses qna.ctp layout
	 *
	 * @var string
	 */
	public $layout = 'qna';
	
	/**
	 * This controller uses Security, RequestHandler, Session, Auth components
	 *
	 * @var array
	 */
	public $components = array('RequestHandler', 'Session', 'Auth' => 
		array(
			'loginAction' => array('controller' => 'users', 'action' => 'login'), 
			'loginRedirect' => array('controller' => 'books', 'action' => 'index'), 
			'logoutRedirect' => array('controller' => 'users', 'action' => 'login'), 
			'loginError' => 'Incorrect username/password combination.', 
			'authError' => 'You can not access this page', 
			'authorize' => array('Controller')
		)
	);

	/**
	 * beforeFilter method (Cakephp in-built method)
	 * used for filteration
	 * 
	 * @return void
	 */
	function beforeFilter() {
		$this -> Auth -> allow('index', 'view');
		
		$this -> set('logged_in', $this -> Auth -> loggedIn());
		$this -> set('current_user', $this -> Auth -> user());
		$this -> set('isAdmin', $this -> isAdmin());
	}	
	
	/**
	 * isAdmin method
	 * used to check whether user is admin or not 
	 * 
	 * @return boolean
	 */
	public function isAdmin() {
		$role = $this->Auth->user('role');
		if ($this->Auth->user('role') == 'admin') {
			return true;
		}
		return false;

	}
	
	public function isAuthorized($user) {
        if (!empty($this->request->params['admin'])) {
                return $user['role'] === 'admin';
        }
        return !empty($user);
	}

}
