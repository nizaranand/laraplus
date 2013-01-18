@layout('layout.default')
@section('title') LaraPlus @endsection
@section('internal_css')
<style>
  #main_body_holder{
    padding: 0 20px;
  }
  /*Left body Holder Styles*/
  #left_body_holder{
    padding: 20px 0;
    width: 170px;
  }
  #left_body_holder img{
    margin-right: 5px;
  }
  #basic_controls{
    margin-top: 10px;
  }
  /*End of Left body Holder Styles*/
  /*Center body holder styles*/
  #center_body_holder{
    margin: 0 -20px 0 0;
    padding: 20px 25px;
    width: 565px;
    border: 1px solid #cccccc;
    border-top: 0;
  }
  #share_box{
    position: relative;
    z-index: 100;
    margin-bottom: 25px;
  }
  #share_box img{
    vertical-align: top;
  }
  #share_form_box{
    display: inline-block;
    border: 1px solid #cccccc;
    padding: 7px;
    min-height: 35px;
    margin-left: 14px;
    background-color: #f9f9f9;
  }
  #compose_post_form{
    margin: 0;
  }
  #status_box{
    height: 25px;
    width: 450px;
    font-size: 13px;
    resize: none;
    color: #000000;
    margin: 0;
    padding-right: 17px;
    overflow:hidden;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    border-radius: 0;
  }
  #status_box::-webkit-input-placeholder {
    color:#999999;
  }
  #status_box:-moz-placeholder {
    color:#999999;
  }
  #status_box:-ms-input-placeholder {
    color:#999999;
  }
  /*End of Center body holder styles*/
  /*Right body Holder Styles*/
  #right_body_holder{
    width: 180px;
    padding: 10px 0;
  }
  /*End of Right body Holder Styles*/
  #share_form_controls{
    display: none;
    margin: auto -8px;
    margin-bottom: -8px;
    padding: 7px;
    background-color: #f2f2f2;
    border: 1px solid #cccccc;
    /*-webkit-border-bottom-right-radius: 5px;
    -webkit-border-bottom-left-radius: 5px;
    -moz-border-radius-bottomleft: 5px;
    -moz-border-radius-bottomright: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;*/
  }
  #close_status_box{
    position: absolute;
    display: inline-block;
    margin-left: 458px;
    margin-top: 2px;
    display: none;
    cursor: pointer;
  }
  #share_type_controls{
    display: none;
    margin: 10px -8px;
    border: 1px solid #cccccc;
    border-top: 0;
    border-bottom: 0;
    text-align: right;
    background-color: #f9f9f9;
  }
  .post_input_holder{
    display: none;
    margin-top: 10px;
  }
  .post_selected{
    font-weight: bold;
  }
  .add_post_controls{
    margin-right: 5px;
    cursor: pointer;
  }
  #uploading_div{
    min-height: 59px;
    display: none;
    text-align: center;
    padding: 10px;
  }
  #show_image_holder{
    min-height: 59px;
    display: none;
    text-align: center;
    background-color: #f3f3f3;
    border: 1px solid #cccccc;
    border-bottom: 0;
    margin: 20px -8px auto -8px;
  }
</style>
@endsection
@section('content')
<div id="notification_holder">
  <div id="message_holder"></div>
</div>

