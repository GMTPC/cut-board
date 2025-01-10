@extends('layouts.app')

@section('content')
<!--<meta HTTP-EQUIV="Refresh"  CONTENT="3600">-->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.btn-custom {
    background-color: #4CAF92; /* สีเขียวสด */
    color: white; /* สีตัวอักษร */
    padding: 10px 20px; /* ขนาดของปุ่ม */
    text-align: center; /* จัดกลาง */
    text-decoration: none; /* ตัดเส้นใต้ */
    font-size: 16px; /* ขนาดตัวอักษร */
    border-radius: 5px; /* มุมมน */
    display: inline-block; /* จัดให้อยู่ในแนวเดียวกับองค์ประกอบอื่น */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
    transition: all 0.3s ease; /* เพิ่มเอฟเฟกต์เวลา hover */
}

.btn-custom:hover {
    background-color: #45A085; /* สีเขียวเข้มขึ้น */
    box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.3); /* เพิ่มเงาเวลา hover */
    transform: translateY(-2px); /* ขยับขึ้นเล็กน้อย */
}

.btn-custom i {
    margin-right: 8px; /* ระยะห่างระหว่างไอคอนและข้อความ */
}
.text-center {
    margin-top: 10px; /* ลดระยะห่างด้านบน */
    margin-bottom: 10px; /* ลดระยะห่างด้านล่าง */
}

.nav-tabs {
    margin-top: 5px; /* ลดช่องว่างระหว่างแท็บกับข้อความ */
}
.panel-gmt {
    border: 2px solid #E65522; /* Orange border */
    border-radius: 5px; /* Rounded corners */
    margin: 15px 0; /* Adjust vertical spacing */
    overflow: hidden; /* Prevent extra border or overflow */
}

.panel-gmt .panel-heading {
    background-color: #E65522; /* Orange background */
    color: white; /* White text */
    padding: 10px;
    margin: 0; /* Remove extra margin */
    border: none; /* Remove unnecessary border */
    border-radius: 0; /* Ensure no additional radius */
}

.panel-body {
    padding: 15px;
}
.btn-sm i {
        font-size: 16px; /* ขนาดของไอคอน */
        margin-right: 5px; /* ระยะห่างระหว่างไอคอนกับข้อความ */
    }

    .btn-warning {
        background-color: #f0ad4e;
        border: none;
    }

    .btn-warning:hover {
        background-color: #ec971f;
    }

    .btn-info {
        background-color: #5bc0de;
        border: none;
    }

    .btn-info:hover {
        background-color: #31b0d5;
    }

    .btn-danger {
        background-color: #d9534f;
        border: none;
    }

    .btn-danger:hover {
        background-color: #c9302c;
    }

    </style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ดึง URL ของ Route
        const line = 'L2'; // ตัวอย่าง line ที่ต้องการ
        const url = `/getemp/${line}`; // แก้ไข URL ตาม Route

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const select = document.querySelector('select[name="wip_empgroup_id"]');
                // ล้างข้อมูลใน select
                select.innerHTML = '<option value="0">เลือกผู้คัด</option>';
                // เติมข้อมูลจาก API
                data.forEach(group => {
                    const option = document.createElement('option');
                    option.value = group.id;
                    option.textContent = `${group.emp1} - ${group.emp2}`;
                    select.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    });
