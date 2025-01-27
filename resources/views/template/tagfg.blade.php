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

   <!--<meta HTTP-EQUIV="Refresh"  CONTENT="600">-->

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

<!-- Color picker -->
<link rel="stylesheet" href="{{ asset('css/bcp.min.css') }}">

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
                        <a id="printtagwipline1a" class="btn btn-success fa fa-print tagsumprint" data-toggle="tooltip" title="พิมพ์" style="font-size:15px;">  พิมพ์ Tag</a>
                        <a class="btn btn-warning fa fa-paint-brush" data-target="#changetypecolor" data-toggle="modal" data-toggle="tooltip" title="เปลี่ยนสี" style="font-size:15px;">  เปลี่ยนสี</a>
                    </div>
                    <div class="table-responsive">
                        <div class="container">
                            <div class="print-output">
                                @foreach ($tag as $tag)
                                    <input type="hidden" name="" id="output1aid" value="">
                                    <table style="width:100%;" class="table-output">

                                        <tr>
                                            <th style="background-color: {{ $colordate }};" colspan="1"><u style="color:black;"><b style="font-size:19px;"class="text-left">{{ substr($tag->brd_lot,6,4) }}</b></u></th>
                                            <td colspan="2" style="background-color: {{ $colordate }};" colspan="1"></td>
                                            <th style="background-color: {{ $colordate }};" colspan="2"><u style="color:black;"><b style="font-size:19px;"class="text-right">TAG FG</b></u></th>
                                        </tr>

                                        <tr> 
 
                                            @if ($tag->brd_amount < 10)
                                                <td colspan="0"><center><b style="font-size:14px;">สำหรับงานคลัง</b><br> {{ QrCode::size(100)->generate(route('qrcodeinterface',"B".substr($tag->ww_line,1,1).$tag->bl_code."-".$tag->pe_type_code.$tag->brd_lot.'00'.$tag->brd_amount)) }}</center></td>
                                            @elseif ($tag->brd_amount < 100)
                                                <td colspan="0"><center><b style="font-size:14px;">สำหรับงานคลัง</b><br> {{ QrCode::size(100)->generate(route('qrcodeinterface',"B".substr($tag->ww_line,1,1).$tag->bl_code."-".$tag->pe_type_code.$tag->brd_lot.'0'.$tag->brd_amount)) }}</center></td>
                                            @else
                                                <td colspan="0"><center><b style="font-size:14px;">สำหรับงานคลัง</b><br> {{ QrCode::size(100)->generate(route('qrcodeinterface',"B".substr($tag->ww_line,1,1).$tag->bl_code."-".$tag->pe_type_code.$tag->brd_lot.$tag->brd_amount)) }}</center></td>
                                            @endif 

                                            {{-- // ส่วนบาร์โค้ต ยาว --}}
                                            @if ($tag->brd_amount < 10)
                                                <td colspan="4" ><center>{!! DNS1D::getBarcodeHTML("B".substr($tag->ww_line,1,1).$tag->bl_code."-".$tag->pe_type_code.$tag->brd_lot.'00'.$tag->brd_amount,'C128',1,60)  !!}
                                                    <small>{!! "BX".$tag->bl_code."-".$tag->pe_type_code.":".$sizearr[substr($tag->pe_type_code,2,2)]." : ".$tag->ww_line."++++++++".'00'.$tag->brd_amount !!}</small> </center>                                           
                                            @elseif ($tag->brd_amount < 100)
                                                <td colspan="4"><center>{!! DNS1D::getBarcodeHTML("B".substr($tag->ww_line,1,1).$tag->bl_code."-".$tag->pe_type_code.$tag->brd_lot.'0'.$tag->brd_amount,'C128',1,60)  !!}
                                                    <small>{!! "BX".$tag->bl_code."-".$tag->pe_type_code.":".$sizearr[substr($tag->pe_type_code,2,2)]." : ".$tag->ww_line."++++++++".'0'.$tag->brd_amount !!}</small>   </center>                                         
                                            @else
                                                <td colspan="4"><center>{!! DNS1D::getBarcodeHTML("B".substr($tag->ww_line,1,1).$tag->bl_code."-".$tag->pe_type_code.$tag->brd_lot.$tag->brd_amount,'C128',1,60)  !!}
                                                    <small>{!! "BX".$tag->bl_code."-".$tag->pe_type_code.":".$sizearr[substr($tag->pe_type_code,2,2)]." : ".$tag->ww_line."++++++++".$tag->brd_amount !!}</small></center>
                                            @endif
                                            </br>
                                     
                                            {{-- <div class="fix-row-center">
                                                <div class="fix-grid-left"><small>{!! DNS1D::getBarcodeHTML("B".substr($tag->ww_line,1,1).$tag->bl_code."-".$tag->pe_type_code,'C128',1,35) !!}</small><small>{!! ("BX".$tag->bl_code."-".$tag->pe_type_code) !!}</small></div>
                                                <div class="fix-grid-right"><small>{!! DNS1D::getBarcodeHTML($tag->brd_lot,'C128',1,35) !!}</small><small>{{ $tag->ww_line."++++++++" }}</small></div>
                                            </div> --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: {{ $tag->brd_color }};" class="wip-fix-fontsize-output text-center"><b style="font-size:19px;">รหัสสินค้า</b></th>
                                        <td style="background-color: {{ $tag->brd_color }};" colspan="3"><b style="font-size:70px;"><center>{{ "BX".$tag->bl_code."-".$tag->pe_type_code }}</center></b></td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: {{ $tag->brd_color }};" class="wip-fix-fontsize-output text-center"><b style="font-size:19px;">ชื่อสินค้า</b></th>
                                        <td style="background-color: {{ $tag->brd_color }};"  colspan="3"><b style="font-size:30px;" class="text-center"><center>{{ $tag->bl_name." ".$tag->pe_type_name }}</center></b></td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: {{ $tag->brd_color }};" class="wip-fix-fontsize-output text-center"><b style="font-size:19px;">วันที่คัด</b></th>
                                        <td style="background-color: {{ $tag->brd_color }};"  colspan="3"><b style="font-size:30px;"><center>{{ date('d ',strtotime($tag->ww_lot_date)) }}{{ $thmonth[date('n',strtotime($tag->ww_lot_date))]." " }}{{ date('Y',strtotime($tag->ww_lot_date)) }}</center></b></td>
                                    </tr>
                                    <tr>
                                        <th class="wip-fix-fontsize-output" style="font-size:17px;">หมวดสินค้า</th>
                                        <td style="font-size:17px;">FG</td>
                                        <td style="font-size:17px;">ไลน์กะผลิต</td>
                                        <td style="font-size:17px;">{{ $tag->ww_group }}</td>
                                    </tr>
                                    <tr>
                                        <th style="font-size:17px;" class="wip-fix-fontsize-output">Lot.</th>
                                        <td style="font-size:17px;">{{ $tag->ww_line."++++++++" }}</td>
                                        <td style="font-size:17px;">ไลน์กะคัด</td>
                                        <td style="font-size:17px;">{{ $tag->ww_group }}</td>
                                    </tr>
                                    <tr>
                                        <th style="font-size:17px;" class="wip-fix-fontsize-output">จำนวน</th>
                                        <td style="font-size:17px;">
                                            @if ($tag->brd_amount < 10){{ '00'.$tag->brd_amount }} </td>
                                            @elseif ($tag->brd_amount < 100){{ '0'.$tag->brd_amount }} </td>
                                            @else{{ $tag->brd_amount }}</td>
                                            @endif
                                        <td style="font-size:17px;">ผู้ตรวจสอบ</td>
                                        <td style="font-size:17px;">{{ $tag->brd_checker }}</td>
                                    </tr>
                                    <tr>
                                        <th style="font-size:17px;" class="wip-fix-fontsize-output">เวลา</th>
                                        <td style="font-size:17px;"></td>
                                        <td style="font-size:17px;">ผู้คัด</td>
                                        <td style="font-size:17px;">{{ $tag->name1 }} - {{ $tag->name2 }}</td>
                                    </tr>
                                    <tr>
                                        <th style="font-size:17px;" class="wip-fix-fontsize-output">น้ำหนัก</th>
                                        <td style="font-size:17px;"></td>
                                        <td style="font-size:17px;">วันที่ผลิต</td>
                                        <td style="font-size:17px;">{{ date('d ',strtotime($dateproduct)) }}{{ $thmonth[date('n',strtotime($dateproduct))]}} {{ date('Y',strtotime($dateproduct)) }}</td>
                                    </tr>
                                    <tr>
                                        @if ($tag->brd_remark == '' )
                                        <th style="font-size:17px;" class="wip-fix-fontsize-output">หมายเหตุ</th>
                                        <td style="font-size:17px;" colspan="3">ไม่มีข้อมูล</td>
                                        @else
                                        <th style="font-size:17px;" class="wip-fix-fontsize-output">หมายเหตุ</th>
                                        <td style="font-size:17px;" colspan="3">{{ $tag->brd_remark }}</td>
                                        @endif 
                                    </tr>
                                   
                                </table>

                                <br><hr style="border-top: 1px dashed black;"><br>

                                <input type="hidden" name="" id="output1aid" value="">
                                <table style="width:100%;" class="table-output">
                                    <tr>
                                        <th style="background-color: {{ $colordate }};" colspan="1"><u style="color:black;"><h2 style="text-align: left;" class="text-left"><b >{{ substr($tag->brd_lot,6,4) }}</b></h2></u></th>
                                        <td colspan="2" style="background-color: {{ $colordate }};" colspan="1"></td>
                                        <th style="background-color: {{ $colordate }};" colspan="2"><u style="color:black;"><h2 style="text-align: right;" class="text-right"><b >ผ่ายคัดบอร์ด</b></h2></u></th>
                                    </tr>
                                    <tr> <!--DNS1D::getBarcodeHTML('C128',1,35)-->
                                        {{-- <center>{!! DNS1D::getBarcodeHTML("BX".$tag->bl_code."-".$tag->pe_type_code.$tag->ww_line."++++++++".$tag->brd_amount,'C128',1,35)  !!}</center> --}}

                                         {{-- // ส่วนบาร์โค้ต ยาว --}}
                                         @if ($tag->brd_amount < 10)
                                         <td colspan="4"><br><center>{!! DNS1D::getBarcodeHTML("B".substr($tag->ww_line,1,1).$tag->bl_code."-".$tag->pe_type_code.$tag->brd_lot.'00'.$tag->brd_amount,'C128',1,50)  !!}
                                             <small>{!! "BX".$tag->bl_code."-".$tag->pe_type_code.":".$sizearr[substr($tag->pe_type_code,2,2)]." : ".$tag->ww_line."++++++++".'00'.$tag->brd_amount !!}</small> </center>                                           
                                     @elseif ($tag->brd_amount < 100)
                                         <td colspan="4"><br><center>{!! DNS1D::getBarcodeHTML("B".substr($tag->ww_line,1,1).$tag->bl_code."-".$tag->pe_type_code.$tag->brd_lot.'0'.$tag->brd_amount,'C128',1,50)  !!}
                                             <small>{!! "BX".$tag->bl_code."-".$tag->pe_type_code.":".$sizearr[substr($tag->pe_type_code,2,2)]." : ".$tag->ww_line."++++++++".'0'.$tag->brd_amount !!}</small>   </center>                                         
                                     @else
                                         <td colspan="4"><br><center>{!! DNS1D::getBarcodeHTML("B".substr($tag->ww_line,1,1).$tag->bl_code."-".$tag->pe_type_code.$tag->brd_lot.$tag->brd_amount,'C128',1,50)  !!}
                                             <small>{!! "BX".$tag->bl_code."-".$tag->pe_type_code.":".$sizearr[substr($tag->pe_type_code,2,2)]." : ".$tag->ww_line."++++++++".$tag->brd_amount !!}</small></center>
                                     @endif
                                     <br>
                                        
                                        {{-- <div class="fix-row-center">
                                            <div class="fix-grid-left"><small>{!! DNS1D::getBarcodeHTML("B".substr($tag->ww_line,1,1).$tag->bl_code."-".$tag->pe_type_code,'C128',1,35) !!}</small><small>{!! ("BX".$tag->bl_code."-".$tag->pe_type_code) !!}</small></div>
                                            <div class="fix-grid-right"><small>{!! DNS1D::getBarcodeHTML($tag->brd_lot,'C128',1,35) !!}</small><small>{{ $tag->ww_line."++++++++" }}</small></div>
                                        </div> --}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class=" wip-fix-fontsize-output"><b style="font-size:19px;">รหัสสินค้า</b></th>
                                    <td colspan="3" class=""><b style="font-size:19px;">{{ "BX".$tag->bl_code."-".$tag->pe_type_code }}</b></td>
                                </tr>
                                <tr>
                                    <th class=" wip-fix-fontsize-output"><b style="font-size:19px;">ชื่อสินค้า</b></th>
                                    <td colspan="3" class=""><b style="font-size:19px;">{{ $tag->bl_name." ".$tag->pe_type_name }}</b></td>
                                </tr>
                                <tr>
                                    <th class=" wip-fix-fontsize-output"><b style="font-size:19px;">วันที่คัด</b></th>
                                    <td colspan="3" class=""><b style="font-size:19px;">{{ date('d ',strtotime($tag->ww_lot_date)) }}{{ $thmonth[date('n',strtotime($tag->ww_lot_date))]." " }}{{ date('Y',strtotime($tag->ww_lot_date)) }}</b></td>
                                </tr>
                                <tr>
                                    <th class="wip-fix-fontsize-output" style="font-size:17px;">หมวดสินค้า</th>
                                    <td style="font-size:17px;">FG</td>
                                    <td style="font-size:17px;">ไลน์กะผลิต</td>
                                    <td style="font-size:17px;">{{ $tag->ww_group }}</td>
                                </tr>
                                <tr>
                                    <th style="font-size:17px;" class="wip-fix-fontsize-output">Lot.</th>
                                    <td style="font-size:17px;">{{ $tag->ww_line."++++++++" }}</td>
                                    <td style="font-size:17px;">ไลน์กะคัด</td>
                                    <td style="font-size:17px;">{{ $tag->ww_group }}</td>
                                </tr>
                                <tr>
                                    <th style="font-size:17px;" class="wip-fix-fontsize-output">จำนวน</th>
                                    <td style="font-size:17px;">
                                        @if ($tag->brd_amount < 10){{ '00'.$tag->brd_amount }} </td>
                                            @elseif ($tag->brd_amount < 100){{ '0'.$tag->brd_amount }} </td>
                                            @else{{ $tag->brd_amount }}</td>
                                            @endif
                                    <td style="font-size:17px;">ผู้ตรวจสอบ</td>
                                    <td style="font-size:17px;">{{ $tag->brd_checker }}</td>
                                </tr>
                                <tr>
                                    <th style="font-size:17px;" class="wip-fix-fontsize-output">เวลา</th>
                                    <td style="font-size:17px;"></td>
                                    <td style="font-size:17px;">ผู้คัด</td>
                                    <td style="font-size:17px;">{{ $tag->name1 }} - {{ $tag->name2 }}</td>
                                </tr>
                                <tr>
                                    <th style="font-size:17px;" class="wip-fix-fontsize-output">น้ำหนัก</th>
                                    <td style="font-size:17px;"></td>
                                    <td style="font-size:17px;">วันที่ผลิต</td>
                                    <td style="font-size:17px;">{{ date('d ',strtotime($dateproduct)) }}{{ $thmonth[date('n',strtotime($dateproduct))]}} {{ date('Y',strtotime($dateproduct)) }}</td>
                                </tr>
                                <tr>
                                    @if ($tag->brd_remark == '' )
                                    <th style="font-size:17px;" class="wip-fix-fontsize-output">หมายเหตุ</th>
                                    <td style="font-size:17px;" colspan="3">ไม่มีข้อมูล</td>
                                    @else
                                    <th style="font-size:17px;" class="wip-fix-fontsize-output">หมายเหตุ</th>
                                    <td style="font-size:17px;" colspan="3">{{ $tag->brd_remark }}</td>
                                    @endif 
                                </tr>
                            </table>
                       
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div class="modal fade" id="changetypecolor" tabindex="-1" role="dialog" aria-labelledby="EditTypeColor" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="EditTypeColor"><b>แก้ไขสีชนิดสินค้า</b></h3>
            </div>
            <form id="edittypecolorform" class="form-inline md-form form-sm mt-0 text-center">
                <input id="brdid" type="hidden" value="{{ $id }}">
                <div class="modal-body">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="text-center">
                        <b style="font-size:18px;">เลือกสี : </b><input style="width:50%;height:30px;" type="color" list="presetColors" name="brd_color" value="{{ $colorbyid }}">
                        <datalist style="width:50%;" id="presetColors">
                            @foreach ($pcs as $pcs)
                                <option value="{{ $pcs->pcs_color }}">{{ $pcs->pcs_color }}</option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-success">บันทึก</button>
                    
                    


                </div>
            </form>
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
<script src="{{ asset('js/bcp.min.js')}}"></script>
<script src="{{ asset('js/bcp.en.min.js')}}"></script>
<script src="{{ asset('js/bcp.en.js')}}"></script>

<!-- Data Table -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.jqueryui.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<!--<script src="https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/padStart"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
    Both of these plugins are recommended to enhance the
    user experience. -->

    <script type="text/javascript">
        @if (env('APP_ENV') !== 'production')
            var path = "/gypman-tech"; // /gypman-tech
        @else
            var path = "/gypman-tech"; // /gypman-tech
        @endif
    </script>
    </html>
