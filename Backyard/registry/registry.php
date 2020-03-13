<?php
/**
 * php social network site
 * this is registry class
 */
class Registry{
/**
* array of objects
*/
private $objects;
/**
* array of settings
*/
private $settings;

public function __construct(){
}
/**
* create a new object and store it in the registry
* @param string $object the object file prefix
* @param string $key pair for the object
* @return void
 */
public function createandstoreobject($object,$key){
  require_once($object . '.class.php');
  $this->objects[ $key ]=new $object($this);
}
/**
*store settings
*@param string $settings the setting data
*@param string $key the key pair for the settings array
*@return void
*/
public function storesetting($setting,$key){

  $this->$settings[ $key ]=$setting;
}
/**
*get a setting from the registries store
*@param string $key the settings array key
*@return string the setting data
*/
public function getsetting($key){
  return $this->settings[$key];
}
  /**
  *get an object from the registries store
  *@param string $key the objects array code
  *@return object
  */
  public function getObject( $key ) {

     return $this->objects[ $key ]; 
  }
}
 ?>