</script>
<script>
    document.getElementById('subline1').addEventListener('click', function (e) {
        e.preventDefault(); // ป้องกันการรีเฟรชหน้า

        // ดึงค่าจากฟอร์ม
        const wipBarcode = document.getElementById('wip_barcode').value;

        // ส่งข้อมูลด้วย AJAX
        fetch('/insert-wip', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // ต้องเพิ่ม CSRF Token
            },
            body: JSON.stringify({
                wip_barcode: wipBarcode,
                wip_amount: 1, // ใส่ค่าที่ต้องการส่ง เช่น จำนวน
                wip_empgroup_id: 1, // ตัวอย่างข้อมูล
                pe_type_code: 'PE123', // ตัวอย่างข้อมูล
                wp_working_id: 1, // ตัวอย่างข้อมูล
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message); // แสดงข้อความตอบกลับ
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>
<!-- เชื่อมต่อ jQuery และ SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#insertwipline1').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "{{ url('/insert-wip/line') }}/" + "{{ $line }}" + "/" + "{{ $work_id }}",
            data: $('#insertwipline1').serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: response.title,
                        html: '<small style="color:green;">' + response.message + '</small>',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    window.setTimeout(function() {
                        location.reload();
                    }, 800);
                }
            },
            error: function(xhr) {
                let response = xhr.responseJSON;

                // กรณีไม่พบข้อมูล work process
                if (response.message === 'ไม่พบข้อมูลกระบวนการทำงานสำหรับ work_id นี้') {
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่พบข้อมูล',
                        html: '<small style="color:red;">ไม่พบข้อมูลกระบวนการทำงานสำหรับ work_id นี้</small>',
                        showConfirmButton: true
                    });
                }
                // กรณี line ไม่ตรงกับ work_id
                else if (response.message === 'Line ไม่ตรงกับ work_id') {
                    Swal.fire({
                        icon: 'error',
                        title: 'ไลน์ไม่ตรงกัน',
                        html: '<small style="color:red;">Line ใน URL: ' + response.line_from_url + '<br>Line ในฐานข้อมูล: ' + response.line_from_db + '</small>',
                        showConfirmButton: true
                    });
                }
                // กรณี Barcode ซ้ำ
                else if (response.message === 'บาร์โค้ดซ้ำในระบบ กรุณาตรวจสอบอีกครั้ง') {
                    Swal.fire({
                        icon: 'error',
                        title: 'บันทึกข้อมูลไม่สำเร็จ',
                        html: '<small style="color:red;">บาร์โค้ดซ้ำในระบบ กรุณาตรวจสอบอีกครั้ง</small>',
                        showConfirmButton: true
                    });
                }
                // กรณี Barcode ไม่ถูกต้อง
                else if (response.message === 'สาเหตุอาจจะมาจากชนิดที่ไม่เหมือนกัน บาร์โค้ดซ้ำ ยังไม่เลือกผู้คัด หรือ รูปแบบไม่ถูกต้อง') {
                    Swal.fire({
                        icon: 'error',
                        title: 'บันทึกข้อมูลไม่สำเร็จ',
                        html: '<small style="color:red;">สาเหตุอาจจะมาจากชนิดที่ไม่เหมือนกัน บาร์โค้ดซ้ำ ยังไม่เลือกผู้คัด หรือ รูปแบบไม่ถูกต้อง</small>',
                        showConfirmButton: true
                    });
                }
                // กรณีเกิด Error อื่น ๆ
                else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        html: '<small style="color:red;">มีบางอย่างผิดพลาด กรุณาลองใหม่อีกครั้ง</small>',
                        showConfirmButton: true
                    });
                }
            }
        });
    });
});
</script>



<div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                <a href="#" class="btn-custom">
    <i class="fa fa-arrow-left"></i> ข้อมูลลงาน
</a>
                </div>
                <h2><b>ระบบคัดบอร์ด : Line {{ $line }}</b></h2>
<input type="hidden" id="getline" name="" value="{{ $line }}">
<div class="alert alert-danger"></div>
<div class="text-center" style="margin-top: 10px; margin-bottom: 10px;"> <!-- ใช้ inline CSS -->
    <ul class="nav nav-tabs" style="margin-top: 5px;"> <!-- ใช้ inline CSS -->
        <li class="active tab-size-xs">
            <a href="#barcode">
                <h4>ข้อมูลเข้า (WIP) และ ข้อมูลออก (FG)</h4>
            </a>
        </li>
        <li class="tab-size-xs">
            <a href="#detail">
                <h4>ข้อมูลขาเข้าแบบละเอียด</h4>
            </a>
        </li>
    </ul>
</div>


            <div class="container-fluid">
            <h4><b>กลุ่มที่คัด :</b> <b>{{ $workprocess->line ?? 'ไม่มีข้อมูล' }}{{ $workprocess->group ?? 'ไม่มีข้อมูล' }}</b></h4>
