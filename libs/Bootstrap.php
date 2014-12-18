<?php
/*
* Name of file: Bootstrap V1.0
* Author: Zoran Vulanovic
* Adress: Danila Bojovica 131
* City:  21460 Vrbas,
* Region:Vojvodina
* Country Srbija
* tel: +381 60 505 15 78
*/
  class Bootstrap {

    private $_url = null;
    private $_controller = null;

    function __construct(){
      $this->_getURL();
      if(empty($this->_url[0])){
        $this->_loadDefaultController();
        return false;
      }
      $this->_loadMineController();
      $this->_callControlerMethod();
    }

    /*
    * Geting URL and explode it into peaces by /
    */
    private function _getURL() {
      $url = isset($_GET['url']) ? $_GET['url'] : null;
      $url = rtrim($url, '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $this->_url = explode('/', $url);
    }

    /*
    *Loading default controller if no exists method in adresbar
    */
    private function _loadDefaultController() {
      require 'controllers/index.php';
      $this->_controller = new Index();
      $this->_controller->index();
      return false;
    }

    /*
    *Call, check controler by name from URL and require it if exists and init object by class name
    */
    private function _loadMineController() {
     $file = 'controllers/'  . $this->_url[0] . '.php';
     if(file_exists($file)){
       require $file;
       $this->_controller = new $this->_url[0];
       $this->_controller->loadModel($this->_url[0]);
     } else {
      $this->error();
    }
  }

  /*
  *Checking if method function exists in class and initialize it with param set or not set
  */
  private function _callControlerMethod() {
   if(isset($this->_url[2])){
     if(method_exists($this->_controller, $this->_url[1])){
       $this->_controller->{$this->_url[1]}($this->_url[2]);
     } else {
       $this->error();
       return false;
     }
   } else {
     if(isset($this->_url[1])){
      if(method_exists($this->_controller, $this->_url[1])){
        $this->_controller->{$this->_url[1]}();
      } else {
        $this->error();
        return false;
      }
    } else {
     $this->_controller->index();
   }
 }
}

/*
*Error Controler to set error page. Go to Error controller to set a new one for error view  or change it one 
*/
private function error() {
  require 'controllers/error.php';
  $this->_controller = new Error();
  $this->_controller->index();
  exit;
}
  }
?>