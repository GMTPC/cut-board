<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Warehouse QR CODE Scanner</title>
    <link rel="stylesheet" href="{{ asset('AdminLTE-master/bower_components/bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-master/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifIt.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    @if (session('success'))
        Swal.fire({
            title: "สำเร็จ!",
            text: "{{ session('success') }}",
            icon: "success",
            confirmButtonText: "ตกลง"
        });
    @endif

    @if (session('error'))
        Swal.fire({
            title: "ผิดพลาด!",
            text: "{{ session('error') }}",
            icon: "error",
            confirmButtonText: "ตกลง"
        });
    @endif
});
</script>
    <div class="container-fluid bg-white">
        <div class="panel-body">

            <h2 style="font-size:5.5em;"><b>ระบบคลังสินค้า</b></h2>
            <h1 style="font-size:3.5em;"><b>สแกน QR CODE รับเข้าจากคัดบอร์ด</b></h1>
            <h2>เวอร์ชั่น : {{ config('websetting.webversion') }}</h2>

            <div class="row">
                <div class="col-xs-12">
                    <br><br>
                    <form method="post" action="{{ route('insertcheckcsvqrcode') }}">
    @csrf
    <input type="hidden" name="ccw_barcode" value="{{ $qr }}">
    <button style="font-size:80px;width:95%;" id="saveqrbtn1" data-lot="{{ substr($qr, 11, 10) }}"
        class="btn btn-success btn-lg" type="submit">บันทึก</button>
</form>

                    <br><br><br><br>
                </div>

                <div class="col-xs-12">
                    <div class="text-left">
                        <button style="font-size:80px;" id="savewithdefact"
                            class="btn btn-warning btn-lg">บันทึกแบบมีของเสีย</button>
                        <br><br>
                        <div hidden id="qrcodedefectform">
                            <form method="post" action="{{ route('insertcheckcsvqrcodewithdefect') }}">
                                @csrf
                                <select style="width:100%;font-size:50px;" class="" name="wrtc_description" required>
                                    <option value="">เลือกคำอธิบาย</option>
                                    <option value="ไม่ครบจำนวน">ไม่ครบจำนวน</option>
                                    <option value="มีของเสีย">มีของเสีย</option>
                                </select>
                                <br><br>
                                <label style="font-size:40px;" for="">หมายเหตุ (หากมี) :</label>
                                <textarea name="wrtc_remark" rows="8" cols="118"></textarea>
                                <input type="hidden" name="ccw_barcode" value="{{ $qr }}">
                                <br><br>
                                <div class="col-xs-4">
                                    <button style="font-size:60px;" id="saveqrbtn2" class="btn btn-warning btn-lg"
                                        type="submit" name="button">บันทึก</button>
                                </div>
                                <div class="col-xs-4">
                                    <button style="font-size:60px;" id="cancelqrdefectform" type="button"
                                        class="btn btn-danger btn-lg">ยกเลิก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      
            <br><br><br><br>
            <center>
                <h2 style="font-size:5.5em; color:red;"><b>คุณไม่มีสิทธิ์ใช้งานในส่วนนี้</b></h2>
                <p style='font-size:60px;color:blue;'>
                    <a href='https://gypman-tech.com/login'>กดเพื่อไปหน้าเว็บไซต์</a>
                </p>
            </center>

    </div>

</body>

<script src="{{ asset('AdminLTE-master/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE-master/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('AdminLTE-master/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('js/notifIt.min.js')}}"></script>

<script type="text/javascript">
    $('#savewithdefact').click(function () {
        $('#qrcodedefectform').show();
    });

    $('#cancelqrdefectform').click(function () {
        $('#qrcodedefectform').hide();
    });

    $('#saveqrbtn1').on('click', function () {
        var lot = $(this).data('lot');
        notif({
            msg: "<b>" + lot + " เข้าสู่คลังแล้ว</b>",
            type: "success"
        });
    });
</script>


</html>
