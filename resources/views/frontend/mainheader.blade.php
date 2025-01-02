<header class="main-header">

    <!-- Logo -->
    <a href="{{ route('mainmenu') }}" class="logo" style="background-color:#ae4522;">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>G</b>MT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><!--<img type="image/png" src="{{ asset('img/Logo_GM_small.png') }}" alt="GMT" style="width:30%;">--><b>Gypman Tech</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation" style="background-color:#ed5f30;">


        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>

                    @else

                        <li>
                            <a id="conmodecheck" href="#">Connection Mode : <b id="conmode"></b> </a>
                        </li>
                        <li>
                            <a class="hidden-xs">User : {{ Auth::user()->name }} </a>
                        </li>

                        <li>
                            <a href="{{ route('logout') }}" class="" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Sign out</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i style="font-size:20px;" class="fa fa-address-card"></i></a>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>
    </header>
