@extends('layouts.app')

@section('content')
<script>
$(document).ready(function () {
    $('#addbrandlistform').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "{{ route('inputbrandslist') }}",
            data: $('#addbrandlistform').serialize(),
            dataType: "json", // ✅ บังคับให้รับ JSON
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    text: response.message, // ✅ ใช้ข้อความจาก Response
                    showConfirmButton: false,
                    timer: 1500
                });

                // ✅ รีโหลดหน้าเมื่อบันทึกสำเร็จ
                setTimeout(function () {
                    location.reload();
                }, 1500);
            },
            error: function (xhr) {
                let errorMessage = "เกิดข้อผิดพลาด กรุณาลองใหม่";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    text: errorMessage,
                    showConfirmButton: true,
                });
            }
        });
    });
});

    </script>

    <script>
$(document).ready(function(){
    $('#brnadslisttable').on('change', '.toggle-blstatus', function() {
        var status = $(this).prop('checked') ? 1 : 0;
        var id = $(this).data('id');

        console.log("Updating bl_status:", status, "for bl_id:", id);

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('updateBrandStatus') }}",
            data: {
                'bl_status': status,
                'bl_id': id
            },
            success: function(data) {
                if (data.status === 'success') {
                    let message = data.bl_status == 1 ? 
                        `<b>เปิดการใช้งาน ${data.bl_code} - ${data.bl_name} แล้ว</b>` : 
                        `<b>ปิดการใช้งาน ${data.bl_code} - ${data.bl_name} แล้ว</b>`;

                    notif({
                        msg: message,
                        type: data.bl_status == 1 ? "success" : "warning"
                    });
                } else {
                    notif({
                        msg: "<b>เกิดข้อผิดพลาด: " + data.message + "</b>",
                        type: "error"
                    });
                }
            },
            error: function(xhr) {
                console.error("AJAX Error:", xhr.responseText);
                notif({
                    msg: "<b>เกิดข้อผิดพลาดในการอัปเดตสถานะ</b>",
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
                    <a href="{{ route('mainmenu') }}" class="btn btn-warning"  name="button"><em class="text-white fa fa-home"><b>  กลับไปยังเมนูหลัก</b></em></a>
                </div>
                <div class="container-fluid">
                    <h2><b>รายการแบรนด์</b></h2>
                    @if (Auth::user()->name == 'Manager' || Auth::user()->name == 'arnut' || Auth::user()->name == 'r'|| Auth::user()->name == 'durf22311'|| Auth::user()->name == 'durf123456')
                        <a class="btn btn-success btn-md fa fa-plus" data-target="#notiaddbrandlist" data-toggle="modal">   เพิ่มรายการ</a>
                        <table id="brnadslisttable" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Brandcode</th>
                                    <th class="text-center">ชื่อแบรนด์</th>
                                    <th class="text-center">สถานะเปิดใช้งาน</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brandslist as $brandslist)
                                    <tr>
                                        <td class="text-center">{{ $count++ }}</td>
                                        <td class="text-center">{{ $brandslist->bl_code }}</td>
                                        <td class="text-center">{{ $brandslist->bl_name }}</td>
                                        <td class="text-center">
                                            <input data-id="{{ $brandslist->bl_id }}" class="toggle-blstatus" type="checkbox" netliva-switch data-active-text="เปิด" data-passive-text="ปิด" data-active-color="#40bf40" data-passive-color="#ff4d4d" {{ $brandslist->bl_status ? 'checked' : '' }}>
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

    <div class="modal fade" id="notiaddbrandlist" tabindex="-1" role="dialog" aria-labelledby="AddBrandList" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="AddBrandList">เพิ่มรายการแบรนด์</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addbrandlistform" class="form-inline md-form form-sm mt-0 text-right" enctype="multipart/form-data" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="col-md-6 text-right">
                            <b style="font-size:18px;">ชื่อแบรนด์ : </b><input style="width:60%;" class="form-group text-center" type="text" name="bl_name" placeholder="ใส่ชื่อแบรนด์ เช่น GM"/>
                        </div>
                        <div class="col-md-6 text-left">
                            <b style="font-size:18px;">Brand Code : </b><input style="width:60%;" class="form-group text-center" type="text" name="bl_code" placeholder="Brand Code เช่น 04"/>
                        </div>
                        <br>
                        <br>
                        <br>
                        <center>
                            <button class="btn btn-success" type="submit" name="button">บันทึกข้อมูล   <i class="fas fa-save"></i></button>
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
