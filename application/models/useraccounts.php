<?php

class UserAccounts extends Eloquent{
  public static $table = 'tbl_accounts';

  public static function insert_new_account($firstname, $lastname, $email, $password, $gender){
    return DB::table('tbl_accounts')->insert_get_id(array('firstname'=>$firstname, 'lastname'=>$lastname, 'email'=>$email, 'password'=>Hash::make($password), 'gender'=>$gender, 'created_at'=>DB::raw('NOW()')));
  }

  public static function check_email($email){
    return DB::first("SELECT * FROM tbl_accounts WHERE email = ?", array($email));
  }

  public static function check_or_get_account($id){
  	return DB::first("SELECT * FROM tbl_accounts WHERE id = ?", array($id));
  }
}

?>