<?php
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	
if($is_admin == false){ die();}
?>
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td><div align="center"><font size="2"><?php
	$add = "?Users*P*";	

	//To display the numbering anywhere under the page use this code// 
	if($items_count > $display_limit){ 
    $current_page = ($start/$display_limit)+1; 

	$start_page = $current_page - 3;
	if($start_page <=0 ){$start_page=1;}
	$end_page = $start_page + 7;
	if($end_page > $page_count){$end_page = $page_count;}
    //Do we need to print <<
	if($start > 1){
		$first_start = $start - ($display_limit*8);
		if($first_start <=0 ){$first_start=0;}
		$this_add = "$add";
		echo "<strong>&nbsp;<a href=\"".$this_add. '" title="Rewind">&lt;&lt;</a>&nbsp;</strong>'; 
	}
	//Do we need to print previous? 
    if($current_page != 1) { 
		$this_add = $add.($start-$display_limit);
        echo "<strong>&nbsp;<a href=\"".$this_add. '" title="previous">&lt;</a>&nbsp;</strong>'; 
    } 
    //Print out the numbers 
    for ($i = $start_page; $i <= $end_page ; $i++) { 
        if($i != $current_page){ 
			$this_add = $add.(($display_limit * ($i - 1)));
            echo " [<a href=\"".$this_add.  '" title="Page Num: '.$i.'">' . $i . '</a>] ';     
			$last_start  = (($display_limit * ($i - 1)));
        } else { 
            echo '<font color="gray"> [' . $i . '] </font>'; 
        } 
    } 
    //Print out Next 
    if($current_page != $page_count){ 
		$this_add = $add.($start+$display_limit);
        echo  "<strong>&nbsp;<a href=\"".$this_add.'" title="Next">&gt;</a>&nbsp;</strong>'; 
    }         
	//Print out >>
	if(($last_start+($display_limit*8)) > $page_count*$display_limit){$show_end = ($page_count*$display_limit)-$display_limit;}else{$show_end = ($last_start+($display_limit*8));}
	if( ($start+$display_limit)  < $items_count){
		$this_add = "$add$show_end";
		echo  "<strong>&nbsp;<a href=\"".$this_add."\" title=\"Fast Forward\">&gt;&gt;</a>&nbsp;</strong>"; 
	}
} 
?>
      </font></div></td>
  </tr>
</table>