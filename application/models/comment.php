<?php

class Comment extends Eloquent{
	
	public static function insert_new_comment($post_id, $commenter_id, $comment){
		return DB::table('tbl_comments')->insert_get_id(array('post_id'=>$post_id, 'commenter_id'=>$commenter_id, 'comment'=>$comment, 'datetime_commented'=>DB::raw('NOW()'), 'timestamp_commented'=>DB::raw('UNIX_TIMESTAMP()')));
	}

	public static function get_post_comments($post_id){
		// return DB::table('tbl_comments')->where('post_id', '=', $post_id)->order_by('post_id', 'desc')->get();
		return DB::query("SELECT * FROM tbl_accounts a, tbl_comments b WHERE a.id = b.commenter_id AND b.post_id = ? ORDER BY comment_id ASC", $post_id);
	}

	public static function count_post_comments($post_id){
		return DB::table('tbl_comments')->where('post_id', '=', $post_id)->count();
	}

	public static function get_comment($comment_id){
		return DB::first("SELECT * FROM tbl_accounts a, tbl_comments b WHERE a.id = b.commenter_id AND b.comment_id = ? ORDER BY comment_id ASC", $comment_id);
	}

	public static function update_commment($comment_id, $commenter_id, $comment){
		DB::table('tbl_comments')->where('comment_id', '=', $comment_id)->where('commenter_id', '=', $commenter_id)->update(array('comment'=>$comment));
	}

	public static function deleteComments($post_id){
		DB::table('tbl_comments')->where('post_id', '=', $post_id)->delete();
	}
}

?>