<div id="main_content">
	<div class="title">
		<h2>Tags</h2>
	</div>
<div id="body" class="wrapper">
    <table>
        <tr>

<?php  if(!isset($loop_fuel)) {
    $loop_fuel = -1;
    }
    $i = $loop_fuel;
     foreach($tag as $key => $value) {
        $key = $i + 1;
        if(!isset($tag[$key])) {
            break;
        }
?>
            <td style="width: 200px; padding: 5px;">
                <div class="tag">
                <?php echo $this->html->link(
				$tag[$key]['tag'],
				'/tags/' . $tag[$key]['tag']
			);
		?> x <?php echo $tag[$key]['count'];?></div>
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
		<span class="left"><?php echo $this->Html->link('Prev','/search/type:'. $type . '/page:'. $previous . '/search:' . $search);?> &nbsp;</span>
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
					echo $this->Html->link($i,'/search/type:'. $type . '/page:'. $i  . '/search:' . $search);	
				$i++;
			}
	 	} else {
	 		echo $this->Html->link(1,'/search/type:'. $type . '/page:1/search:' . $search);
			echo ".....";
	 	}  
	 	if($current > 4 && ($end_page-$current) > 3) {
			$i = $current -2;
			$stop = $current + 2;	
			while($i <= $stop){
				if($i == $current )
					echo '<b>' . $current . '</b>';
				else 
					echo $this->Html->link($i,'/search/type:'. $type . '/page:'. $i  . '/search:' . $search);	
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
					echo $this->Html->link($i,'/search/type:'. $type . '/page:'. $i  . '/search:' . $search);	
				$i++;
			}
	 	} else {
	 		if($end_page <> 6) {
	 			echo ".....";
			}
			if($end_page == $current)
	 			echo '<b>' . $current . '</b>';
			else
				echo $this->Html->link($end_page,'/search/type:'. $type . '/page:' . $end_page . '/search:' . $search);
	 	}
		

		 
	 ?>
	<?php if($current != $end_page) {?>
		<span class="left"><?php echo $this->Html->link('Next','/search/type:'. $type . '/page:'. $next . '/search:' . $search);?> &nbsp;</span>
	<?php } ?>
</div>
<?php } ?>
</div>
