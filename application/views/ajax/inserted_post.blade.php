@if($post_type == 'post')
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
          {{ e(nl2br($sp->status_post)) }}
        </div>
      </div>
      <div class="post_sharing_controls">
        <a href="#">Like it</a> &bull; <a href="#">Share</a>
      </div>
      <div class="post_comments_holder">
      <?php $comment_count = CommentLoader::count_comments($ps->post_id); ?>
      <?php $comment_stream = CommentLoader::get_comments($ps->post_id); ?>
        <ul id="comment_stream_holder_{{ $ps->post_id }}">
        @if($comment_count != 0)
          @foreach($comment_stream as $cs)
          <li id="comment_holder_{{ $ps->post_id }}" class="comment_holder">
            <a href="/profile/{{ $cs->commenter_id }}"><img src="{{ URL::base() }}/public/defaults/default.jpg" width="32px" class="user_stream_pic img-rounded"/></a>
            <div class="comment_container">
              <div class="comment_owner_info">
                <a href="/profile/{{ $cs->commenter_id }}">{{ $cs->firstname.' '.$cs->lastname }}</a> <span class="date_time_commented">{{ date('h:i A', strtotime($cs->datetime_commented)) }}</span> @if(Auth::user()->id == $cs->commenter_id)<span class="edit_comment"><a id="edit_comment_{{ $cs->comment_id }}" class="trigger_edit">Edit</a></span>@endif <a id="delete_comment_{{ $cs->comment_id }}" class="close pull-right delete_comment">×</a>
              </div>
              <div class="comment_content">
                {{ e(nl2br($cs->comment)) }}
              </div>
            </div>

            <div class="edit_comment_holder">
              {{ Form::open('ajax/edit_comment', 'POST', array('id'=>'edit_comment_form_'.$cs->comment_id, 'class'=>'edit_comment_form')) }}
                {{ Form::textarea('edit_comment_box', $cs->comment, array('id'=>'edit_comment_box_'.$cs->comment_id, 'class'=>'comment_box  edit_comment_box')) }}
                <div class="edit_comment_controls">
                  <input type="hidden" name="comment_id" value="{{ $cs->comment_id }}"/>
                  <input type="hidden" name="commenter_id" value="{{ $cs->commenter_id }}"/>
                  <button id="submit_edit_{{ $cs->comment_id }}" class="btn btn-success submit_edit_comment">Edit</button>
                  <button id="del_comment_{{ $cs->comment_id }}" class="btn del_comment">Delete comment</button>
                  <button id="cancel_edit_comment_{{ $cs->comment_id }}" class="btn cancel_edit_comment">Cancel</button>
                </div>
              {{ Form::close() }}
            </div>
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
  <?php
    $pp = PostTypeLoaders::photo_post($ps->owner_id, $ps->ref_post_type_id);
  ?>
    <li id="post_stream_{{ $ps->post_id }}" class="post_stream_content">
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
          {{ nl2br(e($pp->photo_caption)) }}
        </div>
        <div class="photo_holder">
          <img src="{{ URL::base() }}/public/photos/{{ sha1($ps->owner_id) }}/wall/{{ $pp->filename }}"/>
        </div>
      </div>
      <div class="post_sharing_controls">
        <a href="#">Like it</a> &bull; <a href="#">Share</a>
      </div>
      <div class="post_comments_holder">
      <?php $comment_count = CommentLoader::count_comments($ps->post_id); ?>
      <?php $comment_stream = CommentLoader::get_comments($ps->post_id); ?>
        @if($comment_count != 0)
        @foreach($comment_stream as $cs)
          <li id="comment_holder_{{ $ps->post_id }}" class="comment_holder">
            <a href="/profile/{{ $cs->commenter_id }}"><img src="{{ URL::base() }}/public/defaults/default.jpg" width="32px" class="user_stream_pic img-rounded"/></a>
            <div class="comment_container">
              <div class="comment_owner_info">
                <a href="/profile/{{ $cs->commenter_id }}">{{ $cs->firstname.' '.$cs->lastname }}</a> <span class="date_time_commented">{{ date('h:i A', strtotime($cs->datetime_commented)) }}</span> @if(Auth::user()->id == $cs->commenter_id)<span class="edit_comment"><a id="edit_comment_{{ $cs->comment_id }}" class="trigger_edit">Edit</a></span>@endif <a id="delete_comment_{{ $cs->comment_id }}" class="close pull-right delete_comment">×</a>
              </div>
              <div class="comment_content">
                {{ nl2br(e($cs->comment)) }}
              </div>
            </div>

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
          </li>
          @endforeach
        @endif

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

@endforelse
@endif
@if($post_type == 'comment')
  <li id="comment_holder_{{ $comment_details->comment_id }}" class="comment_holder">
    <a href="/profile/{{ $comment_details->commenter_id }}"><img src="{{ URL::base() }}/public/defaults/default.jpg" width="32px" class="user_stream_pic img-rounded"/></a>
    <div class="comment_container">
      <div class="comment_owner_info">
        <a href="/profile/{{ $comment_details->commenter_id }}">{{ $comment_details->firstname.' '.$comment_details->lastname }}</a> <span class="date_time_commented">{{ date('h:i A', strtotime($comment_details->datetime_commented)) }}</span> @if(Auth::user()->id == $comment_details->commenter_id)<span class="edit_comment"><a id="edit_comment_{{ $comment_details->comment_id }}" class="trigger_edit">Edit</a></span>@endif <a id="delete_comment_{{ $comment_details->comment_id }}" class="close pull-right delete_comment">×</a>
      </div>
      <div class="comment_content">
        {{ nl2br(e($comment_details->comment)) }}
      </div>
    </div>

    <div class="edit_comment_holder">
      {{ Form::open('ajax/edit_comment', 'POST', array('id'=>'edit_comment_form_'.$comment_details->comment_id, 'class'=>'edit_comment_form')) }}
        {{ Form::textarea('edit_comment_box', $comment_details->comment, array('id'=>'edit_comment_box_'.$comment_details->comment_id, 'class'=>'comment_box edit_comment_box')) }}
        <div class="edit_comment_controls">
          <input type="hidden" name="comment_id" value="{{ $comment_details->comment_id }}"/>
          <input type="hidden" name="commenter_id" value="{{ $comment_details->commenter_id }}"/>
          <button id="submit_edit_{{ $comment_details->comment_id }}" class="btn btn-success submit_edit_comment">Edit</button>
          <button id="del_comment_{{ $comment_details->comment_id }}" class="btn del_comment">Delete comment</button>
          <button id="cancel_edit_comment_{{ $comment_details->comment_id }}" class="btn cancel_edit_comment">Cancel</button>
        </div>
      {{ Form::close() }}
    </div>
  </li>
@endif