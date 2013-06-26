<div id="main_content">
	<div class="title">
		<h2>Tag - "<?php echo $tag_name;?>"</h2>
	</div>	
<?php
if(!empty($questions)) {
foreach($questions as $question) { ?>
<div class="list_question wrapper">
	<div class="wrapper" style="float: left;">
	<div class="list_answers <?= (count($question['Answer']) < 1) ? 'list_answers_unanswered' : 'list_answers_answered';?>">
		<span class="large_text"><?=count($question['Answer']);?></span>
		<span><?php echo __n('answer','answers',count($question['Answer']))?></span>
	</div>
	<div class="list_views views">
		<span class="large_text"><?=$question['Post']['views'];?></span>
		<span><?php echo __n('view','views',$question['Post']['views']);?></span>
	</div>
	<div class="list_votes votes">
		<span class="large_text"><?=$question['Post']['votes'];?></span>
		<span><?php echo __n('vote','votes',$question['Post']['votes']);?></span>
	</div>
	</div>
	
	
	<div class="wrapper list_detail_text">
		<div class="list_title  wrapper">
		<?=$this->html->link(
				$question['Post']['title'],
				'/questions/' . $question['Post']['public_key'] . '/' . $question['Post']['title']
			);
		?>
		</div>
		<div class="wrapper">
			<div style="float: right;">
				
				<div style="float: left; line-height: 1.1;">
					<div style="text-align: right;">
			<?=$this->html->link(
					$question['User']['username'],
					'/users/' . $question['User']['public_key'] . '/' . $question['User']['username']
				);
			?>
					</div> 
			<span class="quiet"><?=$this->time->timeAgoInWords($question['Post']['created']);?></span>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
		<div class="wrapper tags">
		<? foreach($question['Tag'] as $tag) { ?>
			<div class="tag wrapper">
				<?=$this->html->link(
						$tag['tag'],
						'/tags/' . $tag['tag']
					);
				?>
			</div>
		<?  } ?>
		</div>
	</div>
	
</div>
<? } ?>
 
 <?php if($end_page <> 1) { ?>
<div class="paging">
	<?php if($current != 1) {?>
		<span class="left"><?php echo $this->Html->link('Prev','/tags/'. $tag_name . '/'. $previous . '/search:' . $search);?> &nbsp;</span>
	<?php } ?>
	
	<?php 
		if($current < 5 || $end_page < 7) {	
			$stop = 5;
			if($end_page < 5)
				$stop = $end_page;
			$i = 1;
			while($i <= $stop){
				if($i == $current )
					echo $current;
				else 
					echo $this->Html->link($i,'/tags/'. $tag_name . '/'. $i);	
				$i++;
			}
	 	} else {
	 		echo $this->Html->link(1,'/tags/'. $tag_name . '/1/');
			echo ".....";
	 	}  
	 	if($current > 4 && ($end_page-$current) > 3) {
			$i = $current -2;
			$stop = $current + 2;	
			while($i <= $stop){
				if($i == $current )
					echo '<b>' . $current . '</b>';
				else 
					echo $this->Html->link($i,'/tags/'. $tag_name . '/'. $i);	
				$i++;
			} 
		}
		if(($end_page-$current) < 4 && $current > 5 && $end_page <> 6) {	
			$stop = $end_page;
			$i = $end_page - 4;
			while($i <= $stop){
				if($i == $current )
					echo '<b>' . $current . '</b>';
				else 
					echo $this->Html->link($i,'/tags/'. $tag_name . '/'. $i);	
				$i++;
			}
	 	} elseif ($end_page > 5) {
	 		if($end_page <> 6) {
	 			echo ".....";
			}
			if($end_page == $current)
	 			echo '<b>' . $current . '</b>';
			else
				echo $this->Html->link($end_page,'/tags/'. $tag_name . '/' . $end_page);
	 	}
	
	 ?>
	<?php if($current != $end_page) {?>
		<span class="left"><?php echo $this->Html->link('Next','/tags/'. $tag_name . '/'. $next);?> &nbsp;</span>
	<?php } ?>
</div>
<?php } ?></div>

<?php } ?>