<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
//	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
 
	

	//Router::mapResources(array('posts', 'users'));
	Router::parseExtensions('json', 'xml');
 
 
	// Iterate through each of the routes and for any that start with "/api/", setup the prefix properly.
	Router::connect('/api/questions', array('api' => true, 'prefix' => 'api', 'controller' => 'posts', 'action' => 'index', '[method]'=>'GET'));
	Router::connect('/api/questions', array('api' => true, 'prefix' => 'api', 'controller' => 'posts', 'action' => 'ask', '[method]'=>'POST'));
	Router::connect('/api/questions/:public_key', array('api' => true, 'prefix' => 'api', 'controller' => 'posts', 'action' => 'view', '[method]'=>'GET'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
 	Router::connect('/api/answers/:public_key', array('api' => true, 'prefix' => 'api', 'controller' => 'posts', 'action' => 'answer', '[method]'=>'POST'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/api/questions/:public_key', array('api' => true, 'prefix' => 'api','controller' => 'posts', 'action' => 'edit', '[method]'=>'PUT'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/api/answers/:public_key', array('api' => true, 'prefix' => 'api','controller' => 'posts', 'action' => 'edit', '[method]'=>'PUT'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/api/questions/:public_key', array('api' => true, 'prefix' => 'api','controller' => 'posts', 'action' => 'delete', '[method]'=>'DELETE'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/api/answers/:public_key', array('api' => true, 'prefix' => 'api','controller' => 'posts', 'action' => 'delete', '[method]'=>'DELETE'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+')); 	
	
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	
	Router::connect('/', array('controller' => 'posts', 'action' => 'display'));
	Router::connect('/questions/unsolved', array('controller' => 'posts', 'action' => 'display', 'unsolved'));
    Router::connect('/questions/hot', array('controller' => 'posts', 'action' => 'display', 'hot'));
    Router::connect('/questions/solved', array('controller' => 'posts', 'action' => 'display', 'solved'));
	Router::connect('/search/*', array('controller' => 'posts', 'action' => 'display'));
	Router::connect('/questions', array('controller' => 'posts', 'action' => 'display'));
	Router::connect('/questions/ask', array('controller' => 'posts', 'action' => 'ask'));
	Router::connect('/questions/:public_key/:title/answer', array('controller' => 'posts', 'action' => 'answer'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/questions/:public_key/:title/edit', array('controller' => 'posts', 'action' => 'edit'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/questions/:public_key/:title/delete', array('controller' => 'posts', 'action' => 'delete'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/questions/:public_key/correct', array('controller' => 'posts', 'action' => 'markCorrect'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/questions/:public_key/:title', array('controller' => 'posts', 'action' => 'view'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/questions/:public_key', array('controller' => 'posts', 'action' => 'view'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/answers/:public_key/edit', array('controller' => 'posts', 'action' => 'edit'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/answers/:public_key/delete', array('controller' => 'posts', 'action' => 'delete'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	
	Router::connect('/users/:public_key/upload', array('controller' => 'users', 'action' => 'avatar'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/users/settings/:public_key', array('controller' => 'users', 'action' => 'user_settings'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	Router::connect('/users', array('controller' => 'users', 'action' => 'userlist'));
	Router::connect('/users/username:username/*', array('controller' => 'users', 'action' => 'userlist'));
			Router::connect('/users/:public_key/:title', array('controller' => 'users', 'action' => 'view'), array('pass' => array('public_key'), 'public_key' => '[A-z0-9-]+'));
	
	
	Router::connect('/vote/:public_key/:type', array('controller' => 'votes', 'action' => 'vote'), array('pass' => array('public_key', 'type'), 'public_key' => '[A-z0-9-]+', 'type' => '[A-z]+'));
	
	Router::connect('/tags', array('controller' => 'tags', 'action' => 'tag_list'));
    Router::connect('/tags/:page', array('controller' => 'tags', 'action' => 'tag_list'), array('pass' => array('page'), 'page' => '[0-9-]+'));
    Router::connect('/tags/:tag_name', array('controller' => 'tags', 'action' => 'find_tag'), array('pass' => array('tag_name'), 'tag_name' => '[A-z-]+'));
    Router::connect('/tags/:tag_name/:page', array('controller' => 'tags', 'action' => 'find_tag'), array('pass' => array('tag_name', 'page'), 'tag_name' => '[A-z-]+', 'page' => '[0-9-]+'));
    Router::connect('/tag_search/*', array('controller' => 'tags', 'action' => 'find_tag'));
	
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
