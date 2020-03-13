<?php
class Register{
/**
*standard registration fields
*/
private $fields=array('user'=>'username','password'=>'password','password_confirm'=>'password_confirmation','email'=>'email_address');

/**
*anyerrors in the registration
*/
private $registrationErrors=array();

/**
*the values the user submitted when registering
**/
$private $submittedValues=array();

/**
*the sanitized versions of the values the user has submitted-the once those database ready
*/
private $sanitizedValues=array();

/**
*any error label classes - allows us to make a field different color to indicate there was an error
*/

private $registerationErrorLabels=array();

/**
*should our users be automatically active or should they require mail verification
*/

private $activeValue=1;

private function checkRegisteration(){
$allClear=true;
//blank fields

  foreach($this->fields as $fields->name){
if(!isset ($_POST['register_'.$field]))||$_POST['register_' .$field]==''){


  $allClear=false;
  $this->registerationErrors[]='you must enter a'.$name;
  $this->$registerationErrorLabels['register_'.$field.'_label']='error';
  }
 }
 //password match
 if ($_POST['register_password']!=$_POST['register_password_confirm']) {
   $allClear=false;
   $this->registerationErrors[]='you must confirm your password';
   $this->$registerationErrorLabels['register_password_label ']='error';
   $this->$registerationErrorLabels['register_password_confirm_label ']='error';
 }
 //password length
 if (strlen($_POST['register_password'])<6) {
   $allClear=false;
   $this->registerationErrors[]='your password is too short it must be at least 6 chars';
   $this->$registerationErrorLabels['register_password_label']='error';
   $this->$registerationErrorLabels['register_password_confirm_label']='error';
 }
 //email header
 if( strpos(( urldecode( $_POST[ 'register_email' ] ) ), "\r" ) === true || strpos( ( urldecode( $_POST[ 'register_email' ])), "\n" ) === true ){

   $allClear=false;
   $this->registerationErrors[]='your email is not valid (security)';
   $this->$registerationErrorLabels['register_email_label']='error';
 }
//email valid
if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})",$_POST['register_email'])) {
  $allClear=false;
  $this->registerationErrors[]='you must enter a valid email address';
  $this->$registerationErrorLabels['register_email_label']='error';
}
//term accepted
if(! isset($_POST['register_terms'])||$_POST['register terms']!=1){
$allClear=false;
$this->registerationErrors[]='you must accept our terms and conditions';
$this->$registerationErrorLabels['register_terms_label']='error';

}
//duplicate username and email check
$u=$this->registry->getObject('db')->sanitizeData($_POST['register_user']);
$e=$this->registry->getObject('db')->sanitizeData($_POST['register_email']);
$sql="SELECT * FROM users WHERE username='{$u}' OR email='{$e}'"
$this->registry->getObject('db')->executeQuery($sql);
if ($this->registry->getObject('db')->numRows()==2) {
  $allClear=false;
  //both
  $this->registerationErrors[]='this user name and password already exist';
  $this->$registerationErrorLabels['register_user_label']='error';
  $this->$registerationErrorLabels['register_email_label']='error';
}
elseif ($this->registry->getObject('db')->numRows()==) {
  //possibley both of just one
  $u=$this->registry->getObject('db')->sanitizeData($_POST['register_user']);
  $e=$this->registry->getObject('db')->sanitizeData($_POST['register_email']);
  $data=$this->registry->getObject('db')->getRows();
  if ($data=['username']==$u && $data=['email']==$e) {
    $allClear=false;
    $this->$registrationErrors[]='both username and password already exist';
    $this->$registerationErrorLabels['register_user_label']='error';
    $this->$registerationErrorLabels['register_email_label']='error';
    //both
  }
  elseif ($data->['username']==$u) {
    $allClear=false;
    //username
    $this->registerationErrors[]='the username already exist';
    $this->$registerationErrorLabels['register_user_label']='error';
  }
  elseif ($data->['email']==$e) {
    $allClear=false;
    //email
    $this->registerationErrors[]='the email already exist';
    $this->$registerationErrorLabels['register_email_label']='error';
  }
}
//captcha
if ($this->registry->getSetting('captcha.enabled')==1) {
  // captcha check
}
//hook
if ($this->regestraionExtension->checkRegisterationSubmission()==false) {
  $allClear=false;
}

if($allClear=true){
 $this->sanitizedValues['username']=$u;
  $this->sanitizedValues['email']=$e;
   $this->sanitizedValues['password_hash']=md5($_POST['register_password']);
    $this->sanitizedValues['active']=$this->activeValue;
     $this->sanitizedValues['admin']=0;
      $this->sanitizedValues['banned']=0;
$this->submittedValues['register_user']=$_POST['register_user'];
$this->submittedValues['register_password']=$_POST['register_password'];
return true;
}
else {
 $this->submittedValues['register_user']=$_POST['register_user'];
 $this->submittedValues['register_email']=$_POST['register_email'];
 $this->submittedValues['register_password']=$_POST['register_password'];
  $this->submittedValues['register_password_confirm']=$_POST['register_password_confirm'];
  $this->submittedValues['register_captcha']=(isset($_POST['register_captcha']) ? $_POST['register_captcha'] :'' );
return false;
}

}
//hooking additional files


