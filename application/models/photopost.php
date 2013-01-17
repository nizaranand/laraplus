<?php

class PhotoPost extends Eloquent{
	public static function insert_new_photo_post($owner_id, $filename, $photo_caption, $date_captured, $filesize, $camera, $exposure, $aperture, $iso_speed){
		$photo_post_id = DB::table('tbl_photo_posts')->insert_get_id(array('owner_id' => $owner_id, 'filename' => $filename, 'photo_caption' => $photo_caption, 'date_captured' => $date_captured, 'filesize' => $filesize, 'camera' => $camera, 'exposure' => $exposure, 'aperture' => $aperture, 'iso_speed' => $iso_speed, 'datetime_posted'=>DB::raw('NOW()'), 'timestamp_posted'=>DB::raw('UNIX_TIMESTAMP()')));

		$post_id = Post::insert_new_post($owner_id, $owner_id, $photo_post_id, $photo_post_id, 'photo', 'no');
		return $post_id;
	}

	public static function get_photo_post_details($owner_id, $photo_id){
		return DB::first("SELECT * FROM tbl_accounts a, tbl_photo_posts b WHERE a.id = b.owner_id AND b.owner_id = ? AND b.photo_posts_id = ?", array($owner_id, $photo_id));
	}
}

?>