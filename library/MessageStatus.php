<?php

namespace Library;

class MessageStatus {
  
  const STATUS_ERROR = 0;
  const STATUS_INFO = 1;
  const STATUS_INVALID = 2;
  const STATUS_SUCCESS = 3;

  private $alert = array(
    0 => 'danger',
    1 => 'info',
    2 => 'warning',
    3 => 'success'
  );
  private $message = null;
  private $status = 3;

  public function __construct() {
  }
  
  public function setStatus($status){
    $this->status = $status;
  }
  
  public function setMessage($message) {
    $this->message = $message;
  }
  
  public function getMessage(){
    if($this->message){
      return "<div class='alert alert-".$this->alert[$this->status]."' role='alert'>".$this->message."</div>";
    }
  }
  
  
}
