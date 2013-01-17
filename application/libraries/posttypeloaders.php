<?php

class PostTypeLoaders{

	public static function status_post($owner_id, $status_id){
		return StatusPost::get_status_post_details($owner_id, $status_id);
	}	

	public static function photo_post($owner_id, $status_id){
		return PhotoPost::get_photo_post_details($owner_id, $status_id);
	}	
}

?>