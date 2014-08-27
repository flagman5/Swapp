<?php
/*************************************\
| htpasswd class                      |
| By Jorge Schrauwen 2007             |
| http://www.blackdot.be              |
\*************************************/

/*
Requires:
- PHP >= 4.3.0

Usage:
require('htpasswd.php');

$pwmanager = new htpasswd('.htpasswd');
$pwmanager->create('test', 'pass');
$pwmanager->save();

Functions:
create('MyUser', 'Pass')      : create MyUser with password Pass. (true/false)
remove('MyUser')              : remove MyUser. (true/false)
validate('MyUser', 'Pass')    : will validate the user. (true/false)
users()                     : return and array with the usernames.
save()                        : save the password file. (true/false)
save('/server/www/.htpasswd') : save the password file to /server/www/.htpasswd. (true/false)

Error Handling:
when a function returns false you can get more information via
echo $pwmanager->error;
*/

class htpasswd{
   public $users;
   public $error;
   private $_path;   

   function htpasswd($file=false){
      if(!$file){
         die('Please specify a file!');
      }else{   
         //configure
         $this->_path = $file;
         $this->users = '';
         //load database
         if(file_exists($file)){
            $data = array();
            $fcontents = file($file);
            while(list($line_num, $line) = each($fcontents)){
               $user = explode(':',$line);
               //$user = $arraydata[0];
               $data[$user[0]] = rtrim($user[1]);
            }
            $this->users = $data;
         }
      }
   }
         
   function create($user, $passwd, $update=false){
      $this->error = '';
      if(isset($this->users[$user]) && !$update){
         $this->error = 'User <strong>'.$user.'</strong> exists! To update the user set the update parameter to true.';
         return false;
      }
      $this->users[$user] = $this->non_salted_sha1($passwd);
      return true;
   }
   
   function remove($user){
      $this->error = '';
      if(isset($this->users[$user])){
         unset($this->users[$user]);
         return true;
      }else{
         $this->error = 'User <strong>'.$user.'</strong> does not exist!';
         return false;
      }
   }
   
   function users(){
      $this->error = '';
      $rval = Array();
      if(is_array($this->users)){
         foreach(array_keys($this->users) as $uid){
            $rval[count($rval)] = $uid;
         }
      }
      return $rval;
   }
      
   function validate($user, $pass){
      $this->error = '';
      if(!isset($this->users[$user])) return False;
      $crypted = $this->users[$user];
      
      if(substr($crypted, 0, 6) == "{SSHA}"){
         $ohash = base64_decode(substr($crypted, 6));
         return substr($ohash, 0, 20) == pack("H*", sha1($pass . substr($ohash, 20)));
      }elseif(substr($crypted, 0, 5) == "{SHA}"){
         return ($this->non_salted_sha1($pass) == $crypted);
      }else{
         return ($pass == $crypted);
      }
   }

   function save($file=false){
      $fcontents = "";
      if($file == false) $file = $this->_path;
      foreach(array_keys($this->users) as $user){
         $fcontents .= $user.":".$this->users[$user]."\n";
      }
      if(file_put_contents($file, $fcontents)){
         $this->error = '';
         return true;
      }else{
         $this->error = 'Couln\'t save the file!';
         return false;
      }
   }   

   //encryption functions
   function rand_salt_crypt($pass){
      $salt = "";
      mt_srand((double)microtime()*1000000);
      for ($i=0; $i<CRYPT_SALT_LENGTH; $i++)
         $salt .= substr("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./", mt_rand() & 63, 1);
      return "$apr1$".crypt($pass, $salt);
   }
   
   function rand_salt_sha1($pass){
      mt_srand((double)microtime()*1000000);
      $salt = pack("CCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand());
      return "{SSHA}".base64_encode(pack("H*", sha1($pass . $salt)) . $salt);
   }
   
   function non_salted_sha1($pass){
      return "{SHA}".base64_encode(pack("H*", sha1($pass)));
   }
}

//php4 work around
if(!function_exists('file_put_contents')){
   function file_put_contents($filename, $content, $flags = null, $resource_context = null){
      if(is_array($content)){
         $content = implode('', $content);
      }
      if(!is_scalar($content)){
         trigger_error('file_put_contents() The 2nd parameter should be either a string or an array', E_USER_WARNING);
         return false;
      }
      $length = strlen($content);
      $mode = ($flags &FILE_APPEND) ? 'a' : 'w';
      $use_inc_path = ($flags &FILE_USE_INCLUDE_PATH) ? true : false;
      if(($fh = @fopen($filename, $mode, $use_inc_path)) === false){
         trigger_error('file_put_contents() failed to open stream: Permission denied', E_USER_WARNING);
         return false;
      }
      $bytes = 0;
      if(($bytes = @fwrite($fh, $content)) === false){
         $errormsg = sprintf('file_put_contents() Failed to write %d bytes to %s',
         $length,
         $filename);
         trigger_error($errormsg, E_USER_WARNING);
         return false;
      }
      @fclose($fh);
      if($bytes != $length){
         $errormsg = sprintf('file_put_contents() Only %d of %d bytes written, possibly out of free disk space.',
         $bytes,
         $length);
         trigger_error($errormsg, E_USER_WARNING);
         return false;
      }
      return $bytes;
   }
}
?> 