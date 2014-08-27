<?php
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	
if($is_admin == false){ die();}

if($first_var == 'Remove'){
	$remove = mysql_query("DELETE FROM `memb_customfds` WHERE `field_id`='$second_var' LIMIT 1");
	if($remove){
		$message = 'Field removed.<br/>';
		$get_users = mysql_query("SELECT `user_id`,`custom_fields` FROM `memb_userlist`");
		while($each = mysql_fetch_assoc($get_users)){
			$new_array = array();
			$lines = explode("\n",$each['custom_fields']);
			foreach($lines as $this_line){
				if(strstr($this_line, "[$second_var]{+|%|+}")){
					array_push($new_array,$this_line);
				}
			}
			$update = mysql_query("UPDATE `memb_userlist` SET `custom_fields`='".implode("\n",$new_array)."'
				WHERE `user_id`='$each[user_id]' LIMIT 1");
		}
	}else{$message = 'Unable to remove field ID#'.$second_var.'.';}
}

?>
<form name="fields" id="fields" method="post" action="?Fields" style="display:inline;">
  <table width="100%"  border="1" cellspacing="0" cellpadding="5">
    <tr>
      <td colspan="2"><div align="center"><font color="#999999" size="4"><strong>Custom Profile Fields: </strong></font></div></td>
    </tr>
   <?php
	if($message != NULL){
	?>
  <tr bgcolor="#FFDDDD">
    <td colspan="2"><strong><font color="#FF0000"><?=$message;?></font></strong></td>
  </tr>
  <?php } ?>
    <tr>
      <td><font color="#0000FF"><strong>Field Names: </strong></font></td>
      <td>&nbsp;</td>
    </tr>
	<?php
	$get_fields = mysql_query("SELECT * FROM `memb_customfds`");
	$recrod_nums = mysql_num_rows($get_fields);
	if($recrod_nums <= 0){
		echo '<tr><td colspan="2"><strong>No custom fields found.</strong></td></tr>';
	} else {
		while($each = mysql_fetch_assoc($get_fields)){?>
    <tr>
      <td width="50%">Custom Field ID#<?=$each['field_id'];?>: </td>
      <td width="50%"><input name="fieldxy_<?=$each['field_id'];?>" type="text" id="fieldxy_<?=$each['field_id'];?>" value="<?=$each['field_name'];?>" disabled>
        <?php	if($each['is_required'] == 1){$sel=' checked';}else{$sel=NULL;}?>
		<input name="fieldxz_<?=$each['field_id'];?>" type="checkbox" id="fieldxz_<?=$each['field_id'];?>" value="1"<?=$sel;?> disabled>
Required        [<a onClick="
document.getElementById('fieldxy_<?=$each['field_id'];?>').disabled=false;
document.getElementById('fieldxz_<?=$each['field_id'];?>').disabled=false;" style="cursor:pointer;"><font color="#0000FF">Edit</font></a>] [<a style="cursor:pointer;" onclick="if(window.confirm('Are you sure you want to remove this field? Doing so will remove this field from ALL user profiles.')){window.location.href='?Fields*Remove*<?=$each['field_id'];?>';}"><font color="#0000FF">Remove</font></a>] </td>
    </tr>
	<?php } 
	}?>
    <tr>
      <td colspan="2"><font color="#0000FF"><strong>Add new field: </strong></font></td>
    </tr>
    <tr>
      <td width="50%">Field Name: </td>
      <td width="50%"><input name="newinput" type="text" id="newinput"> <input name="is_required" type="checkbox" id="is_required" value="1">
      Required</td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
        <input type="submit" name="Submit" value="Update/Add">
        <input name="doid" type="hidden" id="doid" value="4">
      </div></td>
    </tr>
  </table>
</form>
