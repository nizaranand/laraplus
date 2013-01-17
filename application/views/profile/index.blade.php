@layout('layout.default')
@section('title') {{ $account_details->firstname.' '.$account_details->lastname }} on Lara + @endsection
@section('internal_css')
<style>
  #profile_header_holder{
    /*border: 1px solid #ccc;*/
    height: 500px;
  }
  #profile_cover_holder{
    overflow: hidden;
    height: 350px;
    /*border: 1px solid #ccc;*/
  }
  .btn_change_cover{
    position: relative;
    z-index: 500;
    margin-top: -120px;
    margin-left: 20px;
  }
  #profile_user_pic{
    position: relative;
    z-index: 500;
    margin-top: -120px;
    margin-right: 20px;
  }
  #profile_user_name{
    color: #ffffff;
    position: relative;
    z-index: 500;
    text-shadow: 1px 1px #000;
    margin-top: -60px;
    margin-right: 20px;
  }
  #profile_controls_holder{
    padding: 5px 0 0 20px;
  }
  #profile_controls_holder .btn{
    margin-right: 5px;
  }
</style>
@endsection
@section('content')
<div id="notification_holder">
  <div id="message_holder"></div>
</div>
<div id="profile_header_holder">
  <div id="profile_cover_holder">
    <img src="<?php echo URL::base();?>/public/coverphotos/sample_cover.JPG" width="1100px"/>
    <button class="btn btn_change_cover">Change cover</button>
  </div>
  <div id="profile_user_info">
    <div id="profile_user_pic" class="pull-right">
      <img src="{{ URL::base() }}/public/defaults/default.jpg" width="180px" class="img-polaroid"/>
    </div>
    <h2 id="profile_user_name" class="pull-right">{{ $account_details->firstname.' '.$account_details->lastname }}</h2>

    <div id="profile_controls_holder">
      <button class="btn">Update profile</button>
      <button class="btn">Activity Log</button>
    </div>

  </div>
</div>
<div id="main_profile_holder" class="row">

</div>
@endsection
@section('js_scripts')

@endsection