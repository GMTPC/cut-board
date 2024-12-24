
<div class="wrapper" id="page-content">


    <!-- Main Header -->
    @include('frontend.mainheader')

    <!-- Sidebar Menu -->
    @include('frontend.mainsidebar')
    <!-- /.sidebar-menu -->


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->



        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
            | Your Page Content Here |
            -------------------------->
            <section class="content-header">
                <main class="py-4">
                    @yield('content')
                </main>
            </section>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    @include('frontend.mainfooter')

    <!-- Control Sidebar -->
    @include('frontend.maincontrolside')
    <!-- /.control-sidebar -->

    <!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
@if (Agent::isMobile() )
    @else
        <div id="load">

        </div>
@endif
