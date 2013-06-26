<?php
App::uses('AppModel', 'Model');
/**
 * Tag Model
 *
 * @property PostTag $PostTag
 */
class Tag extends AppModel {
	

	var $name = 'Tag';
	var $actsAs = array('Containable');
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array(
		'Post' => array(
			'className' => 'Post',
			'joinTable' => 'post_tags',
			'foreignKey' => 'tag_id',
			'associationForeignKey' => 'post_id',
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
	
	/**
	 * getSuggestions method
	 * used to count questions for each tags
	 * 
	 * @return array
	 */
	public function getSuggestions() {
		return $this->query("SELECT COUNT(post_tags.tag_id) as count, tags.tag
                            FROM post_tags, tags
                            WHERE post_tags.tag_id=tags.id
                            GROUP BY post_tags.tag_id
                            ORDER BY count DESC");

    }
	
	/**
	 * getSuggestions method
	 * used to search questions for specific tag
	 * 
	 * @return array
	 */
	public function tagSearch($tag, $page) {
    	$results = $this->find(
            'all', array(
                'contain' => array(
                    'Post' => array(
                        'User' => array(
                            'fields' => array('User.id', 'User.username', 'User.public_key', 'User.image')
                        ),
                        'Answer' => array(
                            'fields' => array('Answer.id')
                        ),
                        'fields' => array('Post.title', 'Post.public_key',
                                          'Post.views', 'Post.votes', 'Post.created'),
                        'limit' => $page . ',' . 10
                    )
                ),
                'conditions' => array('Tag.tag' => $tag),
                'fields' => array('Tag.tag')
            )
        );
        foreach($results['0']['Post'] as $key => $value) {
            $tags_per_site[$key] = $this->Post->find(
                'all', array(
                    'contain' => array(
                        'Tag.tag'
                    ),
                    'conditions' => array(
                        'Post.id' => $results['0']['Post'][$key]['id']
                    )
                )
            );
        }
        foreach($results['0']['Post'] as $key => $value) {
            $questions[$key]['Post'] = $results['0']['Post'][$key];
            $questions[$key]['User'] = $results['0']['Post'][$key]['User'];
            $questions[$key]['Answer'] = $results['0']['Post'][$key]['Answer'];
            $questions[$key]['Tag'] = $tags_per_site[$key]['0']['Tag'];
            unset($questions[$key]['Post']['User']);
            unset($questions[$key]['Post']['Answer']);
        }
		if(!empty($questions)) {
   	    	$final_results = array_reverse($questions);
        	return $final_results;
		} else {
			return null;
		}
    }

}
