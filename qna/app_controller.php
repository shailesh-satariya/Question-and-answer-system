<?php 

class AppController extends  Controler {
	var $components = array('Auth', 'Session');
	
	function beforeFilter() {
		$this->Auth->allow('index', 'view');
		$this->Auth->authError = 'Please login to view the page.';
		$this->Auth->loginError = 'Incorrect username/password combination.';
		$this->Auth->loginRedirect = array('controller'=>'posts', 'action'=>'index');
		$this->Auth->logoutRedirect = array('controller'=>'posts', 'action'=>'index');
	}
}