private $registry;
private $errors=array();
private $extraFields=array();
private $submittedValues=array();
private $sanitizedValues=array();
private $errorLabels=array();


public function __construct(){
$this->registry=$regisrty;
$this->extraFields['dino_name']=array('friendlyname'=>'pet Dinosaurs Name','table'=>'profile','field'=>'dino_name','type'=>'text','required'=>false);
$this->extraFields['dino_breed']=array('friendlyname'=>'pet Dinosaurs Breed','table'=>'profile','field'=>'dino_breed','type'=>'text','required'=>false)
$this->extraFields['dino_gender']=array('friendlyname'=>'pet Dinosaurs Gender','table'=>'profile','field'=>'dino_gender','type'=>'list','required'=>false,'options'=>array('male','female'));
$this->extraFields['dino_dob']=array('friendlyname'=>'pet Dinosaurs Date Of Birth','table'=>'profile','field'=>'dino_dob','type'=>'DOB','required'=>false);


}

public function checkRegisterationSubmission(){
  $valid=true;
foreach ($this->extraFields as $fields => $data) {
  if ((! isset($_POST['register'.$field])||$_POST['register'.$field]=='')&& $data['required']=true) {
    $this->submittedValues[$field]=$_POST['register_'.$field];
    $this->errorLabels['register_'.$field.'_label']='error';
    $this->errors[]='Field'.$data['friendlyname'].'cannot be blank';
    $valid=false;
  }
  elseif ($_POST['register_' .$field]=='') {
    $this->submittedValues['register_' .$field]='';

    }
    else {
      if($data['type']=='text'){
      $this->sanitizedValues['register_'.$field]=$this->registry->getObject('db')->sanitizeData($_POST['register_'.$field]);
      $this->submittedValues['register_'.$field]=$_POST['register_'.$field];

      }
      elseif ($data['type']=='int') {
         $this->sanitizedValues['register_'.$field ] =intval($_POST['register_'. $field]);
         $this->submittedValues['register_'.$field]=$_POST['register_'.$field];
      }

      elseif ($data['type']=='list') {
         if (!in_array($_POST['register'.$field],$data->['options'])) {
           $this->submittedValues[$field]=$_POST['register_'.$field];
           $this->errorLabels['register_'.$field. '_label']='error';
           $this->errors[]='field'.$data['friendlyname']. 'was not valid';
           $valid=false;

         }
         else {
           $this->sanitizedValues['register_'.$field]=intval($_POST['register'.$field]);
           $this->submittedValues['register_'.$field]=intval($_POST['register'.$field]);
         }
      }
      else {
        $method='validate_'.$data['type'];
        if ($this->$method($_POST['register_' .$field])==true) {
          $this->sanitizedValues['register_'. $field]=$this->registry->getObject('db')->sanitizeData($_POST['register_'.$field]);
          $this->submittedValues['register_'.$field]=$_POST['register_'.$field];
        }
        else {
         $this->sanitizedValues['register_'. $field]=$this->registry->getObject('db')->sanitizeData($_POST['register_'.$field]);
           $this->submittedValues['register_'.$field]=$_POST['register_'.$field];
           $this->errors[]='Field'.$data['friendlyname'].'wasnt valid';
           $valid=false;

         }
       }
     }
  }
  if ($valid==true) {
    return true;
  }
  else {
    return false;
  }
}

 /**
 *create users profiles and process registerations
 */
private function processRegisteration(){
//insert
$this->registery->getObject('db')->insertRecords('users',$this->sanitizedValues);
//get ID
$uid=$this->registery->getObject('db')->lastInsertID();
//call extension to insert the profile
$this->registerationExtension->processRegisteration($uid);
//return id  for the frameworks reference -autologin
return $uid;
}

/**
*create our user profile
*@param int $uid the user id
*@return bool
*/
public function processRegisteration($uid){
  $tables=array();
  $tableData=array();
  //group our profile field by a table so we only insert per table
  foreach ($this->extraFields as $field => $data) {
    if (! (in_array($data['table'],$tables))) {
      $tables[]=$data['table'];
      $tableData[$data['table']]=array('user_id'=$uid,$data['field']=>$this->sanitizedValues['register_'. $field]);

    }
    else {
      $tableData[$data['table']]=array('user_id'=$uid,$data['field']=>$this->sanitizedValues['register_'. $field]);
    }
  }
  foreach ($tableData as $table => $data) {
    $this->registry->getObject('db')->insertRecords($table,$data);
  }
  return true;
}

}


?>
