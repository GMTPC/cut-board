@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
$(document).ready(function () {
    $('.view-details').on('click', function (e) {
        e.preventDefault(); // ❌ ป้องกันการเปิดลิงก์ทันที

        let indexno = $(this).data('index'); // ✅ ดึงค่า `cswi_index`

        console.log("📢 CSWI Index No:", indexno); // ✅ แสดงค่าใน Console

        if (!indexno) {
            console.warn("🚨 ค่าของ cswi_index เป็นค่าว่าง!");
            alert("ไม่พบค่า Index กรุณาตรวจสอบข้อมูล"); // ✅ แจ้งเตือน
            return;
        }

        // ✅ ถ้าค่าถูกต้อง เปิดลิงก์ไปยัง csvwhsaved/{indexno}
        window.location.href = `/csvwhsaved/${indexno}`;
    });
});
</script>

<script>
$(document).ready(function(){
    $('#checkcsvform').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "{{ route('insertcheckcsv') }}",
            data: $('#checkcsvform').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false
                });
            },
            success: function(response){
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1000
                });

                // ✅ เคลียร์ค่าบาร์โค้ด
                $("#ccw_barcode").val('');

                // ✅ โหลดข้อมูลใหม่จาก csvdetailrealtime และอัปเดต index ทันที
                $.get("{{ route('csvdetailrealtime') }}", function(data) {
                    $('#csvdetailrealtime').html(data);
                });

                // ✅ อัปเดตค่า index ในฟอร์ม `#csvindexform`
                $('#cswi_index').val(response.ccw_index);  // อัปเดตค่า hidden input
                $('#index_display').text(response.ccw_index); // แสดงค่า index ใหม่
            },
            error: function(response){
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: response.responseJSON.message || "ไม่สามารถบันทึกข้อมูลได้",
                    showConfirmButton: true,
                });
            }
        });
    });
});
</script>

<script>
$(document).ready(function(){
    $('#deleteccwform').on('submit', function(e){
        e.preventDefault();

        let ccw_id = $('#ccw_id_hiden').val(); // ดึงค่า ID ที่ต้องการลบ

        $.ajax({
            type: "DELETE",
            url: "{{ route('deleteccw', '') }}/" + ccw_id, // เรียก API ลบ
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1000
                });

                $('#deleteccwbarcode').modal('hide'); // ปิด Modal หลังจากลบสำเร็จ

                // โหลดข้อมูลใหม่
                $.get("{{ route('csvdetailrealtime') }}", function(data) {
                    $('#csvdetailrealtime').html(data);
                });
            },
            error: function(response){
                console.log("🔴 **Error Response:**", response);
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: response.responseJSON.message || "ลบข้อมูลไม่สำเร็จ",
                    showConfirmButton: true,
                });
            }
        });
    });

    // เมื่อคลิกปุ่มลบ ให้เปิด Modal พร้อมกำหนดค่า ccw_id
    $('.delete-ccw').on('click', function() {
        let ccw_id = $(this).data('id');
        let ccw_barcode = $(this).data('barcode');

        $('#ccwbarcodeheader').text(ccw_barcode);
        $('#ccw_id_hiden').val(ccw_id);

        $('#deleteccwbarcode').modal('show'); // เปิด Modal
    });
});

    </script>

    <script>
$(document).ready(function(){
    $('#csvindexform').on('submit', function(e){
        e.preventDefault();

        // ✅ ใช้ route() เพื่อให้ URL ถูกต้อง
        let url = "{{ route('insertcheckcsvindex') }}";
        console.log("📢 กำลังส่ง AJAX ไปที่:", url);

        $.ajax({
            type: "POST",
            url: url, // ✅ ใช้ route() แทน path
            data: $('#csvindexform').serialize(),
            headers: { 
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // ✅ ป้องกัน CSRF
            },
            beforeSend: function(){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function(result){
                console.log("📢 ค่า result:", result);

                if (!result.indexno) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อผิดพลาด',
                        text: 'ไม่พบค่า indexno',
                        showConfirmButton: true
                    });
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                });

                // ✅ เปิด popup ทันที
                let popup = window.open("{{ url('/outcheckcsvwh') }}/" + result.indexno, '_blank', 'width=800,height=600');

                if (!popup) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'การเปิดไฟล์ถูกบล็อก',
                        text: 'กรุณาอนุญาตให้เว็บไซต์นี้เปิดหน้าต่างใหม่ (popup)',
                        showConfirmButton: true
                    });
                }

                // ✅ รีเฟรชหน้าอัตโนมัติหลังจาก 2 วินาที
                setTimeout(function(){
                    location.reload();
                }, 2000);
            },
            error: function(xhr){
                console.log("📢 ค่า Error Response:", xhr);

                let errorMessage = "เกิดข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">' + errorMessage + '</small>',
                    showConfirmButton: true
                });

                $('#noticsvindex').modal('hide');
            }
        });
    });
});




        </script>
    <div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                    <a href="{{ route('mainmenu') }}" class="btn btn-warning"  name="button"><em class="text-white fa fa-th"><b>  กลับไปก่อนหน้า</b></em></a>
                </div>
                <h2><b>ระบบตรวจสอบข้อมูลสินค้าเข้าสู่ระบบ</b></h2>
                <div class="container-fluid">
                <form id="checkcsvform" class="form-inline md-form form-sm mt-0 text-center" method="post" action="{{ route('insertcheckcsv') }}">
    @csrf
    <input id="ccw_barcode" style="width:30%;" class="text-center" type="text" name="ccw_barcode" placeholder="สแกนบาร์โค้ด">
    <button type="submit" name="button"><i style="font-size:25px;" class="fa fa-barcode"></i></button>