<div id="main_body_holder" class="row">
  <div id="left_body_holder" class="span1">
    <div id="user_prof_holder">
      <a href="/profile/{{ Auth::user()->id }}"><img src="{{ URL::base() }}/public/defaults/default.jpg" width="40px"/></a>
      <a href="/profile/{{ Auth::user()->id }}"><strong>{{ Auth::user()->firstname.' '.Auth::user()->lastname }}</strong></a>
    </div>
    <ul id="basic_controls" class="nav">
      <li><a href="#">Wall feed</a></li>
      <li><a href="#">Messages</a></li>
      <li><a href="#">Events</a></li>
    </ul>
  </div>
  <div id="center_body_holder" class="span6">
    <div id="share_box">
      <img src="{{ URL::base() }}/public/defaults/default.jpg" width="50px" class="img-rounded"/>
      <div class="bubble"></div>
      <div id="share_form_box">
        {{ Form::open('ajax/compose_post', 'POST', array('id'=>'compose_post_form')) }}
          <a id="close_status_box" class="close" data-dismiss="alert">×</a>
          {{ Form::textarea('status_box', '', array('id'=>'status_box', 'placeholder'=>'Hey! What\'s happening?')) }}
          <div id="photo_post_uploader" class="post_input_holder">
            {{ Form::file('post_photo_file', array('id'=>'post_photo_file', 'accept'=>'image/*')) }}
          </div>
          <div id="share_type_controls">
            <a id="post_status" class="post_selected add_post_controls">Update Status</a>
            <a id="post_photo" class="add_post_controls">Add Photo</a>
          </div>
          <div id="uploading_div">Uploading...</div>
          <div id="show_image_holder"></div>
          <div id="share_form_controls">
            <input type="hidden" name="post_type" id="post_type" value="status"/>
            <button type="submit" name="submit_post_button" id="submit_post_button" class="btn btn-success disabled">Share</button>
          </div>
        {{ Form::close() }}
      </div>
    </div>

    <div id="posts_stream_holder">
      <ul id="ul_streamer">
        @forelse($post_stream as $ps)
        @if($ps->post_type == 'status')
        <?php $sp = PostTypeLoaders::status_post($ps->owner_id, $ps->ref_post_type_id); ?>
        <li id="post_stream_{{ $ps->post_id }}" class="post_stream_content">
          <img src="{{ URL::base() }}/public/defaults/default.jpg" width="50px" class="user_stream_pic img-rounded"/>
          <div class="bubble"></div>
          <div class="post_content_holder">
            <div class="post_owner_info">
              <a href="/profile/{{ $ps->owner_id }}"><strong>{{ $sp->firstname.' '.$sp->lastname }}</strong></a> <span class="date_time_posted"><a href="#">{{ date('h:i A', strtotime($ps->datetime_created)) }}</a></span>

              <div id="post_controls_{{ $ps->post_id }}" class="dropdown pull-right">
                <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">
                  <b class="caret"></b>
                </a>
                @if(Auth::user()->id == $ps->owner_id)
                <ul class="dropdown-menu post_owner_controls" role="menu" aria-labelledby="dLabel">
                  <li><a id="edit_post_{{ $ps->post_id }}" class="trigger_edit_post">Edit this post</a></li>
                  <li><a id="delete_post_{{ $ps->post_id }}" class="trigger_delete_post">Delete this post</a></li>
                  <li><a>Disable comments</a></li>
                  <li><a>Lock this post</a></li>
                </ul>
                @endif
              </div>

            </div>
            <div class="post_content">
              {{ nl2br(Sanitize::purify($sp->status_post)) }}
            </div>
          </div>
          <div class="post_sharing_controls">
            <a href="#">Like it</a> &bull; <a href="#">Share</a>
          </div>
          <div class="post_comments_holder">
            <?php
            $comment_count = CommentLoader::count_comments($ps->post_id);
            $comment_stream = CommentLoader::get_comments($ps->post_id);
            ?>
            <ul id="comment_stream_holder_{{ $ps->post_id }}">
              @if($comment_count != 0)
              @foreach($comment_stream as $cs)
              <li id="comment_holder_{{ $cs->comment_id }}" class="comment_holder">
                <a href="/profile/{{ $cs->commenter_id }}"><img src="{{ URL::base() }}/public/defaults/default.jpg" width="32px" class="user_stream_pic img-rounded"/></a>
                <div class="comment_container">
                  <div class="comment_owner_info">
                    <a href="/profile/{{ $cs->commenter_id }}">{{ $cs->firstname.' '.$cs->lastname }}</a> <span class="date_time_commented">{{ date('h:i A', strtotime($cs->datetime_commented)) }}</span> @if(Auth::user()->id == $cs->commenter_id)<span class="edit_comment"><a id="edit_comment_{{ $cs->comment_id }}" class="trigger_edit">Edit</a></span>@endif <a id="delete_comment_{{ $cs->comment_id }}" class="close pull-right delete_comment">×</a>
                  </div>
                  <div class="comment_content">
                    {{ nl2br(Sanitize::purify($cs->comment)) }}
                  </div>
                </div>

                @if(Auth::user()->id == $cs->commenter_id)
                <div class="edit_comment_holder">
                  {{ Form::open('ajax/edit_comment', 'POST', array('id'=>'edit_comment_form_'.$cs->comment_id, 'class'=>'edit_comment_form')) }}
                    {{ Form::textarea('edit_comment_box', $cs->comment, array('id'=>'edit_comment_box_'.$cs->comment_id, 'class'=>'comment_box edit_comment_box')) }}
                    <div class="edit_comment_controls">
                      <input type="hidden" name="comment_id" value="{{ $cs->comment_id }}"/>
                      <input type="hidden" name="commenter_id" value="{{ $cs->commenter_id }}"/>
                      <button id="submit_edit_{{ $cs->comment_id }}" class="btn btn-success submit_edit_comment">Edit</button>
                      <button id="del_comment_{{ $cs->comment_id }}" class="btn del_comment">Delete comment</button>
                      <button id="cancel_edit_comment_{{ $cs->comment_id }}" class="btn cancel_edit_comment">Cancel</button>
                    </div>
                  {{ Form::close() }}
                </div>
                @endif

              </li>
              @endforeach
              @endif
            </ul>

            <div class="comment_form_holder">
              <a href="/profile/{{ Auth::user()->id }}"><img src="{{ URL::base() }}/public/defaults/default.jpg" width="32px" class="user_stream_pic img-rounded"/></a>
              {{ Form::open('ajax/add_comment', 'POST', array('id'=>'comment_form_'.$ps->post_id, 'class'=>'comment_form')) }}
                {{ Form::textarea('comment_box', '',array('id'=>'comment_box_'.$ps->post_id, 'class'=>'comment_box', 'placeholder'=>'Add a comment...')) }}
                <div id="comment_form_controls_{{ $ps->post_id }}" class="comment_form_controls">
                  <input type="hidden" name="commenter_id" value="{{ Auth::user()->id }}"/>
                  <input type="hidden" name="post_id" value="{{ $ps->post_id }}"/>

                  <button type="submit" id="button_submit_{{ $ps->post_id }}" class="btn btn-success disabled post_comment_button" disabled="disabled">Post comment</button>
                  <button id="button_cancel_{{ $ps->post_id }}" class="btn comment_cancel">Cancel</button>
                </div>
              {{ Form::close() }}
            </div>
          </div>
        </li>
        @endif
        @if($ps->post_type == 'photo')
        <?php $pp = PostTypeLoaders::photo_post($ps->owner_id, $ps->ref_post_type_id); ?>
        <li class="post_stream_content">
          <img src="{{ URL::base() }}/public/defaults/default.jpg" width="50px" class="user_stream_pic img-rounded"/>
          <div class="bubble"></div>
          <div class="post_content_holder">
            <div class="post_owner_info">
              <a href="/profile/{{ $ps->owner_id }}"><strong>{{ $pp->firstname.' '.$pp->lastname }}</strong></a> <span class="date_time_posted"><a href="#">{{ date('h:i A', strtotime($ps->datetime_created)) }}</a></span> 

              <div id="post_controls_{{ $ps->post_id }}" class="dropdown pull-right">
                <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">
                  <b class="caret"></b>
                </a>
                @if(Auth::user()->id == $ps->owner_id)
                <ul class="dropdown-menu post_owner_controls" role="menu" aria-labelledby="dLabel">
                  <li><a id="edit_post_{{ $ps->post_id }}" class="trigger_edit_post">Edit this post</a></li>
                  <li><a id="delete_post_{{ $ps->post_id }}" class="trigger_delete_post">Delete this post</a></li>
                  <li><a>Disable comments</a></li>
                  <li><a>Lock this post</a></li>
                </ul>
                @endif
              </div>
            </div>
            <div class="post_content">
              {{ nl2br(Sanitize::purify($pp->photo_caption)) }}
            </div>
            <div class="photo_holder">
              <img src="{{ URL::base() }}/public/photos/{{ sha1($ps->owner_id) }}/wall/{{ $pp->filename }}"/>
            </div>
          </div>
          <div class="post_sharing_controls">
            <a href="#">Like it</a> &bull; <a href="#">Share</a>
          </div>

          <div class="post_comments_holder">
            <?php
            $comment_count = CommentLoader::count_comments($ps->post_id);
            $comment_stream = CommentLoader::get_comments($ps->post_id);
            ?>
            <ul id="comment_stream_holder_{{ $ps->post_id }}">
              @if($comment_count != 0)
              @foreach($comment_stream as $cs)
              <li id="comment_holder_{{ $cs->comment_id }}" class="comment_holder">
                <a href="/profile/{{ $cs->commenter_id }}"><img src="{{ URL::base() }}/public/defaults/default.jpg" width="32px" class="user_stream_pic img-rounded"/></a>
                <div class="comment_container">
                  <div class="comment_owner_info">
                    <a href="/profile/{{ $cs->commenter_id }}">{{ $cs->firstname.' '.$cs->lastname }}</a> <span class="date_time_commented">{{ date('h:i A', strtotime($cs->datetime_commented)) }}</span> @if(Auth::user()->id == $cs->commenter_id)<span class="edit_comment"><a id="edit_comment_{{ $cs->comment_id }}" class="trigger_edit">Edit</a></span>@endif <a id="delete_comment_{{ $cs->comment_id }}" class="close pull-right delete_comment">×</a>
                  </div>
                  <div class="comment_content">
                    {{ nl2br(Sanitize::purify($cs->comment)) }}
                  </div>
                </div>

                @if(Auth::user()->id == $cs->commenter_id)
                <div class="edit_comment_holder">
                  {{ Form::open('ajax/edit_comment', 'POST', array('id'=>'edit_comment_form_'.$cs->comment_id, 'class'=>'edit_comment_form')) }}
                    {{ Form::textarea('edit_comment_box', $cs->comment, array('id'=>'edit_comment_box_'.$cs->comment_id, 'class'=>'comment_box edit_comment_box')) }}
                    <div class="edit_comment_controls">
                      <input type="hidden" name="comment_id" value="{{ $cs->comment_id }}"/>
                      <input type="hidden" name="commenter_id" value="{{ $cs->commenter_id }}"/>
                      <button id="submit_edit_{{ $cs->comment_id }}" class="btn btn-success submit_edit_comment">Edit</button>
                      <button id="del_comment_{{ $cs->comment_id }}" class="btn del_comment">Delete comment</button>
                      <button id="cancel_edit_comment_{{ $cs->comment_id }}" class="btn cancel_edit_comment">Cancel</button>
                    </div>
                  {{ Form::close() }}
                </div>
                @endif

              </li>
              @endforeach
              @endif
            </ul>

            <div class="comment_form_holder">
              <a href="/profile/{{ Auth::user()->id }}"><img src="{{ URL::base() }}/public/defaults/default.jpg" width="32px" class="user_stream_pic img-rounded"/></a>
              {{ Form::open('ajax/add_comment', 'POST', array('id'=>'comment_form_'.$ps->post_id, 'class'=>'comment_form')) }}
                {{ Form::textarea('comment_box', '',array('id'=>'comment_box_'.$ps->post_id, 'class'=>'comment_box', 'placeholder'=>'Add a comment...')) }}
                <div id="comment_form_controls_{{ $ps->post_id }}" class="comment_form_controls">
                  <input type="hidden" name="commenter_id" value="{{ Auth::user()->id }}"/>
                  <input type="hidden" name="post_id" value="{{ $ps->post_id }}"/>

                  <button type="submit" id="button_submit_{{ $ps->post_id }}" class="btn btn-success disabled post_comment_button" disabled="disabled">Post comment</button>
                  <button id="button_cancel_{{ $ps->post_id }}" class="btn comment_cancel">Cancel</button>
                </div>
              {{ Form::close() }}
            </div>
          </div>
        </li>
        @endif
        @empty
          <h3>No Wall Feed yet? How about try look for your friends over here? :)</h3>
        @endforelse
      </ul>
    </div>
  </div>
  <div id="right_body_holder" class="span1">
    <center>{ News stream goes here }</center>
    <span id="system_clock">b</span>
  </div>
