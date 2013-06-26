<?php
	echo $this->Html->css('wmd.css');
	echo $this->Html->script('wmd/showdown.js');
	echo $this->Html->script('wmd/wmd.js');
?>
<div id="main_content">
<div id="<?php echo $question['Post']['public_key'];?>" class="question">
	<div class="question_details">
		<span class="question-header">Question</span> from 
		<?php echo $this->Html->link(
					$question['User']['username'],
					'/users/' . $question['User']['public_key'] . '/' . $question['User']['username']
				);
		?> , 
		<span class="">asked <?php echo $this->time->timeAgoInWords(strtotime($question['Post']['created']));?></span>
	</div>
	<div class="question_info" style="">
		<div class="list_votes votes" style="">
			<span class="large_text"><?php echo $question['Post']['votes']; ?></span>
			<span>votes</span>
		</div>
		<div>
		<?php
        	echo $this->Html->image('thumbs_up.png', array('alt' => 'Plus', 'url' => '/vote/' . $question['Post']['public_key'] . '/up'));
		?>
		<?php
           	echo $this->Html->image('thumbs_down.png', array('alt' => 'Minus', 'url' => '/vote/' . $question['Post']['public_key'] . '/down'));
	    ?>	
	    </div>
	</div>
	
	<div class="question_content">
		
		<h2><?php echo $question['Post']['title'];?></h2>
		<?php echo $question['Post']['content'];?>
		<div id="tags" style="clear: left;margin-top: 10px;">
			<?php foreach($question['Tag'] as $tag) { ?>
				<div class="tag">
					<?php echo $this->Html->link(
							$tag['tag'],
							'/tags/' . $tag['tag']
						);
					?>
				</div>
			<?php  } ?>
		</div>
	</div>
	
	<div class="links">

        
        <?php  
        if($question['Post']['user_id'] == $current_user['id']  || $isAdmin) { ?>
		<?php echo $this->Html->link(
				__('edit',true),
				'/questions/' . $question['Post']['public_key'] . '/' . $this->CommonFunctions->niceUrl($question['Post']['title']) . '/edit', array("class"=>"button button__action"));
		}
		?>

        <?php if($isAdmin){ ?>
               <?php echo $this->Html->link(
                       __('del',true),
               '/questions/' . $question['Post']['public_key'] . '/' . $this->CommonFunctions->niceUrl($question['Post']['title']) . '/delete', array("class"=>"button button__action")); ?>
        <?php } ?>


	</div>


</div>

<?php if(count($answers) != 0) {?>
<div id="answers">
	<h2><?php echo __n('ANSWER','ANSWERS',count($answers));?></h2>
	<hr/>
	<?php foreach($answers as $answer) { ?>
	<div class="<?php echo ($answer['Answer']['status'] == 'correct') ? 'answered' : 'answer';?>" id="a_<?php echo $answer['Answer']['public_key']?>">
		
		<div class="answer_details">
			<span class="question-header">Answer</span> from 
			<?php echo $this->Html->link(
						$answer['User']['username'],
						'/users/' . $answer['User']['public_key'] . '/' . $answer['User']['username']
				);
			?> , 
			<span class="">asked <?php echo $this->time->timeAgoInWords(strtotime($answer['Answer']['created']));?></span>
		</div>
		
		<div class="answer_info" style="">
			<div class="list_votes votes" style="">
				<span class="large_text"><?php echo $answer['Answer']['votes']; ?></span>
				<span>votes</span>
			</div>
			<?php
        		echo $this->Html->image('thumbs_up.png', array('alt' => 'Plus', 'url' => '/vote/' . $answer['Answer']['public_key'] . '/up'));
			?>
			<?php
           		echo $this->Html->image('thumbs_down.png', array('alt' => 'Minus', 'url' => '/vote/' . $answer['Answer']['public_key'] . '/down'));
	   		 ?>	
	   		 <?php if(($question['Post']['status'] == "open") && ($current_user['id'] == $question['Post']['user_id'])) {?>
	   		 	<div class="checkmark">
					<?php echo $this->Html->link('', '/questions/' . $answer['Answer']['public_key'] . '/correct');?>				
				</div>
	   		 <?php } elseif($answer['Answer']['status'] == "correct") { ?>
					<?php echo $this->Html->image('checkmark_green.png');?>
			<?php } ?>
		</div>
		
		<div class="answer_content">
				<?php echo $answer['Answer']['content'];?>
		</div>

		<div class="links">
			
	
			<?php echo $this->Html->link('link', '/questions/' . $question['Post']['public_key'] . '/' . $this->CommonFunctions->niceUrl($question['Post']['title']) 
					. '#a_' . $answer['Answer']['public_key'], array("class"=>"button button__action"));
			?>
			<?php if($answer['Answer']['user_id'] == $current_user['id'] || $isAdmin) { ?>
			<?php echo $this->Html->link(__('edit',true), 
					'/answers/' . $answer['Answer']['public_key'] . '/edit', array("class"=>"button button__action"));
			}
			?>
			<?php if($isAdmin) { ?>
			<?php echo $this->Html->link(__('del',true), 
					'/answers/' . $answer['Answer']['public_key'] . '/delete', array("class"=>"button button__action"));
			}
			?>
	
	
		</div>

		
	
	
	</div>
	<?php } ?>
</div>
<?php } ?>

<div id="user_answer">
	<?php if($logged_in) { ?>

	<h2><?php echo __('YOUR ANSWER'); ?></h2>
	<hr />
	<div class="answer">
	<?php echo $this->form->create(null, array(
			'url' => '/questions/' . $question['Post']['public_key'] . '/' . $this->CommonFunctions->niceUrl($question['Post']['title']) . '/answer')
		); ?>
	<div id="wmd-button-bar" class="wmd-panel"></div>
	<?php echo $this->form->textarea('content', array(
		'id' => 'wmd-input', 'class' => 'wmd-panel'
		));
	 ?>

	<div id="wmd-preview" class="wmd-panel"></div>

	<?php echo $this->form->end(__d('verb','Answer',true));?>
	</div>
	
	<?php } else {?>
	<h3>You must login to answer. <?php echo $this -> Html -> link('Login', array('controller' => 'users', 'action' => 'login')); ?></h3>
<?php  } ?>
	
</div>
</div>

