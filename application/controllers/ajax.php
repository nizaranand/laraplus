<?php

class Ajax_Controller extends Post_Extension_Controller{
  public $restful = true;

  public function post_validate_registration(){
    $firstname = Input::get('firstname');
    $lastname = Input::get('lastname');
    $email = Input::get('email');
    $password = Input::get('password');
    $gender = Input::get('gender');

    $account_id = UserAccounts::insert_new_account($firstname, $lastname, $email, $password, $gender);
    Auth::login($account_id);
    $return['error'] = false;
    $return['msg'] = URL::base();

    echo json_encode($return);
  }

  public function post_validate_login(){
    $email = Input::get('email_login');
    $password = Input::get('password_login');

    $credentials = array(
      'username' => $email,
      'password' => $password
    );
    
    if(empty($email) || empty($password)){
      $errors[] = 'Empty fields!';
    }else{
      if(!UserAccounts::check_email($email)){
        $errors[] = 'Username or password is incorrect.';
      }else{
        if(!Auth::attempt($credentials)) {
          $errors[] = 'Username or password is incorrect.';
        }
      }
    }

    if(!empty($errors)){
      foreach($errors as $error){
        $return['error'] = true;
        $return['msg'] = $error;
      }
    }else{
      $return['error'] = false;
      $return['msg'] = URL::base();
    }
    echo json_encode($return);
  }

  public function post_compose_post(){
    sleep(2);

    $input = Input::all();

    if($input['post_type'] == 'photo'){
      if(empty($input['filename'])){
        $errors[] = 'There\'s something wrong and we\'ve already dispatched our team to fix it.';
      }else{
      if(!empty($errors)){
        foreach($errors as $error){
          $return['error'] = true;
          $return['msg'] = $error;
        }
      }else{
        $inserted_post_id = PhotoPost::insert_new_photo_post(Auth::user()->id, $input['filename'], $input['status_box'], $input['date_captured'], $input['filesize'], $input['camera'], $input['exposure'], $input['aperture'], $input['iso_speed']);
        $return['error'] = false;
        $return['msg'] = $inserted_post_id;
      }
      }
    }else if($input['post_type'] == 'status'){
      if(empty($input['status_box'])){
        $errors[] = 'Please input something.';
      }
      if(!empty($errors)){
        foreach($errors as $error){
          $return['error'] = true;
          $return['msg'] = $error;
        }
      }else{
        $inserted_post_id = StatusPost::insert_new_status_post(Auth::user()->id, $input['status_box']);
        $return['error'] = false;
        $return['msg'] = $inserted_post_id;
      }
    }else{
      $errors[] = 'There\'s something wrong and we\'ve already dispatched our team to fix it.';
    }
    echo json_encode($return);
  }

  public function post_upload_photo_post(){
    sleep(2);
    Bundle::start('resizer');

    $valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
    $input = Input::all();
    $name = $input['post_photo_file']['name'];
    $size = $input['post_photo_file']['size'];

    if(!strlen($name)){
      $errors[] = 'Please select an image.';
    }else{
      list($txt, $ext) = explode(".", $name);
      if(!in_array($ext,$valid_formats)){
        $errors[] = 'File type in invalid.';
      }else{
        if($size > (5024*5024)){
          $errors[] = 'Image size is too large!';
        }else{
          $extension = File::extension($input['post_photo_file']['name']);
          $directory = path('public').'photos/'.sha1(Auth::user()->id);
          $filename = $input['post_photo_file']['name'];

          $upload_success = Input::upload('post_photo_file', $directory, $filename);

          $size = getimagesize($directory.'/'.$filename);
          $width = $size[0];  $height = $size[1];
          $target = 490;
          if ($width > $height) {
            $percentage = ($target / $width);
          } else {
            $percentage = ($target / $height);
          }
          
          $width = round($width * $percentage);
          $height = round($height * $percentage);

          $dir = $directory.'/wall';
          if(!file_exists($dir) && !is_dir($dir)){ mkdir($dir, 0777); }

          $success = Resizer::open($directory.'/'.$filename)->resize( $width , $height , 'exact' )->save($directory.'/wall/'.$filename , 90 );

          $targetPath = $directory . '/' . $filename;
          if($upload_success){
            if($extension != 'png' && $extension != 'PNG' && $extension != 'gif' && $extension != 'GIF'){
              $exif = $this->camera_exif($targetPath);
              $return['photo_camera_maker'] = $exif['make'].' '.$exif['model'];
              $return['photo_camera_expo'] = $exif['exposure'];
              $return['photo_taken'] = $exif['date'];
              $return['photo_aperture'] = $exif['aperture'];
              $return['photo_iso'] = $exif['iso'];
            }else{
              $return['photo_camera_maker'] = '-';
              $return['photo_camera_expo'] = '-';
              $return['photo_taken'] = '-';
              $return['photo_aperture'] = '-';
              $return['photo_iso'] = '-';
            }
            $return['filesize'] = $size;
            $return['filename'] = $input['post_photo_file']['name'];
            $return['file_location'] = URL::base().'/public/photos/'.sha1(Auth::user()->id).'/'.$input['post_photo_file']['name']; 
          }else{
            $errors[] = 'There\'s something wrong and we\'ve already dispatched our team to fix it.';
          }
        }
      }
    }
    if(!empty($errors)){
      foreach($errors as $error){
        $return['error'] = true;
        $return['msg'] = $error;
      }
    }else{
      $return['error'] = false;
      $return['msg'] = false;
    }
    echo json_encode($return);
  }

