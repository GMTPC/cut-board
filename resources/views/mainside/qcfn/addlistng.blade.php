@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
$(document).ready(function(){
    $('#addnglistform').on('submit', function(e){
        e.preventDefault(); // ป้องกันหน้าโหลดซ้ำ

        var formData = new FormData(this);
        formData.append('_token', "{{ csrf_token() }}"); // เพิ่ม CSRF Token

        $.ajax({
            type: "POST",
            url: "{{ route('inputlistng') }}", // ใช้ route() ให้ Laravel กำหนดเส้นทางที่ถูกต้อง
            data: formData,
            processData: false,
            contentType: false,
            success: function(){
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                });
                window.setTimeout(function(){
                    location.reload();
                }, 1350);
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText); // แสดงรายละเอียดข้อผิดพลาดใน Console
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true,
                });
            }
        });
    });
});
</script>
<script>
$(document).ready(function(){
    $('#nglisttable').on('change','.toggle-lngstatus', function() {
        var status = $(this).prop('checked') ? 1 : 0;
        var id = $(this).data('id');

        $.ajax({
            type: "POST", // เปลี่ยนจาก GET เป็น POST
            url: "{{ route('lngstatus') }}", // ใช้ route() เพื่อความปลอดภัย
            data: {
                'lng_status': status,
                'lng_id': id,
                '_token': "{{ csrf_token() }}" // ส่ง CSRF Token ไปด้วย
            },
            dataType: "json",
            success: function(data) {
                if (data.lng_status == 1) {
                    notif({
                        msg: "<b>เปิดการใช้งาน " + data.lng_name + " แล้ว</b>",
                        type: "success"
                    });
                } else {
                    notif({
                        msg: "<b>ปิดการใช้งาน " + data.lng_name + " แล้ว</b>",
                        type: "warning"
                    });
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                notif({
                    msg: "<b>เกิดข้อผิดพลาด</b>",
                    type: "error"
                });
            }
        });
    });
});
</script>

    <div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                    <a href="{{ route('mainmenu') }}" class="btn btn-warning" name="button">
                        <em class="text-white fa fa-home"><b> กลับไปยังเมนูหลัก</b></em>
                    </a>
                </div>
                <div class="container-fluid">
                    <h2><b>รายการของเสีย</b></h2>
                    @if (Auth::user()->name == 'Manager' || Auth::user()->name == 'arnut'|| Auth::user()->name == 'durf123456')
                        <a class="btn btn-success btn-md fa fa-plus" data-target="#notiaddnglist" data-toggle="modal"> เพิ่มรายการ</a>
                        <table id="nglisttable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">ชื่อชนิดของเสีย</th>
                                    <th class="text-center">สถานะเปิดใช้งาน</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nglist as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $item->lng_name }}</td>
                                        <td class="text-center">
                                            <input data-id="{{ $item->lng_id }}" class="toggle-lngstatus" type="checkbox"
                                                netliva-switch data-active-text="เปิด" data-passive-text="ปิด"
                                                data-active-color="#40bf40" data-passive-color="#ff4d4d"
                                                {{ $item->lng_status ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <center>
                            <h3 style="color:red;">คุณไม่มีสิทธิ์ใช้งานในส่วนนี้</h3>
                        </center>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal เพิ่มรายการของเสีย -->
    <div class="modal fade" id="notiaddnglist" tabindex="-1" role="dialog" aria-labelledby="AddNgList" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="AddNgList">เพิ่มรายการของเสีย</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addnglistform" class="form-inline md-form form-sm mt-0 text-right" enctype="multipart/form-data" method="post">
    @csrf
    <div class="modal-body">
        <div class="col-md-12 text-center">
            <b style="font-size:18px;">ชื่อรายการของเสีย: </b>
            <input style="width:30%;" class="form-group text-center" type="text" name="lng_name" placeholder="ใส่ชื่อรายการของเสีย เช่น ขอบแตก" required>
        </div>
        <br><br><br>
        <center>
            <button class="btn btn-success" type="submit">บันทึกข้อมูล <i class="fas fa-save"></i></button>
        </center>
    </div>
</form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

@endsection
