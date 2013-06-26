<div id="main_content">
	<?php
		echo $this->Html->css('wmd.css');
		echo $this->Html->script('wmd/showdown.js');
		echo $this->Html->script('wmd/wmd.js');
	?>
	<div class="title">
		<h2>Ask a question</h2>
	</div>	
	<?php
		echo $this->Form->create('Post', array('action' => 'ask'));
		echo $this->Form->input('title', array('style' => 'width: 650px;'));
	?>
	
	<div id="wmd-button-bar" class="wmd-panel"></div>
		<?php echo $this->form->textarea('Post.content', array(
			'id' => 'wmd-input', 'class' => 'wmd-panel'
			));
		 ?>
	<div id="wmd-preview" class="wmd-panel"></div>

	<?php
		echo $this->Form->input('tags',  array('style' => 'width: 650px;'));
		echo $this->Form->end('Ask a question');
	?>
</div>