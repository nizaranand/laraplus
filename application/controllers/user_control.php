<?php

class User_control_Controller extends Base_Controller{
  public $restful = true;

  public function get_logout(){
    Auth::logout();
    return Redirect::to('/');
  }
}

?>