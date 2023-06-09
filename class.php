<?php
// ─────────────────── class defs

class TodoObject{

  public $title;
  public $priority;
  public $timestamp;
  public $state;
  public $uid;
  public $desc;
  public $sub;

  public function __construct($ttl,$pri = "Normal",$desc = null)
  {
    $this->uid = uniqid(); //no use curr. (v.12)
    $this->timestamp=date_create('now');
    $this->title = $ttl;
    $this->state = "No State";
    $this->priority = $pri;
    $this->desc = $desc;
    $this->sub = []; //sub array 
  }
  
  public function __toString()
  {
    return $this->title;
  }
  
  public function __callable()
  {
    return $this->uid;
  }
  
  public function get_subs(){
  #Returns the sub array
    return $this->sub;
  }

		public function get_details_array(){
		# Returns an array w/ obj's details as strings
		$proparray=[];
		foreach($this as $pname=>$pval):
      $proparray[$pname]=$pval;
		endforeach;
		return $proparray;
  }
 	
}
?>
