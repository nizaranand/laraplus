<?php

class StatusPost extends Eloquent{
	public static function insert_new_status_post($owner_id, $status_post){
		$status_post_id = DB::table('tbl_status_posts')->insert_get_id(array('owner_id' => $owner_id, 'status_post'=>$status_post, 'datetime_posted'=>DB::raw('NOW()'), 'timestamp_posted' => DB::raw('UNIX_TIMESTAMP()')));

		$post_id = Post::insert_new_post($owner_id, $owner_id, $status_post_id, $status_post_id, 'status', 'no');
		return $post_id;
	}

	public static function get_status_post_details($owner_id, $status_id){
		return DB::first("SELECT * FROM tbl_accounts a, tbl_status_posts b WHERE a.id = b.owner_id AND b.owner_id = ? AND b.status_post_id = ?", array($owner_id, $status_id));
	}

  public static function deleteStatusPost($status_id){
    DB::table('tbl_status_posts')->where('status_post_id', '=', $status_id)->delete();
  }
}

?>