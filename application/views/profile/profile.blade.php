@layout('layout.default')
@section('title') {{ $account_details->firstname.' '.$account_details->lastname }} on Lara + @endsection
@section('internal_css')
<style>

</style>
@endsection
@section('content')
<div id="notification_holder">
  <div id="message_holder"></div>
</div>

<div id="profile_holder" class="row">

</div>
@endsection
@section('js_scripts')

@endsection