<h4><b>วันที่เริ่ม :</b> <b>{{ $workprocess->date ? \Carbon\Carbon::parse($workprocess->date)->format('d-m-Y') : 'ไม่มีข้อมูล' }}</b></h4>
<h4><b>สถานะ :</b> <b style="color: green;">{{ $workprocess->status ?? 'ไม่มีข้อมูล' }}</b></h4>


            </div>

            <div class="tab-content">
                <div id="barcode" class="tab-pane fade in active">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="text-center">

                            </div>
                        </div>
                           
                       
                        <div class="panel panel-gmt">
    <div class="panel-heading text-center" style="font-size:18px;">สรุปรายการ</div>
    <div class="panel-body" style="padding: 0;">
        <div class="row text-center">
            <div class="col-md-3 col-xs-3">
            <h4>จำนวนแผ่นเข้า</h4>
            <h4>{{ $totalWipAmount ?? '0' }}</h4>
            </div>
            <div class="col-md-3 col-xs-3">
                <h4>จำนวนแผ่นออก</h4>
                <h4>0</h4>
            </div>
            <div class="col-md-3 col-xs-3">
                <h4>คงค้าง (HD)</h4>
                <h4>{{ $totalWipAmount ?? '0' }}</h4>
            </div>
            <div class="col-md-3 col-xs-3">
                <h4>เสีย (NG)</h4>
                <h4>0</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- ข้อมูลเข้า (WIP) -->
    <div class="col-md-6">
        <div class="panel panel-gmt">
            <div class="panel-heading text-center" style="font-size:18px;">
                ข้อมูลเข้า (WIP)
            </div>
            <div class="panel-body">
                <!-- ฟอร์มสำหรับเพิ่มข้อมูล -->
                <form id="insertwipline1" class="form-inline text-center" action="{{ url('/insert-wip/line/' . $line . '/' . $work_id) }}" method="POST">
                    @csrf
                    <select name="wip_empgroup_id" class="form-control" required>
                        <option value="">เลือกผู้คัด</option>
                        @foreach ($empGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->emp1 }} - {{ $group->emp2 }}</option>
                        @endforeach
                    </select>
                    <input id="wip_barcode" name="wip_barcode" type="text" class="form-control text-center" 
                           placeholder="สแกนบาร์โค้ดยิงรับเข้า WIP" minlength="24" required autofocus>
                    <input type="hidden" name="pe_type_code" value="TYPECODE">
                    <input type="hidden" name="wp_working_id" value="{{ $work_id }}">
                    <input type="hidden" name="wip_amount" value="10">
                    <button id="subline1" type="submit" class="btn btn-default">
                        <i class="fa fa-barcode"></i>
                    </button>
                </form>

                <!-- ตารางข้อมูล -->
                <table id="myTableCode" class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 50%;">บาร์โค้ด</th>
                            <th style="width: 25%;">ผู้คัด</th>
                            <th style="width: 20%;"><i class="fa fa-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody>
    @forelse ($wipBarcodes as $index => $barcode)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ $barcode->wip_barcode }}
            </td>
            <td>
    <div style="display: flex; align-items: center; justify-content: center; gap: 5px; white-space: nowrap; height: 100%;">
        <span>{{ $barcode->groupEmp->emp1 }} - {{ $barcode->groupEmp->emp2 }}</span>
        <!-- ไอคอนแก้ไข -->
        <a href="javascript:void(0);" class="btn btn-black btn-xs" title="แก้ไขข้อมูล" 
           style="padding: 5px 10px; font-size: 12px; background-color: black; color: white; border-color: black;">
            <i class="fa fa-pencil-square-o"></i>
        </a>
    </div>
</td>

            <td>
                <div style="display: flex; gap: 8px; justify-content: center;">
                    <!-- ปุ่มแก้ไข -->
                    <a href="javascript:void(0);" class="btn btn-warning btn-xs" title="แก้ไขข้อมูล" style="padding: 5px 10px; font-size: 12px;">
                        <i class="fa fa-pencil-square-o"></i>
                    </a>

                    <!-- ปุ่มแก้ไขจำนวน -->
                    <a href="javascript:void(0);" class="btn btn-info btn-xs" title="แก้ไขจำนวน" style="padding: 5px 10px; font-size: 12px;">
                        <i class="fa fa-sort-numeric-asc"></i>
                    </a>

                    <!-- ปุ่มลบ -->
                    <a href="javascript:void(0);" onclick="confirmDelete()" class="btn btn-danger btn-xs" title="ลบข้อมูล" style="padding: 5px 10px; font-size: 12px; margin-right: 5px;">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">ไม่มีข้อมูล</td>
        </tr>
    @endforelse
