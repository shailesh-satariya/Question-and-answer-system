<div id="main_content">
	<div class="title">
		<h2>Login</h2>
	</div>	
	<?php
		echo $this->Form->create();
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->end('Login');
	?>
</div>