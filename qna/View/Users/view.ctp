<link rel="stylesheet" href="stylesheets/print.css" type="text/css" media="print" charset="utf-8">
  <!--[if lte IE 6]><link rel="stylesheet" href="stylesheets/lib/ie.css" type="text/css" media="screen" charset="utf-8"><![endif]-->
<?php echo $this->Html->script(array(
        'jquery/jquery.js',
        'jquery/jquery.tabs.js',
        'jquery/jquery.ui-1.7.2.js',
        'jquery/ui.core.js'
        )); 
 ?>	

	<script type="text/javascript">
	 $(function() { $("#tabs").tabs(); }); 
	</script>
<script type="text/javascript">
$(document).ready(function(){
$('#tabs div').hide(); // Hide all divs
$('#tabs div:first').show(); // Show the first div
$('#tabs ul li').addClass('inactive'); // set all links to inactive
$('#tabs ul li:first').removeClass('inactive'); //remove inactive class from first link...
$('#tabs ul li:first').addClass('active'); // ...and set the class of the first link to active
$('#tabs ul li a').click(function(){ //When any link is clicked
	$('#tabs ul li').removeClass('active'); // Remove active class from all links
	$('#tabs ul li').removeClass('inactive');	
	$('#tabs ul li').addClass('inactive'); // set all links to inactive
	$(this).parent().removeClass('inactive'); //remove inactive class from the link that was clicke
	$(this).parent().addClass('active'); //Set clicked link class to active
	var currentTab = $(this).attr('href'); // Set variable currentTab to value of href attribute of clicked link
	$('#tabs div').hide(); // Hide all divs
	$(currentTab).show(); // Show div with id equal to variable currentTab
	return false;
	});
});
</script>

<div id="main_content">

<div id="userAvatar">
	<div id="image">
		<?php if(empty($user['User']['image'])) { ?>
			
			<?php echo $this->html->image('answerAvatar.png'); ?>
		<?php }else { 
echo $this->thumbnail->show(array(
						        'save_path' => WWW_ROOT . 'img/thumbs',
						        'display_path' => $this->webroot.  'img/thumbs',
						        'error_image_path' => $this->webroot. 'img/answerAvatar.png',
						        'src' => WWW_ROOT .  $user['User']['image'],
						        'w' => 130,
								'h' => 130,
								'q' => 100,
		                        'alt' => $user['User']['username'] . ' picture' )
			);
		} ?>
	</div>
</div>
<div id="userInfo">
	<?php if(!empty($user['User']['info'])) { 
		echo $user['User']['info'];	
	}else { 
		echo $user['User']['username'] . ' has not added any information about themselves yet!';
	 } ?> 
</div>

<div id="tabs" style="margin-top: 0px;">
		<ul>
			<li>
				<a href="#tab-1">
					<h3>stats</h3>
				</a>
			</li>
			<li>
				<a href="#tab-2">
					<h3>Q</h3>
				</a>
			</li>
			<li>
				<a href="#tab-3">
					<h3>A</h3>
				</a>
			</li>
		</ul>

	<div class="tabPanel" id="tab-1">
		<h3>user information:</h3>
		<table style="float: left; display: inline;">
			<tr>
				<td>name</td>
				<td><?php echo $user['User']['username'] ?></h2>
			</tr>
			<tr>
				<td>joined</td>
				<td><?php echo $this->time->timeAgoInWords($user['User']['created']);?></td>
			</tr>
			<?php if($user['User']['id'] == $current_user['id']) { ?>
			<tr>
				<td>api key</td>
				<td><?php echo  $user['User']['api_key']; ?></h2>
			</tr>	
			<?php } ?>
			<?php if(!empty($user['User']['location'])) { ?>
			<tr>
				<td>location</td>
				<td><?php echo  $user['User']['location']; ?></h2>
			</tr>	
			<?php } ?>
			<?php if(!empty($user['User']['website'])) { ?>
			<tr>
				<td>website</td>
				<td><?php echo $this->html->link($user['User']['website'], $user['User']['website']); ?></h2>
			</tr>	
			<?php } ?>
		
		</table>
		
	</div>
		
	<div class="tabPanel" id="tab-2">
		<h3>questions asked:</h3>
	    <?php foreach($questions as $_question) { ?>
		<p>
	    <?php
	        echo $this->html->link($_question['Post']['title'], '/questions/' . $_question['Post']['public_key'] . '/' . $_question['Post']['title']);
		?>
		</p>
		<?php } ?>
	</div><!-- end questions tab -->
	
	<div class="tabPanel" id="tab-3">
		<h3>replies given:</h3>
		<?php foreach($answers as $_answer) : ?>
		<p>
	    <?php
	        echo $this->html->link($_answer['Post']['title'], '/questions/' . $_answer['Post']['public_key'] . '/' . $_answer['Post']['title']);
		?>
		</p>
		<?php endforeach; ?>
	</div><!-- end answers tab -->
	
	
</div><!-- end tabs-->

</div>