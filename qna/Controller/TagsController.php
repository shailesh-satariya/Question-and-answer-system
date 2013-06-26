<?php
App::uses('AppController', 'Controller');
/**
 * Tags Controller
 *
 * @property Tag $Tag
 */
class TagsController extends AppController {
	
	/**
	 * beforeFilter method (Cakephp in-built method)
	 * used for filteration
	 * 
	 * @return void
	 */
	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('tag_list', 'find_tag');
	}
	
	/**
	 * tag_list method
	 * used to retrive all tags
	 *
	 * @param integer $page
	 * @return void
	 */
	public function tag_list($page = 1) {
		if ($page < 1 || !is_numeric($page)) {
			$page = 1;
		}
		$list = $this -> Tag -> getSuggestions();
		foreach ($list as $key => $value) {
			$tags[$key]['count'] = $list[$key]['0']['count'];
			$tags[$key]['tag'] = $list[$key]['tags']['tag'];
		}
		$tag_count = count($tags);
		if (($tag_count - ($page * 100)) > 0) {
			$this -> set('next', $page + 1);
		}
		if ($page >= 2) {
			$this -> set('previous', $page - 1);
		}
		if (($tag_count % 100) == 0) {
			$end_page = $tag_count / 100;
		} else {
			$end_page = floor($tag_count / 100) + 1;
		}
		$loop_fuel = (($page * 100) - 100) - 1;
		$this -> set('end_page', $end_page);
		$this -> set('current', $page);
		$this -> set('tag', $tags);
		$this -> set('loop_fuel', $loop_fuel);
		
	}
	
	/**
	 * find_tag method
	 * used to retrive all questions related to specific tag
	 *
	 * @param string $tag_name, integer $page
	 * @return void
	 */
	public function find_tag($tag_name='php', $page=1) {
        $this->pageTitle = 'Results for ' . $tag_name . '';
        if(isset($this->passedArgs['tag'])) {
            $tag_name = $this->passedArgs['tag'];
            $page = $this->passedArgs['page'];
        }
        if($page <= 1) {
            $page = 1;
        }else {
            $previous = $page - 1;
            $this->set('previous', $previous);
        }
        $dummy = $this->Tag->find(
            'all', array(
                'contain' => array(
                    'Post'
                ),
                'conditions' => array('Tag.tag' => $tag_name)
            )
        );
        if(!isset($dummy['0'])) {
            $this->Session->setFlash('No results for tag: ' . $tag_name . '!');
            $this->redirect('/');
        }

        $result_count = count($dummy['0']['Post']);

        if($result_count < 10) {
            $start_limit = 0;
        }else {
            $start_limit = $result_count - (10 * $page);
        }
        if($start_limit < 0) {
            $start_limit = 0;
        }

        $posts = $this->Tag->tagSearch($tag_name, $start_limit);
        if(($result_count % 15) == 0) {
            $end_page = $result_count / 10;
        }else {
            $end_page = floor($result_count / 10) + 1;
        }
        $this->set('questions', $posts);
		
        $this->set('end_page', $end_page);
        $this->set('current', $page);
        $this->set('tag_name', $tag_name);
        if($page < $end_page) {
            $next = $page + 1;
            $this->set('next', $next);
        }
    }

}
