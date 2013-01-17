<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>@yield('title')</title>
  <meta name="viewport" content="width=device-width">
  {{ HTML::style('public/css/bootstrap/bootstrap.css') }}
  {{ HTML::style('public/laravel/css/config.style.css') }}
  @yield('internal_css')
</head>
  <body>
    <div class="navbar navbar-fixed-top navbar-inverse">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="/"><strong>Lara +</strong></a>
          <div class="nav-collapse">
            <ul id="header_right_menu" class="nav pull-right">
              @if(Auth::guest())
              <li><button id="trigger_signup" class="btn btn-primary">Sign up</button></li>
              <li><button id="trigger_log" class="btn btn-info">Log in</button></li>
              <li><button id="trigger_cancel" class="btn btn-danger">Cancel</button></li>
              @else
              <li><a href="/">Home</a><li>
              <li class="divider-vertical"></li>
              <li><a href="/profile/{{ Auth::user()->id }}"><img id="pic_header" src="{{ URL::base() }}/public/defaults/default.jpg" width="20px"/>{{ Auth::user()->firstname .' ' . Auth::user()->lastname }}</a><li>
              <li class="divider-vertical"></li>
              <li><a href="/settings">Settings</a><li>
              <li class="divider-vertical"></li>
              <li><a href="/logout">Log out</a><li>
              @endif
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div id="main_container" class="container">
      @yield('content')
      
      <footer>
        <div class="pull-right">
          <ul class="nav">
            <li><a href="#">About</a></li>
          </ul>
        </div>
        <div>
          <p>LaraChat is part of the Mark Project of Rico Maglayon.</p>
          <p>LaraChat is the version Mark 4 of the Mark Project</p>
          <p>All rights reserved &copy; {{ date('Y') }}</p>
        </div>
      </footer>
    </div>
    {{ HTML::script('public/js/jquery/jquery.min.js') }}
    @yield('js_scripts')
  </body>
</html>