</div>
@endsection
@section('js_scripts')
{{ HTML::script('public/js/jquery_plugins/jquery.form.js') }}
{{ HTML::script('public/js/bootstrap/bootstrap-dropdown.min.js') }}
{{ HTML::script('public/js/jquery_plugins/status.update.js') }}
<!-- Post Comment Script -->
<script>
  (function(){
    $('.dropdown-toggle').dropdown();

    $(document).ready(function(){
      setInterval(function(){
        $.ajax({
          type : 'post',
          url : 'ajax/test_push',
          data : { test : 'yes' },
          success : function(data){
            $("#system_clock").text(data);
          }
        });
      }, 30000);
    });

    var notification_holder = $("#notification_holder"), message_holder = $("#message_holder");

    var comment_box = $(".comment_box"), post_comment_button = $(".post_comment_button"), comment_cancel = $(".comment_cancel"),delete_comment = $(".delete_comment"), trigger_edit = $("a.trigger_edit"), submit_edit_comment = $(".submit_edit_comment"), cancel_edit_comment = $(".cancel_edit_comment"), delete_comment = $(".del_comment"),  edit_comment_box = $(".edit_comment_box");

    delete_comment.live('click', function(e){
      e.preventDefault();
      var get_id = $(this).attr('id'),
          split_to_get = get_id.split('_'),
          comment_id = split_to_get[2];
      console.log(comment_id);
    });

    $(document).on('click', 'a.trigger_delete_post', function(e){
      e.preventDefault();
      notification_holder.hide();
      
      var get_id = $(this).attr('id'),
          split_to_get = get_id.split('_'),
          post_id = split_to_get[2],
          post_holder = $("#post_stream_" + post_id).hide();
      $.ajax({
        type : 'post',
        url : 'ajax/delete_post',
        data : { post_id : post_id },
        success : function(data){
          post_holder.animate({ backgroundColor: "#fbc7c7" }, "fast").animate({opacity: "hide" }, "slow");
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
          notification_holder.fadeIn();
          message_holder.show().text('There\'s an error. We\'ve dispatched our team to fix it. Sorry for the inconvenience.');
          hide_notification();
        }
      });
    });

    $(document).delegate("a.trigger_edit","click", function(e){
      e.preventDefault();
      var get_id = $(this).attr('id'),
          split_to_get = get_id.split('_'),
          comment_id = split_to_get[2],
          comment_holder = $("#comment_holder_"+comment_id);
      comment_holder.children('.comment_container').hide();
      comment_holder.children('.edit_comment_holder').show();
      return false;
    });

    $(document).delegate('textarea.edit_comment_box', 'keyup', function(e){
      var get_id = $(this).attr('id'),
          split_to_get = get_id.split('_'),
          comment_id = split_to_get[3],
          submit_edit = $("#submit_edit_"+comment_id)
      
      if($(this).val() == ''){
        submit_edit.addClass('disabled').attr('disabled', 'disabled');
      }else{
        submit_edit.removeClass('disabled').removeAttr('disabled');
        while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
            $(this).height($(this).height()+20);
            share_form_box.slideDown();
        };
      }
    });

    $(document).delegate('button.submit_edit_comment' ,'click', function(e){
      e.preventDefault();
      notification_holder.hide();

      var get_id = $(this).attr('id'),
          split_to_get = get_id.split('_'),
          comment_id = split_to_get[2],
          edit_comment_form = $("#edit_comment_form_"+comment_id),
          del_com_button = $("#del_comment_"+comment_id),
          can_com_button = $("#cancel_edit_comment_"+comment_id),
          sub_com_button = $(this),
          comment_holder = $("#comment_holder_"+comment_id);

      $(this).addClass('disabled').attr('disabled', 'disabled');
      del_com_button.addClass('disabled').attr('disabled', 'disabled');
      can_com_button.addClass('disabled').attr('disabled', 'disabled');

      $.ajax({
        type : 'post',
        url : edit_comment_form.attr('action'),
        data : edit_comment_form.serialize(),
        dataType : 'json',
        success :  function(data){
          if(data.error){
            notification_holder.fadeIn();
            message_holder.show().text(data.msg);
            sub_com_button.removeClass('disabled').removeAttr('disabled', 'disabled');
            del_com_button.removeClass('disabled').removeAttr('disabled', 'disabled');
            can_com_button.removeClass('disabled').removeAttr('disabled', 'disabled');
            hide_notification();
          }else{
            var new_comment = $("#edit_comment_box_"+comment_id).val();
            sub_com_button.removeClass('disabled').removeAttr('disabled', 'disabled');
            del_com_button.removeClass('disabled').removeAttr('disabled', 'disabled');
            can_com_button.removeClass('disabled').removeAttr('disabled', 'disabled');
            comment_holder.children('.comment_container').show();
            comment_holder.children('.comment_container').children('.comment_content').text(new_comment);
            comment_holder.children('.edit_comment_holder').hide();
          }
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
          notification_holder.fadeIn();
          sub_com_button.removeClass('disabled').removeAttr('disabled', 'disabled');
          del_com_button.removeClass('disabled').removeAttr('disabled', 'disabled');
          can_com_button.removeClass('disabled').removeAttr('disabled', 'disabled');
          message_holder.show().text('There\'s an error. We\'ve dispatched our team to fix it. Sorry for the inconvenience.');
          hide_notification();
        }
      });
    });

    $(document).delegate('button.cancel_edit_comment', 'click', function(e){
      e.preventDefault();
      var get_id = $(this).attr('id'),
          split_to_get = get_id.split('_'),
          comment_id = split_to_get[3],
          comment_holder = $("#comment_holder_"+comment_id);
      comment_holder.children('.comment_container').show();
      comment_holder.children('.edit_comment_holder').hide();
    });

    $(document).delegate('textarea.comment_box', 'focus', function(){
      var get_id = $(this).attr('id'),
          split_to_get = get_id.split('_'),
          post_id = split_to_get[2],
          comment_box = $("#comment_box_" + post_id),
          comment_form_controls = $("#comment_form_controls_" + post_id);
      comment_box.css('height', '88px');
      comment_form_controls.show();
      return false;
    }).delegate('textarea.comment_box', 'keyup', function(e){
      var get_id = $(this).attr('id'),
          split_to_get = get_id.split('_'),
          post_id = split_to_get[2],
          button_submit = $("#button_submit_"+post_id);

      if($(this).val() == ''){
        button_submit.addClass('disabled').attr('disabled', 'disabled');
      }else{
        button_submit.removeClass('disabled').removeAttr('disabled');
        while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
            $(this).height($(this).height()+20);
            share_form_box.slideDown();
        };
      }
    });

    // post_comment_button.live('click', function(e){
    $(document).delegate('button.post_comment_button', 'click', function(e){
      e.preventDefault();
      notification_holder.hide();
      $(this).addClass('disabled').attr('disabled', 'disabled');

      var get_id = $(this).attr('id'),
          split_to_get = get_id.split('_'),
          post_id = split_to_get[2],
          comment_form = $("#comment_form_"+post_id),
          comment_box = $("#comment_box_" + post_id),
          comment_form_controls = $("#comment_form_controls_" + post_id);
      $.ajax({
        type : 'post',
        url : comment_form.attr('action'),
        data : comment_form.serialize(),
        dataType : 'json',
        success : function(data){
          if(data.error){
            notification_holder.fadeIn();
            message_holder.show().text(data.msg);
            $(this).removeClass('disabled').removeAttr('disabled');
            hide_notification();
          }else{
            comment_form[0].reset();
            comment_box.removeAttr('style');
            comment_form_controls.hide();
            load_new_comment(data.comment_id, post_id)
          }
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
          notification_holder.fadeIn();
          message_holder.show().text('There\'s an error. We\'ve dispatched our team to fix it. Sorry for the inconvenience.');
          hide_notification();
        }
      });
    });

    $(document).delegate('button.comment_cancel', 'click', function(e){
      e.preventDefault();
      var get_id = $(this).attr('id'),
          split_to_get = get_id.split('_'),
          post_id = split_to_get[2],
          comment_form = $("#comment_form_"+post_id),
          comment_box = $("#comment_box_" + post_id),
          comment_form_controls = $("#comment_form_controls_" + post_id);
      comment_form[0].reset();
      comment_box.removeAttr('style');
      comment_form_controls.hide();
    });

    function load_new_comment(latest_id, post_id){
      var comment_stream_holder = $("#comment_stream_holder_"+post_id);
      $.ajax({
        type : 'POST',
        url : 'ajax/inserted_post',
        data : { latest_id : latest_id, type : 'comment' },
        success : function(data){
          comment_stream_holder.slideDown(800).append(data);
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
          notification_holder.fadeIn();
          message_holder.show().text('There\'s an error. We\'ve dispatched our team to fix it. Sorry for the inconvenience.');
          hide_notification();
        }
      });
    }

    function hide_notification(){
      setTimeout(function(){
        notification_holder.fadeOut();
        message_holder.hide().text('');
      }, 10000);
    }
  })();
</script>
@endsection