</tbody>


                </table>
            </div>
        </div>
    </div>

    <!-- ข้อมูลออก (FG) -->
    <div class="col-md-6">
        <div class="panel panel-gmt">
            <div class="panel-heading text-center" style="font-size:18px;">
                ข้อมูลออก (FG)
            </div>
            <div class="panel-body">
                <div class="text-center">
                    <button class="btn btn-warning">
                        <i class="fa fa-plus"></i> ออกรหัส FG
                    </button>
                </div>
                <table id="myTable" class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>LOT FG</th>
                            <th>จำนวน</th>
                            <th>OUT FG CODE</th>
                            <th><i class="fa fa-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



  

 <script>
 document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.querySelector("#fgData");
    const dataRows = tableBody.querySelectorAll(".data-row");
    const noDataRow = tableBody.querySelector(".no-data");

    // ตรวจสอบจำนวนแถวที่มีข้อมูล
    if (dataRows.length === 0) {
        noDataRow.style.display = "table-row"; // แสดงแถวไม่มีข้อมูล
    } else {
        noDataRow.style.display = "none"; // ซ่อนแถวไม่มีข้อมูล
    }
});
 </script>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                                 
                                       
            {{-- <div id="detail" class="tab-pane fade">
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table id="myTable" class="table table-hover bg-white text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>รหัสบาร์โค้ด</th>
                                    <th>ประเภทสินค้า</th>
                                    <th>วันที่</th>
                                    <th>จำนวน</th>
                                    <th>จำนวนที่เสีย (NG)</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="codewip4"></td>
                                        <td class="codewip6"></td>
                                        <td class="codewip7"></td>
                                    </tr>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>รหัสบาร์โค้ด</th>
                                    <th>ประเภทสินค้า</th>
                                    <th>วันที่</th>
                                    <th>จำนวน</th>
                                    <th>จำนวนที่เสีย (NG)</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div id="tofg" class="tab-pane fade">
                <br>
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>LOT FG</th>
                                    <th>จำนวน</th>
                                    <th>OUT FG CODE</th>
                                    <th>Brands</th>
                                    <th>ประเภทแผ่น</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                            <td></td> <!--class="outfg2"-->
                                            <td></td> <!--class="outfg2"-->
                                        <td></td>
                                            <td></td>
                                            <td>ยิปซั่ม </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
                <div id="csvtb" class="tab-pane fade">
                    <br>
                    <div class="container-fluid">
                        <h4><b>CSV Table</b></h4>
                        <div class="table-responsive">
                            <table id="csvsummary" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">บาร์โค้ด</th>
                                        <th class="text-center">Lot</th>
                                        <th class="text-center">จำนวน</th>
                                        <th class="text-center">รหัสแปรรูปผลผลิต</th>
                                        <th class="text-center">ตำแหน่งเก็บ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td class="text-center text-csv-end"></td>
                                            <td class="text-center text-csv-end"></td>
                                            <td class="text-center text-csv-end"></td>
                                            <td class="text-center text-csv-end">3</td>
                                            <td class="text-center text-csv-end">=</td>
                                        </tr>
                                    -->
                                        <tr>
                                            <td class="text-center text-csv-end">PK01-000008 <br>
                                                </td>
                                            <td class="text-center text-csv-end"><br>
                                                </td>
                                            <td class="text-center text-csv-end"><br>
                                                </td>
                                            <td class="text-center text-csv-end">3<br>
                                                4</td>
                                            <td class="text-center text-csv-end"> <br>
                                                </td>
                                        </tr>
                                  
                                            <tr>
                                                <td class="text-center text-csv-end"></td>
                                                <td class="text-center text-csv-end"></td>
                                                <td class="text-center text-csv-end"></td>
                                                <td class="text-center text-csv-end">4</td>
                                                <td class="text-center text-csv-end"></td>
                                            </tr>
                                        
                                            <tr>
                                                <td class="text-center text-csv-end"></td>
                                                <td class="text-center text-csv-end"></td>
                                                <td class="text-center text-csv-end">0</td>
                                                <td class="text-center text-csv-end">4</td>
                                                <td class="text-center text-csv-end"></td>
                                            </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">บาร์โค้ด</th>
                                        <th class="text-center">Lot</th>
                                        <th class="text-center">จำนวน</th>
                                        <th class="text-center">รหัสแปรรูปผลผลิต</th>
                                        <th class="text-center">ตำแหน่งเก็บ</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="text-center">
                        {{-- <a id="csvsumbtn" class="btn btn-success" name="button"><b>บันทึก CSV  <i class="fas fa-file-download"></i></b></a> --}}

                        {{-- <a href="" class="btn btn-success" name="button"><b>บันทึก CSV  <i class="fas fa-file-download"></i></b></a> 
                        
                       
                    </div>
                </div>
        </div>
        <h3><p class="text-danger">ต้องรอการตรวจสอบรับเข้าคลังสินค้าให้หมด จึงจะสามารถจบทำงานได้</p></h3> <br>
       
        <!--ปิดปุ่มgขียว 27/05/21  -->
        
        
        
        <div class="text-center">
            <a class="btn btn-success" data-target="#inputend" data-toggle="modal" name="button" ><b>บันทึกจบ (END) <i class="fas fa-file-export"></i></b></a>
        </div>
          
            
    </div>
</div>
</div>

<div class="modal fade" id="notideleteline1" tabindex="-1" role="dialog" aria-labelledby="DeleteBarcodeLine1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="DeleteBarcodeLine1">ลบข้อมูลบาร์โค้ด</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deletfieldline1">
                <div class="modal-body">
        

                    <input type="hidden" name="id" id="delete_line1id">
                    <h4 style="color:red;">คุณต้องการลบข้อมูลบาร์โค้ด <b style="color:red;"> <u id="barcodetarget"></u> </b>หรือไม่</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-danger">ลบบาร์โค้ด</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="outfg" tabindex="-1" role="dialog" aria-labelledby="OutFg" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="OutFg"><b>ออกรหัส FG</b></h3>
            </div>
            <form id="outfgform" class="form-inline md-form form-sm mt-0 text-right" enctype="multipart/form-data" accept-charset="utf-8" method="post">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="panel panel-gmt">
                        <div class="panel-heading text-center" style="font-size:18px;">ออกรหัส FG</div>
                        <div class="panel-body" style="padding-top: 0px;padding-left: 0px;">
                            <br>
                            <div class="text-center">
                                <input class="form-control text-center" type="number" name="brd_amount" max="{{ $amount }}" value="100" data-toggle="tooltip" title="กรอกจำนวน" placeholder="กรอกจำนวน" required>
                                @include('frontend.selectbrand') 
                                &nbsp;&nbsp;&nbsp;
                                <select id="select_emp_id" name="brd_eg_id" class="margin-select selectpicker show-tick form-control" aria-required="true" data-size="9" data-dropup-auto="true" data-live-search="true" data-style="btn-warning btn-sm text-white" data-width="fit" data-container="body" required>
                                    <option value="0">เลือกผู้คัด</option>
                                        <option style="font-size:15px;" data-tokens="1" value="{{ $empbywip->eg_id }}"></option>
                                </select>
                                &nbsp;&nbsp;
                                <input style="width:30%;" class="form-control text-center" name="brd_checker" type="text"  placeholder="ผู้ตรวจสอบ" required> <br>
                                <b>เลขหลังบอร์ด</b>
                                <input style="width:30%;" class="form-control text-center" name="brd_backboard_no" type="text" placeholder="เลขหลังบอร์ด">
                                <b>เพิ่มหมายเหตุ</b>
                                <input style="width:30%;" class="form-control text-center" name="brd_remark" type="text" name="" placeholder="หมายเหตุ">
                            </div>
                            <input type="hidden" name="brd_working_id" value="">
                            <input type="hidden" name="brd_lot" value="{{ $lotgenerator }}">
                        </div>
                    </div>
                    <br>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success fas fa-save">  บันทึก </button> 
                    
                    <!--  $sToken = "qyfH6ZcGoMCGxuOufKmdjOXXQwSVzsVjihm6vf17PQ4";
                        $ttexe = "BX".$tag->bl_code."-".$tag->pe_type_code.":".$sizearr[substr($tag->pe_type_code,2,2)].":".$tag->ww_line."++++++++";
                        $sMessage1 = "คัดบอร์ดออก tag FG ";
                        $sMessage = $sMessage1 ."".$ttexe;
                    -->
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="notiamount" tabindex="-1" role="dialog" aria-labelledby="EditAmount" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="EditAmount"><b>แก้ไขจำนวน</b> <b id="showoutlot"></b> </h3>
            </div>
            <form id="editamountform" class="form-inline md-form form-sm mt-0 text-center">
                <input id="wipidamount" type="hidden" name="wip_id">
                <div class="modal-body">
                   
                    <div class="text-center">
                        <h4><b>Barcode : <u id="showwipbarcode2"></u> </b></h4><br>
                        <b style="font-size:17px;">จำนวนที่ต้องการแก้ไข : </b><input type='number' id='wipnewamount' class='text-center' name='wip_amount'>
                        <input type='hidden' id='wipbarcodechange' class='text-center' name='wip_barcode'>
                    </div>
                    {{-- <div class="text-center" id="editamountid">
                    </div> --}}
                </div>
              
            </form>
        </div>
    </div>
</div>
<div class="modal fade"  id="notiinputng" tabindex="-1" role="dialog" aria-labelledby="DeleteBarcodeLine1" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="InputNg"><b>เพิ่มข้อมูลของเสีย</b></h3>
                <h4><b>Barcode : <i id="showbarcodewip"></i></b></h4>
            </div>
            <div class="modal-body">
                <div class="panel-body">
                    <h4><b>สรุปรายการของเสีย </b></h4>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" id="listresultng">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:70%;">ของเสีย</th>
                                    <th class="text-center" style="width:20%;">จำนวน</th>
                                    <th class="text-center" style="width:10%;"><i class="fa fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody id="ng-data">

                            </tbody>
                        </table>
                    </div>
                    <input class="inputng_id" type="hidden" name="inputng_id" id="inputng_id">
                    <div id="panel-ng" class="panel panel-gmt">
                        <div class="panel-heading text-center" style="font-size:18px;">เพิ่มข้อมูลของเสีย</div>
                        <div class="panel-body" style="padding-top: 0px;padding-left: 0px;">
                            <br>
                            <div class="text-center">
                                <a class="btn btn-default btn-sm" style="font-size:13px;" id="addl1a" href="#" role="button"><span class="glyphicon glyphicon-plus"></span>&nbsp;เพิ่มของที่เสีย</a>
                            </div>
                            <form id="inputngform" class="form-inline md-form form-sm mt-0">
                                <div class="container-fluid">
                                    <div class="table-responsive">
                                        <table class="table" id="wipline1awaste">
                                            <tr>
                                                <th class="text-left">ของเสีย</th>
                                                <th class="text-center">จำนวนที่เสีย</th>
                                            </tr>
                                            <tr>
                                                <td class="text-left">
                                                    <select name="amg_ng_id[]" data-size="2" class="btn btn-info btn-sm" data-live-search="true" style="font-size:16px;">
                                                        <option value="">เลือกของเสีย</option>
                                                        
                                                            <option style="font-size:16px;" data-tokens="" value="">
                                                            </option>
                                                    </select>
                                                </td>
                                                <td class="text-left"><input type="number" value="" name="amg_amount[]" placeholder="จำนวน"/>
                                                    <input type="hidden" value="" name="amg_wip_id[]" id="inputng_idchild"></td>
                                                </tr>
                                            </table>
                                            <div class="text-right">
                                                <button id="removelistng" class="btn btn-warning btn-sm " type="button" name="button"><span class="fas fa-redo-alt"></span>&nbsp;ทำใหม่</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        {{-- <a href="#{" onClick="; return ;">

                                        </a>  --}}
                                        <button class="fas fa-save btn btn-success" type="submit"> บันทึก</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="ngmodalbtn" type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deletengnoti" tabindex="-1" role="dialog" aria-labelledby="DeleteBarcodeLine1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="DeleteBarcodeLine1">ลบข้อมูลของเสีย</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deletengform">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}

                        <input type="text" name="id" id="deletengid">
                        <h4 style="color:red;">คุณต้องการลบข้อมูลของเสีย <b style="color:red;"> <u id="deletengname"></u> </b>หรือไม่</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-danger">ลบบาร์โค้ด</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="inputend" tabindex="-1" role="dialog" aria-labelledby="DeleteBarcodeLine1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="AddBrands"><b>ยืนยันการจบการทำงาน (END)</b></h3>
                    <p style="color:red;font-size:15px;">เมื่อกดยืนยัน สถานะจะถูกเปลี่ยนเป็น<u>จบการทำงาน</u> ข้อมูลทั้งหมดจะไม่สามารถแก้ไขได้ โปรดตรวจสอบข้อมูลให้เรียบร้อยก่อนกดยืนยัน</p>
                </div>
                <form id="forminputend" class="md-form text-center" enctype="multipart/form-data" method="post">
                    <div class="modal-body">
                        <div class="panel panel-gmt">
                            <div class="panel-heading text-center" style="font-size:18px;">สรุปรายการ</div>
                            <div class="panel-body" style="
                            padding-top: 0px;
                            padding-left: 0px;
                            ">
                            <div class="col-md-3 col-xs-3">
                                <h4 class="text-center">จำนวนแผ่นเข้า</h4>
                            </div>
                            <div class="col-md-3 col-xs-3">
                                <h4 class="text-center">จำนวนแผ่นออก</h4>
                            </div>
                            <div class="col-md-3 col-xs-3">
                                <h4 class="text-center">คงค้าง (HD)</h4>
                            </div>
                            <div class="col-md-3 col-xs-3">
                                <h4 class="text-center">เสีย (NG)</h4>
                            </div>
                        </div>
                        <div class="panel-body" style="
                        padding-top: 0px;
                        padding-left: 0px;
                        ">
                        <div class="col-md-3 col-xs-3">
                            <h4 class="text-center"></h4>
                            <input class="form-control text-center" type="hidden" name="ws_input_amount" value="" readonly>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <h4 class="text-center"></h4>
                            <input class="form-control text-center" type="hidden" name="ws_output_amount" value="" readonly>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <h4 class="text-center"></h4>
                            <input class="form-control text-center" type="hidden" name="ws_holding_amount" value="" readonly>
                        </div>
                        <div class="col-md-3 col-xs-3">
                                <h4 class="text-center">0</h4>
                                <input class="form-control text-center" type="hidden" name="ws_ng_amount" value="0" readonly>
                                <h4 class="text-center"></h4>
                                <input class="form-control text-center" type="hidden" name="ws_ng_amount" value="" readonly>
                            <input type="hidden" name="ws_working_id" value="" readonly>
                            <input type="hidden" name="wh_working_id" value="" readonly>

                        </div>
                        <input type="hidden" name="wh_barcode" value="" readonly>
                        <input type="hidden" name="wh_lot" value="" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                    <a href="#" style="cursor:not-allowed;" class="btn btn-light" data-toggle="tooltip" title="ยอดคงค้างติดลบ">ยืนยัน</a>
                    <button type="submit" class="btn btn-success">ยืนยัน</button>
            </div>
        </form>
    </div>
</div>
</div>

<div class="modal fade" id="notideleteoutfg" tabindex="-1" role="dialog" aria-labelledby="DeleteBarcodeFg" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="DeleteBarcodeFg">ลบข้อมูลบาร์โค้ด</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deletoutfg">
                <div class="modal-body">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}

                    <input type="hidden" name="id" id="delete_outfgid">
                    <h4 class="text-center" style="color:red;">คุณต้องการลบข้อมูลบาร์โค้ด <b>Lot No : </b>  <b id="showoutfg"></b> หรือไม่</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-danger">ลบบาร์โค้ด</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="notieditbrand" tabindex="-1" role="dialog" aria-labelledby="EditBrand" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="EditBrand"><b>แก้ไขข้อมูล LOT No.</b> <b id="showoutlot"></b> </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editbrandform">
                <div class="modal-body">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="text-center">
                        @include('frontend.selectbrand')
                    </div>
                    <input type="hidden" name="id" id="editbrandid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editempwip" tabindex="-1" role="dialog" aria-labelledby="EditEnpWip" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="EditEnpWip"><b>แก้ไขข้อมูลผู้คัด </b></h3>
                <h4><b>Barcode :<u><i id="empwipbarcode"></i></u></b></h4>
            </div>
            <div class="container-fluid">
                <form id="editempwipform">
                    <div class="modal-body">
                        <div class="text-center">
                            <select name="wip_empgroup_id" class="margin-select selectpicker show-tick form-control" aria-required="true" data-size="9" data-dropup-auto="true" data-live-search="true" data-style="btn-info btn-md text-white" data-width="fit" data-container="body" required>
                                <option style="font-size:15px;" value="0">เลือกผู้คัด</option>
                                    <option style="font-size:15px;" data-tokens="1" value="\">\</option>
                            </select>
                        </div>
                        <input type="hidden" name="id" id="empwipid">
                        <input type="hidden" name="wip_empgroup_id_old" id="empgropidwip">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-success">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





    <div class="modal fade" id="ngeachidnoti" tabindex="-1" role="dialog" aria-labelledby="Ngeachidnoti" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="Ngeachidnoti"><b>ข้อมูลของเสีย <u id="ngbarcodenoti"></u> </b></h3>
                </div>
                <div class="modal-body">
                    <div class="panel panel-gmt">
                        <div class="panel-heading text-center" style="font-size:18px;">สรุปรายการ</div>
                        <div class="panel-body" style="
                        padding-top: 0px;
                        padding-left: 0px;
                        ">
                        <div class="col-md-6 col-xs-6">
                            <h4 class="text-center"><b>ของเสีย</b></h4>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <h4 class="text-center"><b>จำนวนที่เสีย</b></h4>
                        </div>
                    </div>
                    <div class="panel-body" style="
                    padding-top: 0px;
                    padding-left: 0px;
                    ">
                    <div id="showngeachid">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">

var slectElement = '<td class="text-left"><select name="amg_ng_id[]" data-size="2" class="btn btn-info btn-sm" data-live-search="true" style="font-size:16px;"><option value="">เลือกของเสีย</option><option value=""></option></select></td>';
        var inputngid = '<input type="hidden" value="" name="amg_wip_id[]" id="inputng_idchild">';
        var inputElement = '<td class="text-left"><input type="number" value="" name="amg_amount[]" placeholder="จำนวน"/>'+inputngid+'</td>'
        var workid = '';
        var line = '';
        var enddate = "";
        var group = '';
        var hiddeninput = '<input type="hidden" name="eg_line[]" value=""><input type="hidden" name="eg_division[]" value="QC"><input type="hidden" name="eg_emp_id_1[]" value=""><input type="hidden" name="eg_emp_id_2[]" value=""><input type="hidden" name="eg_status[]" value="1">';
        var addscanwipemp = '<select name="wip_empgroup_id" class="margin-select selectpicker show-tick form-control" aria-required="true" data-size="9" data-dropup-auto="true" data-live-search="true" data-style="btn-info btn-sm text-white" data-width="fit" data-container="body" required><option style="font-size:15px;" value="0">เลือกผู้คัด</option></select>';
            var inputbarcode = '<input type="text" id="pe_user_emp" name="pe_working_id" value=""><input type="text" id="pe_type_code" name="pe_type_code"><input type="text" name="wip_amount" id="wip_amount" class="form-control text-center" data-toggle="tooltip" title="จำนวนที่คัดแล้ว" placeholder="จำนวนที่คัดแล้ว" required/><input type="text" name="wip_working_id" value=""><input id="wip_barcode" name="wip_barcode" type="text" class="form-control text-center" data-toggle="tooltip" title="สแกนบาร์โค้ด" style="width:40%;" placeholder="สแกนบาร์โค้ดยิงรับเข้า WIP" autofocus><input type="text" name="wp_working_id" value=""><input type="text" id="wp_date_product" name="wp_date_product"><br>';
        </script>
        @endsection


 