  public function post_inserted_post(){
    $latest_id = Input::get('latest_id');
    $type = Input::get('type');
    if(empty($latest_id)){
      echo 'aww';
    }else{
      if($type == 'post'){
        $post_stream = Post::get_specific_post($latest_id);
        return View::make('ajax.inserted_post')->with('post_stream', $post_stream)->with('post_type', $type);
      }else if($type == 'comment'){
        $comment_details = Comment::get_comment($latest_id);
        return View::make('ajax.inserted_post')->with('post_type', $type)->with('comment_details', $comment_details);
      }
    }
  }

  public function post_add_comment(){
    sleep(2);

    $commenter_id = Input::get('commenter_id');
    $post_id = Input::get('post_id');
    $comment = Input::get('comment_box');

    if(empty($comment)){
      $errors[] = 'Comment is empty. Please input something.';
    }

    if(!empty($errors)){
      foreach($errors as $error){
        $return['error'] = true;
        $return['msg'] = $error;
      }
    }else{
      $comment_id = Comment::insert_new_comment($post_id, $commenter_id, $comment);
      $return['error'] = false;
      $return['msg'] = false;
      $return['comment_id'] = $comment_id;
    }
    echo json_encode($return);
  }

  public function post_edit_comment(){
    sleep(2);
    $comment_id = Input::get('comment_id');
    $commenter_id = Input::get('commenter_id');
    $new_comment = Input::get('edit_comment_box');

    if(empty($new_comment)){
      $errors[] = 'Comment is empty. Please input something.';
    }

    if(!empty($errors)){
      foreach($errors as $error){
        $return['error'] = true;
        $return['msg'] = $error;
      }
    }else{
      Comment::update_commment($comment_id, $commenter_id, $new_comment);
      $return['error'] = false;
      $return['msg'] = false;
    }
    echo json_encode($return);
  }

  public function post_delete_post(){
    $post_id = Input::get('post_id');
    $post = Post::getSpecificPost($post_id);

    if(empty($post_id)){
      $errors[] = 'There\'s something wrong and we\'ve already dispatched our team to fix it.';
    }else{
      if(!$post){
        $errors[] = 'That post doesn\'t exists.';
      }
    }

    if(!empty($errors)){
      foreach($errors as $error){
        $return['error'] = true;
        $return['msg'] = $error;
      }
    }else{
      if($post->post_type == 'photo'){

      }else if($post->post_type == 'status'){
        StatusPost::deleteStatusPost($post->ref_post_type_id);
      }
      if(Comment::count_post_comments($post->post_id) != 0){
        Comment::deleteComments($post->post_id);
      }
      Post::deletePost($post->post_id);
      $return['error'] = false;
      $return['msg'] = false;
    }
    echo json_encode($return);
  }

  public function post_test_push(){
    $test = Input::get('test');
    $time = date('h:i A');
    echo 'System Clock: '. $time;
  }
}

?>