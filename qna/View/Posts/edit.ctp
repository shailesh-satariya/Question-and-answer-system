<div id="main_content">
	<?php
		echo $this->Html->css('wmd.css');
		echo $this->Html->script('wmd/showdown.js');
		echo $this->Html->script('wmd/wmd.js');
	?>
	
	<?php
		if(empty($question['Post']['title'])) { $question['Post']['title'] = 'answer'; }
	?>
	<?php echo $this->form->create(null, array(
		'url' => '/questions/' . $question['Post']['public_key'] . '/' . str_replace(' ', '-',  $question['Post']['title']) . '/edit')
	); ?>
	<?php if ($question['Post']['type'] == 'question') { ?>

		<div class="title">
			<h2><?php echo __('Edit Question'); ?></h2>
		</div>
		<?php echo $this->Form->input('Post.title', array('class' => 'wmd-panel big_input', 'value' => $question['Post']['title'], 'id' => 'PostTitle')); ?>
		<div id="wmd-button-bar" class="wmd-panel"></div>
			<?php echo $this->form->textarea('content', array(
				'id' => 'wmd-input', 'class' => 'wmd-panel', 'value' => $question['Post']['content']
				));
		 	?>
		<div id="wmd-preview" class="wmd-panel"></div>	
		<?php	echo $this->form->text('Post.tags', array('class' => 'wmd-panel big_input', 'value' => $tags, 'id' => 'tag_input')); ?>
	<?php } else {?>
		<div class="title">
			<h2><?php echo __('Edit Answer'); ?></h2>
		</div>
		<div id="wmd-button-bar" class="wmd-panel"></div>
			<?php echo $this->form->textarea('content', array(
				'id' => 'wmd-input', 'class' => 'wmd-panel', 'value' => $question['Post']['content']
				));
			 ?>
		<div id="wmd-preview" class="wmd-panel"></div>
		
	<?php } ?>

	<?php echo $this->Form->end(__('Submit')); ?>
	
</div>