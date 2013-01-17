<?php

class Post extends Eloquent{
	public static function insert_new_post($owner_id, $ref_owner_id, $orig_post_type_id, $ref_post_type_id, $post_type, $shared_post){
		return DB::table('tbl_posts')->insert_get_id(array('owner_id'=>$owner_id, 'ref_owner_id'=>$ref_owner_id, 'orig_post_type_id'=>$orig_post_type_id, 'ref_post_type_id'=>$ref_post_type_id, 'post_type'=>$post_type, 'shared_post'=>$shared_post, 'datetime_created'=>DB::raw('NOW()'), 'timestamp_created'=>DB::raw('UNIX_TIMESTAMP()')));
	}

	public static function get_posts(){
		return DB::table('tbl_posts')->order_by('post_id', 'desc')->get();
	}

	public static function get_specific_post($post_id){
		return DB::table('tbl_posts')->where('post_id', '=', $post_id)->get();
	}

  public static function deletePost($post_id){
    DB::table('tbl_posts')->where('post_id', '=', $post_id)->delete();
  }

  public static function getSpecificPost($post_id){
    return DB::table('tbl_posts')->where('post_id', '=', $post_id)->first();
  }
}

?>