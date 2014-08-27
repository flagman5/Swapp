<?php
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	

//Function to Encode/Decode
function encode_decode($data,$keyfile) {
    $pwd = $keyfile;
        $pwd_length = strlen($pwd);
    for ($i = 0; $i < 255; $i++) {
          $key[$i] = ord(substr($pwd, ($i % $pwd_length)+1, 1));
            $counter[$i] = $i;
        }
        for ($i = 0; $i < 255; $i++) {
            $x = ($x + $counter[$i] + $key[$i]) % 256;
            $temp_swap = $counter[$i];
            $counter[$i] = $counter[$x];
            $counter[$x] = $temp_swap;

        }
        for ($i = 0; $i < strlen($data); $i++) {
                        $a = ($a + 1) % 256;
            $j = ($j + $counter[$a]) % 256;
            $temp = $counter[$a];
            $counter[$a] = $counter[$j];
            $counter[$j] = $temp;
            $k = $counter[(($counter[$a] + $counter[$j]) % 256)];
            $Zcipher = ord(substr($data, $i, 1)) ^ $k;
            $Zcrypt .= chr($Zcipher);
        }
        return $Zcrypt;
}

//Function Hexadeciam to Binary
function hex2bin($hexdata) {  
	for ($i=0;$i<strlen($hexdata);$i+=2) {
		$bindata.=chr(hexdec(substr($hexdata,$i,2)));
	}  
	return $bindata;
} 

//Generate session
function generate_session($strlen){
    return substr(md5(uniqid(rand(),1)),1,$strlen);
}

//Get User IP
function get_ip(){
	$ipParts = explode(".", $_SERVER['REMOTE_ADDR']);
	if ($ipParts[0] == "165" && $ipParts[1] == "21") {    
    	if (getenv("HTTP_CLIENT_IP")) {
        	$ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        }
    } else {
       return $_SERVER['REMOTE_ADDR'];
   	}
   	return $ip;
}

//Check Email
function is_valid_email($string) {
	return preg_match('/^[.\w-]+@([\w-]+\.)+[a-zA-Z]{2,6}$/', $string);
}

// Check username
function validate_username($string) {
   return preg_match("/^([a-z0-9])+$/i", $string);
} 

//Send email function
function send_mail($return_name,$return_email,$from_name,$from_email,$subject,$message,$to){
	$headers = "Return-Path: $return_name <$return_email>\r\n"; 
	$headers .= "From: $from_name <$from_email>\r\n"; 
	$headers .= "Content-Type: text/html; charset=utf-8;\n\n\r\n"; 
	return @mail ($to,$subject,$message,$headers);
}

//Function to handle fdaccess file
function editfule($file_loc,$operation,$username,$password,$oldusername){
	include('htpasswd.php');
	
	if($operation == 'Add'){
		$htpw = new htpasswd($file_loc);
		$htpw->create($username, $password);
		$htpw->save(); 
		return;
	} 	

	$htpw = new htpasswd($file_loc);
	
	if($operation == 'Edit'){
		$htpw->remove($oldusername); 
		$htpw->create($username, $password);
	} else {
		$htpw->remove($username); 
	}
	$htpw->save();

	return;
}
?>