</form>

                    <div class="row">
                        <div class="col-md-6">
                           
                            <div class="text-left">
                                <a class="btn btn-success fa fa-save" data-target="#noticsvindex" data-toggle="modal">&nbsp;&nbsp;&nbsp;ออก CSV</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-right">
                                <a class="btn btn-default fa fa-file" data-target="#noticsvallfile" data-toggle="modal">&nbsp;&nbsp;&nbsp;ไฟล์ที่บันทึกแล้ว</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-gmt">
    <div class="panel-heading text-center" style="font-size:18px;">รายการยิงรับเข้า</div>
    <div class="panel-body" style="padding-top: 0px; padding-left: 0px;">
        <div class="col-md-5 col-xs-5">
            <h4 class="text-center">บาร์โค้ด</h4>
        </div>
        <div class="col-md-3 col-xs-3">
            <h4 class="text-center">Lot</h4>
        </div>
        <div class="col-md-2 col-xs-2">
            <h4 class="text-center">จำนวน</h4>
        </div>
        <div class="col-md-1 col-xs-1">
            <h4 class="text-center"><i class="fa fa-cog"></i></h4>
        </div>
    </div>
    <div class="panel-body" style="padding-top: 0px; padding-left: 0px;">
        <div id="csvdetailrealtime">
            @if($detail->isEmpty())
                <p class="text-center text-muted">ไม่มีข้อมูล</p>
            @else
                @foreach ($detail as $item)
                    <div class="row">
                        <div class="col-md-5 col-xs-5">
                            <h4 class="text-center">{{ $item->ccw_barcode }}</h4>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <h4 class="text-center">{{ $item->ccw_lot }}</h4>
                        </div>
                        <div class="col-md-2 col-xs-2">
                            <h4 class="text-center">{{ $item->ccw_amount }}</h4>
                        </div>
                        <div class="col-md-1 col-xs-1">
                            <h4 class="text-center">
                                <a href="#" class="btn btn-danger btn-sm delete-ccw" data-id="{{ $item->ccw_id }}" data-barcode="{{ $item->ccw_barcode }}">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </h4>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- submit savne-->
<div class="modal fade" id="noticsvindex" tabindex="-1" role="dialog" aria-labelledby="CsvIndex" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="CsvIndex"><b>ยืนยันข้อมูล</b></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="csvindexform" method="post" action="{{ route('insertcheckcsvindex') }}">
    @csrf  {{-- ✅ เพิ่ม CSRF Token เพื่อให้ Laravel รับค่าได้ --}}
    <div class="modal-body">
        <h4 class="text-center">ยืนยันการบันทึกข้อมูลเพื่อออก CSV</h4>
        <input type="hidden" name="cswi_index" id="cswi_index" value="{{ $index ?? '' }}">
        </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
        <button type="submit" class="btn btn-success">ยืนยัน</button>
    </div>
</form>

        </div>
    </div>
</div>

<div class="modal fade" id="noticsvallfile">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">ไฟล์ที่บันทึกแล้ว</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p class="text-danger">สามารถค้นหาด้วยชื่อได้ที่ช่องค้นหา</p>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="csvallfiletable">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">ชื่อไฟล์</th>
                                <th class="text-center">วันที่</th>
                                <th class="text-center"><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
    @foreach ($savedfiles as $file)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            
            <!-- แสดงชื่อไฟล์ PWH + เวลาสร้าง -->
            <td class="text-center">
                {{ isset($file->created_at) ? 'PWH' . $file->created_at->format('dmYHi') : '-' }}
            </td>

            <!-- แสดงวันที่สร้าง -->
            <td class="text-center">
                {{ isset($file->created_at) ? $file->created_at->format('Y-m-d H:i:s') : '-' }}
            </td>

            <!-- ปุ่มกดไปที่ csvwhsaved -->
            <td class="text-center">
    <a class="btn btn-default btn-sm view-details" data-index="{{ $file->cswi_index }}" data-toggle="tooltip" title="ดูข้อมูล">
        <i class="fa fa-file-text"></i>
    </a>
</td>


        </tr>
    @endforeach
</tbody>

                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteccwbarcode" tabindex="-1" role="dialog" aria-labelledby="DeleteCCW" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="DeleteCCW"><b>ลบข้อมูล </b> </h3>
            </div>
            <form id="deleteccwform" method="POST" action="#">
    @csrf <!-- Laravel CSRF Token (แต่ AJAX ใช้ headers ใส่ให้แล้ว) -->
    <div class="modal-body">
        <h4><b>ต้องการลบข้อมูล</b> 
            <u style="color:red;"><b id="ccwbarcodeheader"></b></u> <b>หรือไม่</b>
        </h4>
        <input type="hidden" id="ccw_id_hiden" name="ccw_id" value="">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
        <button type="submit" class="btn btn-success">ยืนยัน</button>
    </div>
</form>

        </div>
    </div>
</div>

@endsection
