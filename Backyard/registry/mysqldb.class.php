<?php
/**
*database class /access calss basic abstraction
*/

class mysqldb{
  /**
  *allows multiple database connections
  *each connection is stored as an element in the array and the active connection
  *is maintained in a variable
  */
  private $connection=array();
  /**
  *tells the database object which connection to use
  *set active connection ($id) allows us to change this
  */
  private $activeconnection=0;
  /**
  *the quiries that are executed and for later results cached
  *keeped for use within template engine
  */
  private $querycache=array();
  /**
  *that that was prepared(not executed) but cached for later
  *within template engine
  */
  private $datacache=array();
  /**
  *number of queries made during execution process
  */
  private $querycounter=0;

  /**
  *record of the last query
  */
  private $last;

  /**
  *reference to registry object
  */
  private $registry;
  /**
  *construct our database object
  */
  public function __construct(Registry $registry){

    $this-> registry=$registry;
  }

  /**
  *create a new database connection
  *@param string database hostname
  *@param string database username
  *@param string database password
  *@param string database dbname
  *@return int the id of the new connection
  */

  public function newConnection($host,$user,$password,$database){

    $this->connections[]=new mysqli($host,$user,$password,$database);
    $connection_id=count($this->connections)-1;
    echo"connection success";
    if (mysqli_connect_errno()) {
      trigger_error('error connecting to the host. '.$this->connections[$connection_id]->error,E_USER_ERROR);
    }
    return $connection_id;
  }
  /**
  *change which database is actively used for the next operation
  *@param int the new connection id
  *@return void
  */
  public function setActiveConnection(int $new){
    $this->activeconnection=$new;
  }
  /**
  *executing a query string
  *@param string the query
  *@return void
  */
  public function executeQuery($querystring){
    if (!$result=$this->$connections[$this->$activeconnection]->query($querystr)) {
        trigger_error('error executing query '.$querystr. '-' .$this->$connections[$this->activeconnection]->error,E_USER_ERROR);
    }
    else {
      $this->last=$result;
    }
  }
  /**
  *get the rows from the most recently executed query,excluding cached queries
  *@return array
  */
public function getRows(){
  return $this->last->fetch_array(MYSQLI_ASSOC);
}
/**
*delete records from the database
*@param string table to remove rows from
*@param string the condition for which rows are to be removed
*@param int the number of rows to be removed
*@return void
*/
public function deleteRecords($table,$condition,$limit){
$limit=($limit=='')?'':'LIMIT'.$limit;
$delete="DELETE FROM {$table} WHERE {$condition} {$limit} ";
$this->executeQuery($delete);
}
/**
*update records in the db
*@param string the table
*@param array of changes field=>value
*@param string the condition
*@return bool
*/
public function updateRecords($table ,$changes,$condition){
$update="UPDATE".table."SET";
foreach($changes as $field =>$value){
$update =" ".$field."='{value}',";
   }
   //remove our trailing
   $update=substr($update,0,-1);
   if ($condition!='') {
     $update.="WHERE".$condition;
   }
   $this->executeQuery($update);
   return true;

 }
 /**
 *insert records into the database
 *@param string the database table
 *@param array data to insert field =>value
 *@return bool
 */
 public function insertRecords($table,$data){
 //setub some variables for fields and values
 $fields="";
 $values="";
 //populate them
foreach( $data as $f => $v) {
  $fields .="'$f',";
  $values.=(is_numeric($v) && (intval($v)==$v))?
  $v.",":"'v',";
}
//remove our trailing
$fields=substr($fields,0,-1);
//remove our trailing
$values=substr($values,0,-1);
$insert="INSERT INTO $table ({$fields}) VALUES ({$values})";
echo $insert;
$this->executeQuery($insert);
return true;
 }

 /**
 *sanitize data
 *@param string the data to be sanitized
 *@return string the sanitized data
 */
 public function sanitizeData($value){
   //stripslashes
   if (get_magic_quotes_gpc()) {
     // code...
     $value=stripslashes($value);
   }
   //quote value
   if (version_compare(phpversion(),"4.3.0")=="-1") {
     // code...
     $calue=$this->connections[$this ->activeconnection]->escape_string($value);
   }
   else {

    $value=$this->connections[$this->activeconnection]->real_escape_string($value);
   }
   return $value;
  }
  /**
  *get the rows from the most recently executed query,excluding cached
  *@return array
  */

   //public function getRows(){

   //return $this->last->fetch_array(MYSQLI_ASSOC);
  //}

  public function numRows(){
    return $this->last->num_rows;
  }
  /**
  *get the number of affected rows in the prev query
  *return int the number of affected rows
  */
  public function affectedRows(){

    return $this->last->affectedRows;
  }
/**
*now to disconnecting the class
*disconnect the object
*close all sessions
*/
public function __deconstruct(){
foreach($this->connections as $connection){
  $connection->close();
}
}

}

?>
