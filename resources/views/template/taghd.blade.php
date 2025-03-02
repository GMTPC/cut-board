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

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

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
    #load{
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

<title>{{ config('websetting.webtitle') }}</title>

<!-- Style -->
<link rel="stylesheet" href="{{ asset('css/index.css') }}">

<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

<link rel="stylesheet" href="{{ asset('AdminLTE-master/bower_components/bootstrap/dist/css/bootstrap.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('AdminLTE-master/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('AdminLTE-master/bower_components/Ionicons/css/ionicons.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('AdminLTE-master/dist/css/AdminLTE.min.css') }}">
<!-- Data Table Style-->
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/dataTables.jqueryui.min.css') }}">
<link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">

<!-- Printing Setting -->
<link media="screen" href="{{ asset('css/printing.css') }}" />

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

        <!-- Google Font -->
        <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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

    <body class="hold-transition skin-blue sidebar-mini">

        <div class="container-fluid bg-white">
            <div class="panel panel-default">
                <div class="panel-body">
                        <div class="container-fluid">
                            <div class="text-center">
                                <a id="printtagwipline1a" class="btn btn-success fa fa-print tagsumprint" data-toggle="tooltip" title="พิมพ์" style="font-size:15px;">  พิมพ์</a>
                            </div>
                            <div class="table-responsive">
                                <div class="container">
                                    <div class="print-output">
                                     
                                            <input type="hidden" name="" id="output1aid" value="">
                                            <table style="width:100%;" class="table-output">
                                                <tr>
                                                    <td style="background-color: {{ $colorline }};" colspan="4" class="underlineheader">
                                                        <div class="col-6-md">
                                                            <u style="color:black;"><h2 style="text-align: left;" class="text-left"><b ></b></h2></u>
                                                        </div>
                                                        <div class="col-6-md">
                                                            <u style="color:black;"><h2 style="text-align: right;" class="text-right"><b >TAG WIP แผ่นรอคัด</b></h2></u>
                                                        </div> </br>
                                                    </td>
                                                </tr>
                                                @foreach ($wipHoldings as $taghd)
<tr>
    @if ($wsHoldingAmount == 10)
        <td colspan="4">
            <center>
                {!! DNS1D::getBarcodeHTML(substr($taghd->wh_barcode, 0, 21)."0".substr($taghd->wh_barcode, 21, 2), 'C128', 1, 35) !!}
            </center> 
            <small>
    {!! substr($taghd->wh_barcode, 0, 11) . ":" . 
        (isset($sizearr[$peTypeCode]) ? $sizearr[$peTypeCode] : 'N/A') . 
        ":" . $taghd->wh_lot . $wsHoldingAmount !!}
</small>

    @else
        <td colspan="4">
            <center>
                {!! DNS1D::getBarcodeHTML($taghd->wh_barcode, 'C128', 1, 35) !!}
            </center> 
            <small>
    {!! substr($taghd->wh_barcode, 0, 11) . ":" . 
        (isset($sizearr[substr($peTypeCode, 2, 2)]) ? $sizearr[substr($peTypeCode, 2, 2)] : 'N/A') . 
        ":" . $taghd->wh_lot . $wsHoldingAmount !!}
</small>



    @endif
    <br/>
    <div class="fix-row-center">
        <div class="fix-grid-left">
            <small>{!! DNS1D::getBarcodeHTML(substr($taghd->wh_barcode, 0, 11), 'C128', 1, 35) !!}</small>
            <small>{!! substr($taghd->wh_barcode, 0, 11) !!}</small>
        </div>
        <div class="fix-grid-right">
            <small>{!! DNS1D::getBarcodeHTML($taghd->wh_lot, 'C128', 1, 35) !!}</small>
            <small>{{ $taghd->wh_lot }}</small>
        </div>
    </div>
</td>
</tr>
@endforeach





                      
                                                 
                                                   
                                                    </br>
                                                    <div class="fix-row-center">
                                                        <div class="fix-grid-left">
                                                            <small></small>
                                                            <small></small>
                                                        </div>
                                                        <div class="fix-grid-right">
                                                            <small></small>
                                                            <small></small>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                            <th style="background-color: {{ $colorline }};" class="wip-fix-fontsize-output">
    <b style="font-size:19px;">รหัสสินค้า</b>
</th>
<td style="background-color: {{ $colorline }};" colspan="3">
    <b style="font-size:19px;">
        {{ substr($wipHoldings->first()->wh_barcode ?? 'ไม่มีข้อมูล', 0, 4) }} - {{ $peTypeCode ?? 'ไม่มีข้อมูล' }}
    </b>
