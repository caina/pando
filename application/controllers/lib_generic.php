<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_Generic extends Authenticated_controller {

	 public function __construct(){
        parent::__construct(); 

    }

    function method($folder,$lib,$method,$param1=false,$param2=false,$param3=false){
    	$this->load->library("{$folder}/{$lib}", array(), $lib);
    	$this->$lib->$method($param1,$param2,$param3);
    }


}

?>