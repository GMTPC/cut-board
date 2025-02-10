<!DOCTYPE html>
<html class="all-font-size" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mini.css/2.3.7/mini-default.min.css" integrity="sha512-3jRZ/RINdy5kQXlIFiRSrDNf7KVxO6f7U4OGHXOTd5J+qRoimjea7WTYTpwZloSNFLagmWu7AHsmyORSN9Y2Pw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
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
            font: menu;
        }

        .barcode-output {
            font-family: 'code128';
            font-size: 36px;
        }

        .table-output {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-output th,
        .table-output td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .table-output th {
            background-color: #3555F7;
            color: white;
        }

        .qr-code {
            text-align: center;
            padding: 20px;
        }

        .large-text {
            font-size: 60px;
        }

        .medium-text {
            font-size: 30px;
        }

        .wms-text {
            font-size: 16px;
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
        onReady(function() {
            show('page-content', true);
            show('load', false);
        });
    </script>
    <title>{{ config('websetting.webtitle') }}</title>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    {{-- {{ dd($data ) }} --}}
    <div id="load"></div>
    <div id="page-content">
        <div class="container-fluid bg-white">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <div class="container">
                            <div class="print-output">
                                <table class="table-output">
                                    <tr>
                                        <th rowspan="2">Pallet No.</th>
                                        <td rowspan="2">A001</td>
                                        <td>วันที่คัด</td>
                                        <td colspan="3">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
                                        <th rowspan="2" style="width:70px">TAG</th>
                                        <th rowspan="2" style="width:70px">FG</th>
                                    </tr>
                                    <tr>
                                        <td>เวลา</td>
                                        <td>-</td>
                                        <td colspan="2"></td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" rowspan="2">
                                            <small class="wms-text">สำหรับงานคลัง</small> <br><br>
                                            {{ QrCode::size(150)->generate('https://103.40.144.249:8081/qrcodeinterface/B236-A10109240905A001100') }}
                                            <br />
                                            <small>B236-A10109240905A001100</small>
                                        </td>
                                        @php
    $bl_id_formatted = isset($brandList->bl_id) ? (strlen($brandList->bl_id) == 1 ? '0' . $brandList->bl_id : $brandList->bl_id) : 'N/A';
@endphp
<td colspan="6" class="large-text">
    BX{{ $bl_id_formatted }}-{{ $peTypeCode }}
</td>


     </tr>

                                    <tr>
                                    <td colspan="6" class="medium-text">
    {{ $bl_name ?? 'N/A' }}  {{ $wip_sku_name ?? 'N/A' }}
</td>
                                    </tr>
                                    <tr>
                                        <td>น้ำหนัก/หน่วย</td>
                                        <td>- </td>
                                        {{-- <td >kg.</td> --}}
                                        <td colspan="2">จำนวน</td>
                                        <td>หน่วย</td>
                                        <td>แผ่น</td>
                                        <td rowspan="6" colspan="2">
                                            <small class="wms-text">สำหรับงาน WMS</small> <br><br>
                                            {{ QrCode::size(150)->generate('B236-A10109240830B003P1P2P3') }}
                                            <br />
                                            <small>B236-A10109240830B003P1P2P3</small></center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Line</td>
                                        <td> {{ $ww_line ?? 'N/A' }}</td>
                                        <td rowspan="2" colspan="4" class="large-text">
    {{ $brd_amount ?? 'N/A' }}
</td>                                    </tr>
                                    <tr>
                                        <td>วันที่ผลิต</td>
                                        <td>04 กันยายน 2024</td>
                                    </tr>
                                    <tr>
                                        <td>ผู้คัด</td>
                                        <td>  {{ $emp1 ?? 'N/A' }} - {{ $emp2 ?? 'N/A' }}</td>
                                        <td>ไลน์/กะผลิต</td>
                                        <td>{{ $ww_group ?? 'N/A' }}</td>
                                        <td>หน่วยย่อย</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>ผู้ตรวจสอบ</td>
                                        <td>    {{ $brd_checker ?? 'N/A' }}
                                        </td>
                                        <td>ไลน์/กะคัด</td>
                                        <td>{{ $ww_group ?? 'N/A' }}</td>
                                        <td>หมายเหตุ</td>
                                        <td>-</td>
                                    </tr>

                                </table>

                                <br />
                                <hr style="border-top: 1px dashed black;"> <br /> <br />

                                <table class="table-output">
                                    <tr>
                                        <th rowspan="2">Pallet No.</th>
                                        <td rowspan="2">A001</td>
                                        <td>วันที่คัด</td>
                                        <td colspan="3">05 กันยายน 2024</td>
                                        <th rowspan="2" colspan="2">ผ่ายคัดบอร์ด</th>
                                    </tr>
                                    <tr>
                                        <td>เวลา</td>
                                        <td>-</td>
                                        <td colspan="2"></td>
                                    </tr>

                                    <tr>
                                    @php
    $bl_id_formatted = isset($brandList->bl_id) ? (strlen($brandList->bl_id) == 1 ? '0' . $brandList->bl_id : $brandList->bl_id) : 'N/A';
@endphp
                                        <td colspan="8" class="medium-text"> BX{{ $bl_id_formatted }}-{{ $peTypeCode }}</td>

                                    </tr>
                                    <tr>
                                        <td colspan="8" class="medium-text">    {{ $bl_name ?? 'N/A' }}  {{ $wip_sku_name ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>น้ำหนัก/หน่วย</td>
                                        <td>-</td>
                                        <td colspan="2">จำนวน</td>
                                        <td>หน่วย</td>
                                        <td>แผ่น</td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td>Line</td>
                                        <td>WipWorking</td>
                                        <td rowspan="2" colspan="4" class="large-text"> {{ $brd_amount ?? 'N/A' }}</td>
                                        <td rowspan="2" colspan="4"></td>
                                    </tr>
                                    <tr>
                                        <td>วันที่ผลิต</td>
                                        <td>04 กันยายน 2024</td>
                                    </tr>
                                    <tr>
                                        <td>ผู้คัด</td>
                                        <td>  {{ $emp1 ?? 'N/A' }} - {{ $emp2 ?? 'N/A' }}</td>
                                        <td>ไลน์/กะผลิต</td>
                                        <td>{{ $ww_group ?? 'N/A' }}</td>
                                        <td>หน่วยย่อย</td>
                                        <td>-</td>
                                        <td colspan="2"></td>
                                    </tr>