</td>


                                            </tr>
                                            <tr>
                                            <th style="background-color: {{ $colorline }};" class="wip-fix-fontsize-output">
    <b style="font-size:19px;">ชื่อสินค้า</b>
</th>
<td style="background-color: {{ $colorline }};" colspan="3">
    <b style="font-size:19px;">
        แผ่นรอคัด {{ $peTypeName ?? 'ไม่มีข้อมูล' }}
    </b>
</td>

                                            </tr>
                                          
<tr>
        <th style="background-color: {{ $colorline }}; width: 25%;" class="wip-fix-fontsize-output">
            <b style="font-size:19px;">วันที่คัด</b>
        </th>
        <td style="background-color: {{ $colorline }};" colspan="3" width="75%">
            <b style="font-size:19px;">
                {{ \Carbon\Carbon::now()->locale('th')->translatedFormat('d F Y') }}
            </b>
        </td>
    </tr>
    <tr>
        <th class="wip-fix-fontsize-output" style="font-size:17px; width: 25%;">หมวดสินค้า</th>
        <td style="font-size:17px; width:30%;">WIP แผ่นรอคัด</td>
        <td style="font-size:17px; width:20%;">ไลน์กะผลิต</td>
        <td style="font-size:17px; width:%;">
            {{ $wwGroup ?? 'ไม่มีข้อมูล' }}
        </td>
    </tr>
                                            <tr>
                                                <th style="font-size:17px;" class="wip-fix-fontsize-output">Lot.</th>
                                                <td style="font-size:17px;">
    {{ $whLot ?? 'ไม่มีข้อมูล' }}
</td>
                                                <td style="font-size:17px;">ไลน์กะคัด</td>
                                                <td style="font-size:17px;"> {{ $wwGroup ?? 'ไม่มีข้อมูล' }}</td>
                                            </tr>
                                            <tr>
                                                <th style="font-size:17px;" class="wip-fix-fontsize-output">จำนวน</th>
                                                <td style="font-size:17px;">
    {{ $totalWipAmount ?? 'ไม่มีข้อมูล' }}
</td>

                                                    </td>
                                                    </td>
                                                  
                                                    
                                                </td>
                                                <td style="font-size:17px;">ผู้ตรวจสอบ</td>
                                                <td style="font-size:17px;">{{ $brdChecker }}</td>
                                            </tr>
                                            <tr>
                                                <th style="font-size:17px;" class="wip-fix-fontsize-output">เวลา</th>
                                                <td style="font-size:17px;"></td>
                                                <td style="font-size:17px;">ผู้คัด</td>
                                                <td style="font-size:17px;">{{ $emp1 }} - {{ $emp2 }}</td>
                                            </tr>
                                            <tr>
                                                <th style="font-size:17px;" class="wip-fix-fontsize-output">น้ำหนัก</th>
                                                <td style="font-size:17px;"></td>
                                                <td style="font-size:17px;">วันที่ผลิต</td>
                                                <td style="font-size:17px;"></td>
                                            </tr>
                                        </table>
                                    <hr style="border-top: 1px dashed black;">
                                    <input type="hidden" name="" id="output1aid" value="">
                                    <table style="width:100%;" class="table-output">
                                        <tr>
                                            <td colspan="4" class=" underlineheader">
                                                <div class="col-6-md">
                                                    <u style="color:black;"><h2 style="text-align: left;" class="text-left"><b ></b></h2></u>
                                                </div>
                                                <div class="col-6-md">
                                                    <u style="color:black;"><h2 style="text-align: right;" class="text-right"><b >ผ่ายคัดบอร์ด</b></h2></u>
                                                </div> </br>
                                            </td>
                                        </tr>
                                        @foreach ($wipHoldings as $taghd)
<tr>
    @if ($wsHoldingAmount == 10)
        <td colspan="4">
            <center>
                {!! DNS1D::getBarcodeHTML(substr($taghd->wh_barcode, 0, 21)."0".substr($taghd->wh_barcode, 21, 2), 'C128', 1, 35) !!}
            </center> 
            <small>
    {!! substr($taghd->wh_barcode, 0, 11) . ":" . 
        (isset($sizearr[$peTypeCode]) ? $sizearr[$peTypeCode] : 'N/A') . 
        ":" . $taghd->wh_lot . $wsHoldingAmount !!}
