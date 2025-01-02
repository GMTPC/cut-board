<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html class="all-font-size" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- header('Content-Type: text/html; charset=ISO-8859-15');  --}}

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style type="text/css">

    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: normal;
        src: url("{{ asset('fonts/THSarabunNew.ttf') }}") format('truetype');
    }
    @font-face {
        font-family: 'code128';
        font-style: normal;
        font-weight: normal;
        src: url('{{ asset('fonts/code128.ttf') }}') format('truetype');
    }

    body {
        font-family: "THSarabunNew";
    }
    /* #load{
        display: block;
        position: absolute;
        width: 100%;
        height: 100%;
        position: fixed;
        top:0px;
        left:0px;
        z-index: 9999;
        background:#fff url('{{ asset("img/3dotspinnerGM.gif") }}')  no-repeat 50% 50%  !important;
        background-size:120px 65px !important;
    } */

    .custom-background-active {
    background: #fff url('img/3dotspinnerGM.gif') no-repeat 50% 50%;
    }
    
    #page-content{
        display: none;
    }
    .all-font-size{
        font-size: 18px;
    }
    .barcode-output{
        font-family: 'code128';
        font-size: 36px;
    }
    .fc-time{
   display : none;
   
}

.my-excel-button {
    background-color: #3673F3;
    font-size: 14px;
    color: white;
}

</style>
<script type="text/javascript">
    function onReady(callback) {
        var intervalID = window.setInterval(checkReady, 1000);

        function checkReady() {
            if (document.getElementsByTagName('body')[0] !== undefined) {
                window.clearInterval(intervalID);
                callback.call(this);
            }
        }
    }

    function show(id, value) {
        document.getElementById(id).style.display = value ? 'block' : 'none';
    }

    onReady(function () {
        show('page-content', true);
        show('load', false);
    });
</script>


<script>
   $(document).ready(function() {
    $('.custom-background').click(function() {
        updateData();
    });
});
</script>

<title>{{ config('websetting.webtitle') }} | Ver. 2.0.1</title>

<!-- Style -->

<!-- Bootstrap -->
<link rel="stylesheet" href="{{ asset('AdminLTE-master/bower_components/bootstrap/dist/css/bootstrap.css') }}">

<!-- Font Awesome -->
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
<link rel="stylesheet" href="{{ asset('AdminLTE-master/bower_components/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous">
{{-- csv --}}
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" > --}}
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" > --}}

<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('AdminLTE-master/bower_components/Ionicons/css/ionicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/all.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('AdminLTE-master/dist/css/AdminLTE.min.css') }}">
<!-- Data Table Style-->
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.jqueryui.min.css') }}">

<!-- Other style -->
<link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/netliva_switch.css') }}">
<link rel="stylesheet" href="{{ asset('css/notifIt.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.dataTables.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('css/switchery.css') }}">

<!-- Printing Setting -->
<link media="screen" href="{{ asset('css/printing.css') }}" />

<!-- Color picker -->
<link rel="stylesheet" href="{{ asset('css/colorPick.css') }}">

<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
    page. However, you can choose any other skin. Make sure you
    apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-master/dist/css/skins/skin-blue.min.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        {{-- <!-- Google Font -->
        <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> --}}
    </head>
    <!--
        BODY TAG OPTIONS:
        =================
        Apply one or more of the following classes to get the
        desired effect
        |---------------------------------------------------------|
        | SKINS         | skin-blue                               |
        |               | skin-black                              |
        |               | skin-purple                             |
        |               | skin-yellow                             |
        |               | skin-red                                |
        |               | skin-green                              |
        |---------------------------------------------------------|
        |LAYOUT OPTIONS | fixed                                   |
        |               | layout-boxed                            |
        |               | layout-top-nav                          |
        |               | sidebar-collapse                        |
        |               | sidebar-mini                            |
        |---------------------------------------------------------|
    -->

    <body class="hold-transition skin-blue sidebar-mini" onload="autoSubmit();"> 

        <!-- main contain body -->
        @include('frontend.mainbody')

    </body>
    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery -->

    <script src="{{ asset('AdminLTE-master/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-master/plugins/jQueryUI/jquery-ui.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>

   

    <!-- Jquery Print Script -->
    <script src="{{ asset('js/jquery.printPage.js')}}"></script>
    <script src="{{ asset('js/jquery.PrintArea.js')}}"></script>
    <script src="{{ asset('js/printThis.js')}}"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('AdminLTE-master/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-switch.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE-master/dist/js/adminlte.min.js') }}"></script>

    <!-- JS Barcode Code 128 -->
    <script src="{{ asset('js/JsBarcode.code128.min.js')}}"></script>

    <!-- Custom Script -->
    <script src="{{ asset('js/general.js') }}"></script>
    <script src="{{ asset('js/decodeitem.js') }}"></script>
    <script src="{{ asset('js/wipbarcode.js')}}"></script>
    <script src="{{ asset('js/condition.js')}}"></script>
    <script src="{{ asset('js/customprints.js')}}"></script>
    <script src="{{ asset('js/crud.js')}}"></script>

    <!-- Other script -->
    <script src="{{ asset('js/excelexportjs.js')}}"></script>
    <script src="{{ asset('js/table2csv.js')}}"></script>
    <script src="{{ asset('js/jquery.tabletocsv.js')}}"></script>
    <script src="{{ asset('js/tableHTMLExport.js')}}"></script>
    <script src="{{ asset('js/jquery.TableCSVExport.js')}}"></script>
    <script src="{{ asset('js/numeral.min.js')}}"></script>
    <script src="{{ asset('js/jquery.mask.js')}}"></script>
    <script src="{{ asset('js/sweetalert2@9.js')}}"></script>
    <script src="{{ asset('js/promise-polyfill.js')}}"></script>
    <script src="{{ asset('js/sPreloader.js')}}"></script>
    <script src="{{ asset('js/netliva_switch.js')}}"></script>
    <script src="{{ asset('js/notifIt.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js')}}"></script>
    <script src="{{ asset('js/colorPick.min.js')}}"></script>
    <script src="{{ asset('js/moment.min.js')}}"></script>
    <script src="{{ asset('js/fullcalendar.min.js')}}"></script>
    <script src="{{ asset('js/dropzone.js')}}"></script>

    <!-- Data Table -->
    <script src="{{ asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('js/dataTables.jqueryui.min.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <script type="text/javascript">
            @if (env('APP_ENV') !== 'production') 
                var path = "/gypman-tech"; // /gypman-tech
            @else
                var path = "/gypman-tech";
            @endif
        </script>
        @yield('javascript')
        </html>

         {{-- เพิ่มมาใหม่ --}}
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js" ></script> --}}
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" ></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" ></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" ></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" ></script> --}}
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js" ></script>
    {{-- <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js" ></script> --}}


