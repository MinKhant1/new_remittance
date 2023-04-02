<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  
    <ul class="navbar-nav ml-auto">
        @auth
        <li class="nav-item active"><a href="{{url('/logout')}}" class="nav-link"><span class="fa fa-user"></span> {{auth()->user()->name}}</a></li>
      @else
        <li class="nav-item active"><a href="{{url('/login')}}" class="nav-link"><span class="fa fa-user"></span>Login</a></li>
      @endauth
    </ul>

  </nav>
  <!-- /.navbar -->