</small>

    @else
        <td colspan="4">
            <center>
                {!! DNS1D::getBarcodeHTML($taghd->wh_barcode, 'C128', 1, 35) !!}
            </center> 
            <small>
    {!! substr($taghd->wh_barcode, 0, 11) . ":" . 
        (isset($sizearr[substr($peTypeCode, 2, 2)]) ? $sizearr[substr($peTypeCode, 2, 2)] : 'N/A') . 
        ":" . $taghd->wh_lot . $wsHoldingAmount !!}
</small>



    @endif
    <br/>
    <div class="fix-row-center">
        <div class="fix-grid-left">
            <small>{!! DNS1D::getBarcodeHTML(substr($taghd->wh_barcode, 0, 11), 'C128', 1, 35) !!}</small>
            <small>{!! substr($taghd->wh_barcode, 0, 11) !!}</small>
        </div>
        <div class="fix-grid-right">
            <small>{!! DNS1D::getBarcodeHTML($taghd->wh_lot, 'C128', 1, 35) !!}</small>
            <small>{{ $taghd->wh_lot }}</small>
        </div>
    </div>
</td>
</tr>
@endforeach

</br>
                                    <tr>
                                        <th class="wip-fix-fontsize-output"><b style="font-size:19px;">รหัสสินค้า</b></th>
                                        <td colspan="3" ><b style="font-size:19px;"> {{ substr($wipHoldings->first()->wh_barcode ?? 'ไม่มีข้อมูล', 0, 4) }} - {{ $peTypeCode ?? 'ไม่มีข้อมูล' }}</b></td>
                                    </tr>
                                    <tr>
                                        <th class="wip-fix-fontsize-output"><b style="font-size:19px;">ชื่อสินค้า</b></th>
                                        <td colspan="3" ><b style="font-size:19px;">แผ่นรอคัด  {{ $peTypeName ?? 'ไม่มีข้อมูล' }}</b></td>
                                    </tr>
                                    <tr>
                                        <th class="wip-fix-fontsize-output"><b style="font-size:19px;">วันที่คัด</b></th>
                                        <td colspan="3">
    <b style="font-size:19px;">
    {{ \Carbon\Carbon::now()->locale('th')->translatedFormat('d F Y') }}
    </b>
</td>
                                    </tr>
                                    <tr>
                                        <th class="wip-fix-fontsize-output" style="font-size:17px;">หมวดสินค้า</th>
                                        <td style="font-size:17px;width:30%;">WIP แผ่นรอคัด และ C</td>
                                        <td style="font-size:17px;width:20%;">ไลน์กะผลิต</td>
                                        <td style="font-size:17px;width:30%;">            {{ $wwGroup ?? 'ไม่มีข้อมูล' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="font-size:17px;" class="wip-fix-fontsize-output">Lot.</th>
                                        <td style="font-size:17px;">    {{ $whLot ?? 'ไม่มีข้อมูล' }}
                                        </td>
                                        <td style="font-size:17px;">ไลน์กะคัด</td>
                                        <td style="font-size:17px;">            {{ $wwGroup ?? 'ไม่มีข้อมูล' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="font-size:17px;" class="wip-fix-fontsize-output">จำนวน</th>
                                        <td style="font-size:17px;">
    HD ({{ $wsHoldingAmount ?? 0 }})  
    NG ({{ $wsNgAmount ?? 0 }})
</td>                                        <td style="font-size:17px;">ผู้ตรวจสอบ</td>
                                        <td style="font-size:17px;">{{ $brdChecker }}</td>
                                        </tr>
                                    <tr>
                                        <th style="font-size:17px;" class="wip-fix-fontsize-output">เวลา</th>
                                        <td style="font-size:17px;"></td>
                                        <td style="font-size:17px;">ผู้คัด</td>
                                        <td style="font-size:17px;">{{ $emp1 }} - {{ $emp2 }}</td>
                                        </tr>
                                    <tr>
                                        <th style="font-size:17px;" class="wip-fix-fontsize-output">น้ำหนัก</th>
                                        <td style="font-size:17px;"></td>
                                        <td style="font-size:17px;">วันที่ผลิต</td>
                                        <td style="font-size:17px;"></td>
                                    </tr>
                                </table>
                                        </br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('AdminLTE-master/bower_components/jquery/dist/jquery.min.js') }}"></script>

    <!-- Jquery Print Script -->
    <script src="{{ asset('js/jquery.printPage.js')}}"></script>
    <script src="{{ asset('js/jquery.PrintArea.js')}}"></script>
    <script src="{{ asset('js/printThis.js')}}"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('AdminLTE-master/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

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

    <!-- Data Table -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.jqueryui.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <!--<script src="https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/padStart"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
        Both of these plugins are recommended to enhance the
        user experience. -->

        </html>
