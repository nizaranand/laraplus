<?php

class Profile_Controller extends Base_Controller{
	public $restful = true;

	public function get_index($account_id){
		// echo md5($account_id);
		$account_details = UserAccounts::check_or_get_account($account_id);
		if(!$account_details){
			return Response::error('404');
		}
		return View::make('profile.index')->with('account_details', $account_details);
	}
}

?>