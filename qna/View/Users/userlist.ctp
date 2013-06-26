<div id="main_content">
	<?=$this->form->create('User', array('action' => '?'));?>
<?=$this->form->label('username');?>
 <?=$this->form->text('username', array('style' => 'padding:2px;margin:0;'));?>
  <?=$this->form->end();?><br/>
<div id="body" class="wrapper">
    <table>
        <tr>

<?php  if(!isset($loop_fuel)) {
    $loop_fuel = -1;
    }
    $i = $loop_fuel;
     foreach($users as $key => $value) {
        $key = $i + 1;
        if(!isset($users[$key])) {
            break;
        }
?>
            <td style="width: 200px; padding: 5px;">
                <div class="users">
                <div class="thumb_with_border">
		
				<?php echo $this->html->link( $this->thumbnail->get(array(
						        'save_path' => WWW_ROOT . 'img/thumbs',
						        'display_path' => $this->webroot.  'img/thumbs',
						        'error_image_path' => $this->webroot. 'img/answerAvatar.png',
						        'src' => WWW_ROOT .  $users[$key]['image'],
						        'w' => 25,
								'h' => 25,
								'q' => 100,
		                        'alt' => $users[$key]['username'] . 'picture' )
			),'/users/' .$users[$key]['public_key'].'/'.$users[$key]['username'], array('escape' => false));?>
				</div>
                <?php echo $this->html->link(
				$users[$key]['username'],
				'/users/' . $users[$key]['public_key'] . '/' . $users[$key]['username']
			);
		?></div>
            </td>
<?php      $i++;
        if($i < 5 && $i > 0) {
            if($i % 4 == 0) {
?>
        </tr>
        <tr>
<?php
            }
        }elseif($i > 5) {
            if(($i - 4) % 5 == 0) {
?>
        </tr>
        <tr>
<?php          }
        }
        if($i - $loop_fuel == 100) {
            break;
        }
    }

?>
        </tr>
    </table>
</div>
<?php if ($end_page <> 1) { ?>
<div class="paging">
	<?php if($current != 1) {?>
		<span class="left"><?php echo $this->Html->link('Prev','/users/username:'. $username . '/page:'. $previous);?> &nbsp;</span>
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
					echo $this->Html->link($i,'/users/username:'. $username . '/page:'. $i );	
				$i++;
			}
	 	} else {
	 		echo $this->Html->link(1,'/users/username:'. $username . '/page:1/search:' . $search);
			echo ".....";
	 	}  
	 	if($current > 4 && ($end_page-$current) > 3) {
			$i = $current -2;
			$stop = $current + 2;	
			while($i <= $stop){
				if($i == $current )
					echo '<b>' . $current . '</b>';
				else 
					echo $this->Html->link($i,'/users/username:'. $username . '/page:'. $i );	
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
					echo $this->Html->link($i,'/users/username:'. $username . '/page:'. $i );	
				$i++;
			}
	 	} else {
	 		if($end_page <> 6) {
	 			echo ".....";
			}
			if($end_page == $current)
	 			echo '<b>' . $current . '</b>';
			else
				echo $this->Html->link($end_page,'/users/username:'. $username . '/page:' . $end_page);
	 	}
		

		 
	 ?>
	<?php if($current != $end_page) {?>
		<span class="left"><?php echo $this->Html->link('Next','/users/username:'. $username . '/page:'. $next);?> &nbsp;</span>
	<?php } ?>
</div>
<?php } ?>
</div>
