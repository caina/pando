<?php 
class Snippet_model extends Authenticated_model{

	public function __construct(){
		parent::__construct(); 
		$this->table_name = "snippet";
     }

 }
 ?>