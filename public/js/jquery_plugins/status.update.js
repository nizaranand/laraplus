(function(){
  $(window).bind('resize', function() {
      
  });
  var status_box = $("#status_box"), submit_post_button = $("#submit_post_button"), compose_post_form = $("#compose_post_form"), notification_holder = $("#notification_holder"), message_holder = $("#message_holder");

  var share_form_box = $("#share_form_box"), share_form_controls = $("#share_form_controls"), share_type_controls = $("#share_type_controls"), close_status_box = $("#close_status_box"), post_type = $("#post_type"), post_status = $("#post_status"), post_photo = $("#post_photo"), post_input_holder = $(".post_input_holder"), photo_post_uploader = $("#photo_post_uploader"), removable_input = $(".removable_input");
  var uploading_div = $("#uploading_div"), show_image_holder = $("#show_image_holder"), temp_div = $("#temp_div");

  status_box.on('keyup', function(e){
    if($(this).val() == ''){
      submit_post_button.addClass('disabled');
    }else{
      submit_post_button.removeClass('disabled');
      while($(this).outerHeight() < this.scrollHeight + parseFloat($(this).css("borderTopWidth")) + parseFloat($(this).css("borderBottomWidth"))) {
          $(this).height($(this).height()+40);
            share_form_box.slideDown();
      };
    }
  }).on("focus", function(){
    var sfb_class = share_form_box.attr('class');
    share_form_box.removeAttr('style');
    status_box.animate({height : '80px'}, 'fast');
    if(sfb_class != 'show_photo_uploader' && sfb_class != 'show_status_box'){
      share_form_box.slideDown().addClass('show_status_box');
      share_form_controls.show();
      share_type_controls.show();
      close_status_box.show();
    }
  });

  $("#post_photo_file").live('change', function(){
    notification_holder.hide();
    share_type_controls.hide();
    photo_post_uploader.hide();
    uploading_div.show();
    var options = { 
      url : 'ajax/upload_photo_post',
      dataType : 'json',
      success : function(data) {
        if(data.error){
          notification_holder.fadeIn();
          message_holder.show().text(data.msg);
          submit_post_button.removeClass('disabled');
          hide_notification();
        }else{
          uploading_div.hide();
          share_form_box.removeAttr('style');
          show_image_holder.html('<img src="'+data.file_location+'" width="300px"/>').show();
          share_form_controls.prepend('<div id="temp_div"><input type="hidden" name="filename" value="'+data.filename+'" class="removable_input"/><input type="hidden" name="camera" value="'+data.photo_camera_maker+'" class="removable_input"/><input type="hidden" name="exposure" value="'+data.photo_camera_expo+'" class="removable_input"/><input type="hidden" name="date_captured" value="'+data.photo_taken+'" class="removable_input"/><input type="hidden" name="aperture" value="'+data.photo_aperture+'" class="removable_input"/><input type="hidden" name="iso_speed" value="'+data.photo_iso+'" class="removable_input"/><input type="hidden" name="filesize" value="'+data.filesize+'" class="removable_input"/></div>');
          submit_post_button.removeClass('disabled');
        }
      },
      error : function(XMLHttpRequest, textStatus, errorThrown) {
        notification_holder.fadeIn();
        message_holder.show().text('There\'s an error. We\'ve dispatched our team to fix it. Sorry for the inconvenience.');
        hide_notification();
      }
    };
    compose_post_form.ajaxForm(options).submit()
  });

  submit_post_button.on('click', function(){
    notification_holder.hide();
    var options = { 
        url : compose_post_form.attr('action'),
        dataType : 'json',
        success : function(data) { 
          if(data.error){
            notification_holder.fadeIn();
            message_holder.show().text(data.msg);

            submit_post_button.removeClass('disabled').removeAttr('disabled').text('Share');
            status_box.animate({height : '25px'}, 'fast');
            share_form_box.animate({height : '35px'}, 'fast').removeClass();
            post_status.addClass('post_selected');
            post_photo.removeClass('post_selected');
            share_form_controls.hide();
            share_type_controls.hide();
            close_status_box.hide();
            post_input_holder.hide();
            compose_post_form[0].reset();
            load_new_post(data.msg);
            removable_input.remove();
            show_image_holder.empty();

            hide_notification();
          }else{
            submit_post_button.removeClass('disabled').removeAttr('disabled').text('Share');
            status_box.animate({height : '25px'}, 'fast');
            share_form_box.animate({height : '35px'}, 'fast').removeClass();
            post_status.addClass('post_selected');
            post_photo.removeClass('post_selected');
            post_type.val('status');
            share_form_controls.hide();
            share_type_controls.hide();
            close_status_box.hide();
            post_input_holder.hide();
            compose_post_form[0].reset();
            load_new_post(data.msg);
            temp_div.empty();
            show_image_holder.hide().remove();

            hide_notification();
          }
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
          submit_post_button.removeClass('disabled').removeAttr('disabled').text('Share');
          notification_holder.fadeIn();
          message_holder.show().text('There\'s an error. We\'ve dispatched our team to fix it. Sorry for the inconvenience.');
          hide_notification();
        } 
    };
    submit_post_button.addClass('disabled').text('Sharing...').attr('disabled');
    compose_post_form.ajaxForm(options);
  });

  function load_new_post(latest_id){
    var post_stream_holder = $("#posts_stream_holder ul#ul_streamer");
    $.ajax({
      type : 'POST',
      url : 'ajax/inserted_post',
      data : { latest_id : latest_id, type : 'post' },
      success : function(data){
        post_stream_holder.prepend(data);
        return false;
      }
    });
  }

  function hide_notification(){
    setTimeout(function(){
      notification_holder.fadeOut();
      message_holder.hide().text('');
    }, 10000);
  }

  close_status_box.on("click", function(){
    submit_post_button.removeClass('disabled').removeAttr('disabled').text('Share');
    status_box.animate({height : '25px'}, 'fast');
    share_form_box.animate({height : '35px'}, 'fast').removeClass();
    post_status.addClass('post_selected');
    post_photo.removeClass('post_selected');
    post_type.val('status');
    share_form_controls.hide();
    share_type_controls.hide();
    close_status_box.hide();
    post_input_holder.hide();
    compose_post_form[0].reset();
    temp_div.empty();
    show_image_holder.hide().remove();
    hide_notification();
  });

  post_photo.on("click", function(){
    post_status.removeClass('post_selected');
    post_photo.addClass('post_selected');
    post_type.val('photo');
    photo_post_uploader.show();
    share_form_box.animate({height : '198px'}, 'fast').removeClass().addClass('show_photo_uploader');
  });

  post_status.on("click", function(){
    post_photo.removeClass('post_selected');
    post_status.addClass('post_selected');
    post_type.val('status');
    photo_post_uploader.hide();
    share_form_box.animate({height : '168px'}, 'fast').removeClass().addClass('show_status_box');
  });
})();