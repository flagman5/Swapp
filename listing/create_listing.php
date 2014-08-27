<?php

	//Call Database & Connect
	require_once('../login/headers/database.php');
	connect();
   
   $category = $_POST["category"];
   $listing_name = trim($_POST["listing_name"]);
   $listing_description = trim($_POST["listing_description"]);
   $user_id = $_POST["user"];
   $listing_condition = trim($_POST["listing_condition"]);
   $listing_location = trim($_POST["listing_location"]);
   
   //make sure name is supplied
   if(empty($listing_name)) {
		$result = 4;
	}
	else if(empty($listing_description)) {
		$result = 5;
	}
	else {
		// Edit upload location here
	   $destination_path = "uploaded_images/";
	
	   //check file type
	   $type =  strtolower($_FILES['listing_picture']['type']);
	   if($type == 'image/jpg' or $type == 'image/jpeg' or $type == 'image/jpe' or $type == 'image/gif' or $type == 'image/bmp' or $type == 'image/png' or $type == 'image/tiff' or $type == 'image/tif') 
	   {
			//check size
			if ($_FILES['listing_picture']['size'] < 500000)
			{
				//get a random number
				$random = mt_rand();
				$file_name = $random. '_' . $name ."_". $_FILES['listing_picture']['name'];
			   $target_path = $destination_path . $file_name;
				
			   if(@move_uploaded_file($_FILES['listing_picture']['tmp_name'], $target_path)) {
				  $result = 1;
			   }
			   
			   //do dbase stuff
			
				//insert into dbase
				$query = "INSERT INTO products VALUES(NULL,'".$user_id."','".$category."','".$listing_name."','".$listing_description."','".$listing_condition."','".$listing_location."','".$file_name."', NOW())";
					
				$result = mysql_query($query) or die(mysql_error());
			   
			   sleep(1);
			   
			}
			else {
				//file too large
				$result = 2;
			}
		}
		else {
			//bad file type
			$result = 3;
		}
	}
?>

<script language="javascript" type="text/javascript">window.top.window.stopUpload(<?php echo $result; ?>);</script>   
