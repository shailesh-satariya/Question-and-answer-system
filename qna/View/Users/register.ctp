<div class="title">
	<h2>Register</h2>
</div>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		echo $this->Form->input('password_confirmation', array('type' => 'password'));
	?>
<?php echo $this->Form->end(__('Submit')); ?>
