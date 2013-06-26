<?php echo $this -> html -> docType('xhtml-trans'); ?>
<html>
<head>
    <title><?php echo $title_for_layout; ?> | QuestionAnswerSystem</title>
    <?php 
    	echo $this->Html->meta('icon', $this->Html->url('/favicon.png'));
    	echo $this->Html ->script('jquery');
		echo $this->Html ->script('prettify/prettify');
    	echo $this->Html ->css('style'); 
		echo $this->Html ->css('prettify'); 
    ?>
</head>
<body onload="prettyPrint()">
<div id="userbar">
	 <div id="userinfo">
	 <?php if($logged_in): ?>
	 	<?php echo $this -> Html -> link($current_user['username'], '/users/' . $current_user['public_key'] . '/' . $current_user['username']); ?>&nbsp;|&nbsp;
	 	<?php echo $this -> Html -> link('Settings', '/users/settings/' . $current_user['public_key']); ?>&nbsp;|&nbsp;
	 	<?php echo $this -> Html -> link('Logout', array('controller' => 'users', 'action' => 'logout')); ?>
	 <?php else: ?>	
	 	<?php echo $this -> Html -> link('Login', array('controller' => 'users', 'action' => 'login')); ?>
		<?php echo $this -> Html -> link('Register', array('controller' => 'users', 'action' => 'register')); ?>
	<?php endif; ?>
	</div>
</div>
<div id="header">
	<div id="header-content">
    	<div id="header-logo">
        	<h1><a href="<?php  echo $this -> Html -> url('/'); ?>"><?php echo __('QuestionAnswerSystem'); ?></a></h1>
        </div>	
      
	
	

    </div>
</div>

<div style="clear: both;"></div>
<div id="navigation">
	 <ul id="topnav">
		<li>
			<?php echo $this->Html->link('Ask', array('controller'=>'posts', 'action'=>'ask'));?>
		</li>
		<li>
        	<?php echo $this->Html->link('Questions','/');?>
        </li>
        <li>
        	<?php echo $this->Html->link('Unsolved','/questions/unsolved');?>
        </li>
		<li>
        	<?php echo $this->Html->link('Solved','/questions/solved');?>
        </li>
		<li>
        	<?php echo $this->Html->link('Hot','/questions/hot');?>
        </li>
        
        <li>
        	<?php echo $this->Html->link('Tags', array('controller'=>'tags', 'action'=>'index'));?>
        </li>
        <li>
        	<?php echo $this->Html->link('Users', '/users');?>
        </li>
		</ul>
	<div id=searchbox>
		<?php 
					echo $this->form->create('Post', array('action' => 'display'));
					echo $this->form->input('Post.needle', array('label' =>false, 'value' => 'search', 'onclick' => 'this.value=""', 'id' => 'searchInput'));
					echo $this->form->end();
		?>
	</div>
	
</div>
<div style="clear: both;"></div>

<div id="container">
    <?php echo $this -> session -> flash(); ?>
    <?php echo $content_for_layout; ?>
</div>
</body>
</html>