<?php

class CommentLoader{
	public static function count_comments($post_id){
		return Comment::count_post_comments($post_id);
	}

	public static function get_comments($post_id){
		return Comment::get_post_comments($post_id);
	}
}