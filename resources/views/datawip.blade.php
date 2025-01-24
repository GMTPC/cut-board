@extends('layouts.app')

@section('content')
<!--<meta HTTP-EQUIV="Refresh"  CONTENT="3600">-->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .custom-form {
    margin: 0 auto;
    display: flex;
    justify-content: center;
}

    .move-up {
    margin-top: 0px; /* ขยับขึ้น */
}
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
  #insertwipline1 .form-control, 
    #insertwipline1 .selectpicker {
        margin-right: 10px; /* เพิ่มระยะห่างระหว่างช่อง */
        vertical-align: middle; /* จัดให้อยู่แนวเดียวกัน */
    }

    #insertwipline1 button {
        vertical-align: middle; /* จัดปุ่มให้อยู่แนวเดียวกับ input */
    }
    </style>
 <script>
$(document).ready(function () {
    // เมื่อมีการเปลี่ยนแปลงใน Dropdown
    $('#brd_brandlist_id').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        // ลบ "ติ๊กถูก" อันเก่าทั้งหมด
        $('#brd_brandlist_id option').prop('selected', false);

        // ทำให้เฉพาะตัวที่ถูกเลือกใหม่ "ติ๊กถูก"
        $(this).find('option').eq(clickedIndex).prop('selected', true);

        // รีเฟรช selectpicker เพื่ออัปเดตการแสดงผล
        $(this).selectpicker('refresh');

        // พับ Dropdown เมื่อเลือกแล้ว
        $(this).selectpicker('toggle');
    });
});
    </script>
 
 <script>
$(document).ready(function () {
    // เมื่อผู้ใช้เปลี่ยนตัวเลือกใน Dropdown ผู้คัด
    $('#select_emp_id').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        // ลบ "ติ๊กถูก" อันเก่าทั้งหมด
        $('#select_emp_id option').prop('selected', false);

        // ทำให้เฉพาะตัวที่ถูกเลือกใหม่ "ติ๊กถูก"
        $(this).find('option').eq(clickedIndex).prop('selected', true);

        // รีเฟรช selectpicker เพื่ออัปเดตการแสดงผล
        $(this).selectpicker('refresh');

        // พับ Dropdown หลังจากเลือก
        $(this).selectpicker('toggle');
    });
});


    </script>

 <script>
$(document).ready(function () {
    // ✅ เพิ่ม CSRF Token สำหรับ Laravel
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ✅ เพิ่มแถวใหม่ในตารางเมื่อคลิกปุ่ม
    $(document).ready(function () {
    // ✅ เพิ่ม CSRF Token สำหรับ Laravel
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ✅ เพิ่มแถวใหม่ในตารางเมื่อคลิกปุ่ม
    $("#addl1a").on("click", function () {
        var inputID = $('input[name="inputng_id"]').val() || ""; // รับค่า inputng_id (ถ้าไม่มีให้ใช้ค่าว่าง)
        var wipID = $("#inputng_idchild").val() || ""; // รับค่า wip_id (ถ้าไม่มีให้ใช้ค่าว่าง)

        // สร้าง HTML สำหรับ Dropdown (เลือกของเสีย)
        var selectElement = `
            <td class="text-left">
                <select name="amg_ng_id[]" data-size="2" class="btn btn-info btn-sm" data-live-search="true" style="font-size:16px;">
                    <option value="">เลือกของเสีย</option>
                    @foreach($listNgAll as $ng)
                        <option style="font-size:16px;" data-tokens="{{ $ng->lng_name }}" value="{{ $ng->lng_id }}">
                            {{ $ng->lng_name }}
                        </option>
                    @endforeach
                </select>
            </td>`;

        // สร้าง HTML สำหรับ Input (จำนวน)
        var inputElement = `
            <td class="text-left">
                <input type="number" value="" name="amg_amount[]" placeholder="จำนวน" required />
                <input type="hidden" value="{{ $wipBarcodes->first()->wip_id ?? '' }}" name="amg_wip_id[]" id="inputng_idchild">
            </td>`;

        // เพิ่มแถวใหม่ในตาราง
        var newRow = `<tr>${selectElement}${inputElement}</tr>`;
        $("#wipline1awaste").append(newRow);
    });
});

$(document).ready(function () {
    // ✅ ดักจับการ Submit Form
    $('#inputngform').on('submit', function (e) {
        e.preventDefault();

        let isValid = true;

        // ตรวจสอบข้อมูลก่อนส่ง
        $('#wipline1awaste').find('select, input[type="number"]').each(function () {
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'ข้อมูลไม่ครบถ้วน',
                html: '<small style="color:red;">กรุณากรอกข้อมูลให้ครบถ้วนก่อนบันทึก</small>',
                showConfirmButton: true,
            });
            return;
        }

        // ส่งข้อมูลด้วย AJAX
        $.ajax({
            type: "POST",
            url: "{{ route('addng') }}",
            data: $(this).serialize(),
            success: function () {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                });
                window.setTimeout(function () {
                    location.reload();
                }, 1200);
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                });
            }
        });
    });

    // ✅ ปุ่มทำใหม่ (Reset Form)
    $('#removelistng').click(function () {
        $('#wipline1awaste').find('input, select').val('');
        $('#wipline1awaste').find('.is-invalid').removeClass('is-invalid');
    });
});
});
</script>





















<script>
$(document).ready(function() {
    $('.open-delete-modal').click(function() {
        $('#notideleteline1').show();
    });
});

    </script>

    

<script>
    $(document).ready(function() {
        // เมื่อคลิกที่ปุ่ม open-ng-modal
        $('.open-ng-modal').click(function() {
            // เปิด Modal ที่มี id เป็น notiinputng
            $('#notiinputng').modal('show');
        });
    });
</script>

<script>
$(document).ready(function () {
    $('.open-noti-amount').click(function () {
        $('#notiamount').modal('show');  // เปิด Modal
    });
});
</script>
<script>
$(document).ready(function() {
    $('#editamountform').on('submit', function(e) {
        e.preventDefault();

        var id = $('#wipidamount').val();

        $.ajax({
            type: "POST",
            url: "{{ url('/editwipamg') }}/" + id,
            data: $(this).serialize() + '&_method=PUT',  // ส่งข้อมูลพร้อมระบุว่าเป็น PUT
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function() {
                    location.reload();
                }, 1300);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง',
                    showConfirmButton: true
                });
            }
        });
    });
});

</script>

<script>
$(document).ready(function() {
    $('#deletfieldline1').on('submit', function(e) {
        e.preventDefault();

        var id = $('#delete_line1id').val();     // ID ที่ต้องการลบ
        var workid = $('#workid').val();         // WORK ID ที่ต้องการใช้

        if (!id || !workid) {
            console.error("ID หรือ Work ID หายไป");
            return;
        }

        $.ajax({
            type: "POST",  // ✅ เปลี่ยนเป็น POST และใช้ _method: 'DELETE'
            url: "/deleteline1wip/" + workid + "/" + id,
            data: {
                _method: 'DELETE',  // ✅ ระบุว่าเป็น DELETE
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'ลบข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function() {
                    location.reload();
                }, 1300);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'ลบข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กรุณาลองใหม่อีกครั้ง</small>',
                    showConfirmButton: true
                });
            }
        });
    });
});


</script>



</script>
<script>
$(document).ready(function () {
    // เปิด Modal และตั้งค่า
    $(document).on('click', '.open-edit-modal', function () {
        const workingId = $(this).data('working-id');
        $('#empwipid').val(workingId);
        $('#editempwipform').attr('action', `/update-empgroup/${workingId}`);
        $('#editempwip').modal('show');
    });

    // เมื่อเปลี่ยนค่าของ Dropdown
    $('#wip_empgroup_id_1').on('change', function () {
        const selectedValue = $(this).val(); // ดึงค่าที่เลือกใหม่

        // ล้างตัวเลือกเก่าทั้งหมด
        $('#wip_empgroup_id_1 option').prop('selected', false); // ล้างตัวเลือกทั้งหมด
        $(`#wip_empgroup_id_1 option[value="${selectedValue}"]`).prop('selected', true); // ตั้งค่าตัวเลือกใหม่

        // รีเฟรช Select Picker เพื่ออัปเดตสถานะ
        $(this).selectpicker('refresh');

        // พับ Dropdown หลังจากเลือกตัวเลือก
        $(this).closest('.bootstrap-select').find('.dropdown-toggle').dropdown('toggle'); // บังคับปิด Dropdown
    });

    // ปิด Modal และรีเซ็ต Dropdown
    $('#editempwip').on('hidden.bs.modal', function () {
        // รีเซ็ตค่ากลับไปเริ่มต้น
        $('#wip_empgroup_id_1 option').prop('selected', false); // ล้างตัวเลือกทั้งหมด
        $('#wip_empgroup_id_1').val('0'); // ตั้งค่าเริ่มต้น
        $('#wip_empgroup_id_1').selectpicker('refresh'); // รีเฟรช Dropdown
    });

    // ส่งฟอร์มด้วย Ajax
    $('#editempwipform').on('submit', function (e) {
        e.preventDefault();

        const actionUrl = $(this).attr('action');
        const formData = $(this).serialize();

        $.ajax({
            url: actionUrl,
            type: 'PUT',
            data: formData,
            beforeSend: function () {
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    html: '<small style="color:green;">ระบบกำลังทำการบันทึกข้อมูล</small>',
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function () {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false
                });
                window.setTimeout(function () {
                    location.reload();
                }, 1350);
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">กด OK เพื่อปิดหน้าต่าง</small>',
                    showConfirmButton: true
                });
            }
        });
    });
});




</script>

<script>
$(document).ready(function () {
    // ดึงค่า line และ workId จาก URL
    const urlParts = window.location.pathname.split('/');
    const line = urlParts[urlParts.length - 2].replace('L', ''); // แปลง L2 เป็น 2
    const workId = urlParts[urlParts.length - 1]; // ดึง workId เช่น 30053

    console.log('Line:', line);
    console.log('Work ID:', workId);

    if (!line || !workId || isNaN(line) || isNaN(workId)) {
        Swal.fire({
            icon: 'error',
            title: 'URL ไม่ถูกต้อง',
            text: 'ไม่สามารถดึง Line หรือ Work ID จาก URL ได้',
            showConfirmButton: true,
        });
        return;
    }

    // ดักจับการ Submit ฟอร์ม
    $('#insertwipline1').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serializeArray();

        // ตรวจสอบข้อมูลบาร์โค้ดก่อนส่ง
        const barcode = $('#wip_barcode').val();
        if (!barcode || !barcode.startsWith('W') || barcode.length < 24) {
            Swal.fire({
                icon: 'error',
                title: 'รูปแบบบาร์โค้ดไม่ถูกต้อง',
                text: 'บาร์โค้ดต้องขึ้นต้นด้วย W และมีความยาวอย่างน้อย 24 ตัวอักษร',
                showConfirmButton: true,
            });
            return;
        }

        // เพิ่มข้อมูลเพิ่มเติมใน formData
        formData.push({ name: 'line', value: line }); // ส่ง line (เช่น 2)
        formData.push({ name: 'work_id', value: workId }); // ส่ง work_id

        // แสดง Loader ระหว่างรอการตอบกลับจากเซิร์ฟเวอร์
        Swal.fire({
            title: 'กำลังบันทึกข้อมูล...',
            text: 'กรุณารอสักครู่',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // ส่งข้อมูลไปยังเซิร์ฟเวอร์
        $.ajax({
            type: 'POST',
            url: `/insert-barcode/L/${line}/${workId}`, // URL ที่เซิร์ฟเวอร์รองรับ
            data: formData,
            success: function (response) {
                Swal.close(); // ปิด Loader
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: response.title || 'บันทึกเรียบร้อย',
                        text: response.message || 'ข้อมูลถูกบันทึกสำเร็จ',
                        timer: 1500,
                        showConfirmButton: false,
                    });

                    // รีเฟรชหน้าหลังจาก 1.5 วินาที
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: response.title || 'ข้อผิดพลาด',
                        text: response.message || 'ไม่สามารถบันทึกข้อมูลได้',
                        showConfirmButton: true,
                    });
                }
            },
            error: function (xhr) {
                Swal.close(); // ปิด Loader
                let errorMessage = 'เกิดข้อผิดพลาด';
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
    $('#outfgform').on('submit', function (e) {
        e.preventDefault(); // ป้องกันการ Submit แบบปกติ

        let line = "{{ $line }}";
        let workid = "{{ $work_id }}";
        let path = "{{ url('/') }}";

        $.ajax({
            type: 'POST',
            url: `${path}/outfgcode/${line}/${workid}`,
            data: $('#outfgform').serialize(),
            success: function (response) {
                // ตรวจสอบและแปลง response หากจำเป็น
                const data = typeof response === 'string' ? JSON.parse(response) : response;

                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: data.message || 'บันทึกข้อมูลสำเร็จ',
                    timer: 2000,
                    showConfirmButton: false,
                });
            },
            error: function (xhr) {
                // ตรวจสอบข้อความ Error
                let errorMessage = 'ไม่สามารถบันทึกข้อมูลได้';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        errorMessage = errorResponse.message || errorMessage;
                    } catch (e) {
                        console.error('Parsing Error:', e);
                    }
                }

                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: errorMessage,
                    showConfirmButton: true,
                });
            },
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
@if ($wipBarcodes->count() > 0 && $productTypes->count() > 0)
    <h4><b>ชนิดสินค้า :</b> <b>{{ $productTypes->first()->pe_type_name }}</b></h4>
@endif


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
                <h4>{{ ($totalWipAmount ?? 0) - ($totalNgAmount ?? 0) }} </h4>
            </div>
            <div class="col-md-3 col-xs-3">
                <h4>เสีย (NG)</h4>
                <h4>{{ $totalNgAmount ?? 0 }}</h4>

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
            <div class="panel-body d-flex justify-content-center">
                <!-- ฟอร์ม -->
                <form id="insertwipline1" class="form-inline d-flex align-items-center justify-content-center custom-form" 
      action="{{ route('insertWip', ['line' => $line, 'work_id' => $work_id]) }}" method="POST">
    @csrf

    <!-- Dropdown -->
    <div class="form-group mr-2">
    <select name="wip_empgroup_id" 
        id="wip_empgroup_id_2"
        class="margin-select selectpicker show-tick form-control move-up" 
        aria-required="true" 
        data-size="9" 
        data-dropup-auto="true" 
        data-live-search="true" 
        data-style="btn-info btn-md text-white" 
        data-width="fit" 
        data-container="body" 
        required>
    <option style="font-size:15px;" value="0">เลือกผู้คัด</option>
    @foreach ($empGroups as $group)
        <option style="font-size:15px;" 
                value="{{ $group->id }}" 
                data-emp1="{{ $group->emp1 }}" 
                data-emp2="{{ $group->emp2 }}">
            {{ $group->emp1 }} - {{ $group->emp2 }}
        </option>
    @endforeach
</select>


    </div>

    <!-- Input -->
    <div class="form-group mr-2">
        <input id="wip_barcode" 
               name="wip_barcode" 
               type="text" 
               class="form-control text-center" 
               placeholder="สแกนบาร์โค้ดยิงรับเข้า WIP" 
               minlength="24" 
               required 
               autofocus>
    </div>

    <!-- Hidden Input -->
    <input id="wp_working_id" name="wp_working_id" type="hidden" value="{{ $work_id }}">

    <!-- Button -->
    <div class="form-group">
        <button id="subline1" type="submit" class="btn" name="submit_fgcode" style="border: 1px solid #ccc; background-color: #fff; padding: 5px 8px; border-radius: 4px; width: 36px; height: 36px; display: flex; justify-content: center; align-items: center;">
            <i style="font-size:20px; color: #333;" class="fa fa-barcode"></i>
        </button>
    </div>
</form>

            </div>

            <!-- ตารางข้อมูล -->
            <table id="myTableCode" class="table table-bordered text-center mt-3">
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
        <a href="javascript:void(0);" 
   class="btn btn-black btn-xs open-edit-modal" 
   title="แก้ไขข้อมูล" 
   data-working-id="{{ $barcode->wip_working_id }}" 
   style="padding: 5px 10px; font-size: 12px; background-color: black; color: white; border-color: black;">
    <i class="fa fa-pencil-square-o"></i>
</a>

    </div>
</td>


                            <td>
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <a href="javascript:void(0);" class="btn btn-warning btn-xs open-ng-modal" title="แก้ไขข้อมูล" style="padding: 5px 10px; font-size: 12px; background-color: #f0ad4e; color: white; border-color: #f0ad4e;">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-info btn-xs open-noti-amount" title="แก้ไขจำนวน" style="padding: 5px 10px; font-size: 12px; background-color: #5bc0de; color: white; border-color: #5bc0de;">
                                        <i class="fa fa-sort-numeric-asc"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs open-delete-modal" title="ลบข้อมูล" style="padding: 5px 10px; font-size: 12px; background-color: #d9534f; color: white; border-color: #d9534f;" data-toggle="modal" data-target="#notideleteline1">
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

    <!-- ข้อมูลออก (FG) -->
    <div class="col-md-6">
        <div class="panel panel-gmt">
            <div class="panel-heading text-center" style="font-size:18px;">
                ข้อมูลออก (FG)
            </div>
            <div class="panel-body">
                <div class="text-center">
                    <button class="btn btn-warning" data-toggle="modal" data-target="#outfg">
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
                    @foreach ($brandsLots as $lot)
                        <tr>
                            <td></td>
                            <td>{{ $lot }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforeach

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


<script>
$(document).ready(function() {
    $('#wip_empgroup_id').on('change', function() {
        let selectedOption = $(this).find('option:selected');
        let emp1 = selectedOption.data('emp1');
        let emp2 = selectedOption.data('emp2');

        $('#emp1_old').val(emp1);
        $('#emp2_old').val(emp2);
    });
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
<script>
$(document).ready(function () {
    // เปิด Modal เมื่อคลิกปุ่ม
    $('.open-noti-amount').click(function () {
        $('#notiamount').fadeIn();  // เปิด Modal
    });
});
    
</script>                      

                      
              <div id="detail" class="tab-pane fade">
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
                     <a id="csvsumbtn" class="btn btn-success" name="button"><b>บันทึก CSV  <i class="fas fa-file-download"></i></b></a> 

                         <a href="" class="btn btn-success" name="button"><b>บันทึก CSV  <i class="fas fa-file-download"></i></b></a> 
                        
                       
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

<!-- Modal ลบข้อมูลบาร์โค้ด -->
<div class="modal fade" id="notideleteline1" tabindex="-1" role="dialog" aria-labelledby="DeleteBarcodeLine1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="DeleteBarcodeLine1">ลบข้อมูลบาร์โค้ด</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deletfieldline1" method="POST">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}

    <input type="hidden" name="workid" id="workid" value="{{ $work_id }}">
    <input type="hidden" name="id" id="delete_line1id" value="{{ $wipBarcodes->last()->wip_id ?? 0 }}">

    <div class="modal-body">
        <h4 style="color:red;">
            คุณต้องการลบข้อมูลบาร์โค้ด 
            <b style="color:red;"><u>{{ $wipBarcodes->last()->wip_barcode ?? '-' }}</u></b>
            หรือไม่?
        </h4>
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
   <form id="outfgform" 
      class="form-inline md-form form-sm mt-0 text-right" 
      enctype="multipart/form-data" 
      method="post" 
      action="{{ url('/outfgcode/' . $line . '/' . $work_id) }}">
    {{ csrf_field() }}

    <!-- เพิ่ม hidden inputs -->
    <input type="hidden" name="brd_working_id" value="{{ $work_id }}">
    <input type="hidden" name="brd_lot" value="{{ $lotgenerator }}">

    <div class="modal-body">
        <div class="panel panel-gmt">
            <div class="panel-heading text-center" style="font-size:18px;">ออกรหัส FG</div>
            <div class="panel-body" style="padding-top: 0px;padding-left: 0px;">
                <br>
                <div class="text-center">
                    <input 
                        class="form-control text-center" 
                        type="number" 
                        name="brd_amount" 
                        max="" 
                        value="{{ ($totalWipAmount ?? 0) - ($totalNgAmount ?? 0) }}" 
                        data-toggle="tooltip" 
                        title="กรอกจำนวน" 
                        placeholder="กรอกจำนวน" 
                        required>
                    <select name="brd_brandlist_id" 
                            id="brd_brandlist_id"
                            class="margin-select selectpicker show-tick form-control move-up" 
                            aria-required="true" 
                            data-size="9" 
                            data-dropup-auto="true" 
                            data-live-search="true" 
                            data-style="btn-info btn-md text-white" 
                            data-width="fit" 
                            data-container="body" 
                            required>
                        <option value="0">เลือกแบรนด์</option>
                        @foreach ($brandLists as $brand)
                            <option data-tokens="{{ $brand->bl_name }}" value="{{ $brand->bl_id }}">{{ $brand->bl_name }}</option>
                        @endforeach
                    </select>
                    &nbsp;&nbsp;
                    <select id="select_emp_id" 
                            name="brd_eg_id" 
                            class="margin-select selectpicker show-tick form-control" 
                            aria-required="true" 
                            data-size="9" 
                            data-dropup-auto="true" 
                            data-live-search="true" 
                            data-style="btn-warning btn-sm text-white" 
                            data-width="fit" 
                            data-container="body" 
                            required>
                        <option value="0">เลือกผู้คัด</option>
                        @foreach ($empGroups as $group)
                            <option style="font-size:15px;" 
                                    value="{{ $group->id }}" 
                                    data-emp1="{{ $group->emp1 }}" 
                                    data-emp2="{{ $group->emp2 }}">
                                {{ $group->emp1 }} - {{ $group->emp2 }}
                            </option>
                        @endforeach
                    </select>
                    &nbsp;&nbsp;
                    <input style="width:30%;" 
                           class="form-control text-center" 
                           name="brd_checker" 
                           type="text"  
                           placeholder="ผู้ตรวจสอบ" 
                           required>
                    <br>
                    <b>เลขหลังบอร์ด</b>
                    <input style="width:30%;" 
                           class="form-control text-center" 
                           name="brd_backboard_no" 
                           type="text" 
                           placeholder="เลขหลังบอร์ด">
                    <b>เพิ่มหมายเหตุ</b>
                    <input style="width:30%;" 
                           class="form-control text-center" 
                           name="brd_remark" 
                           type="text" 
                           placeholder="หมายเหตุ">
                </div>
            </div>
        </div>
        <br>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-success fas fa-save">  บันทึก </button>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
    </div>
</form>


        </div>
    </div>
</div>
<!-- Modal แก้ไขจำนวน -->
<div class="modal fade" id="notiamount" tabindex="-1" role="dialog" aria-labelledby="EditAmount" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="EditAmount"><b>แก้ไขจำนวน</b> <b id="showoutlot"></b></h3>
            </div>
            <form id="editamountform" method="POST" action="{{ route('editwipamg', ['id' => $wipBarcodes->last()->wip_id ?? 0]) }}" class="form-inline md-form form-sm mt-0 text-center">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    
    <input id="wipidamount" type="hidden" name="wip_id" value="{{ $wipBarcodes->last()->wip_id ?? 0 }}">
    
    <div class="modal-body">
        <!-- Barcode -->
        <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
            <h4 style="margin-right: 15px; width: 180px; text-align: right;"><b>Barcode :</b></h4>
            <span style="font-size: 17px;"><u id="showwipbarcode2">{{ $wipBarcodes->last()->wip_barcode ?? '-' }}</u></span>
        </div>

        <!-- จำนวนที่ต้องการแก้ไข -->
        <div style="display: flex; align-items: center; justify-content: center;">
            <b style="font-size: 17px; margin-right: 15px; width: 180px; text-align: right;">จำนวนที่ต้องการแก้ไข :</b>
            <input type="number" id="wipnewamount" class="text-center" name="wip_amount"
                   value="{{ $totalWipAmount ?? '0' }}"
                   style="width: 100px; text-align: center;">
            <input type="hidden" id="wipbarcodechange" class="text-center" name="wip_barcode" value="{{ $wipBarcodes->last()->wip_barcode ?? '-' }}">
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


<div class="modal fade" id="notiinputng" tabindex="-1" role="dialog" aria-labelledby="DeleteBarcodeLine1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="InputNg"><b>เพิ่มข้อมูลของเสีย</b></h3>
                <h4><b>Barcode : <i id="showbarcodewip">{{ $wipBarcodes->first()->wip_barcode ?? 'ไม่มีข้อมูล' }}</i></b></h4>
            </div>

            <div class="modal-body">
                <div class="panel-body">
                    <h4><b>สรุปรายการของเสีย</b></h4>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" id="listresultng">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:70%;">ของเสีย</th>
                                    <th class="text-center" style="width:20%;">จำนวน</th>
                                    <th class="text-center" style="width:10%;"><i class="fa fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody id="ng-data"></tbody>
                        </table>
                    </div>

                    <input class="inputng_id" type="hidden" name="inputng_id" id="inputng_id">

                    <div id="panel-ng" class="panel panel-gmt">
                        <div class="panel-heading text-center" style="font-size:18px;">เพิ่มข้อมูลของเสีย</div>
                        <div class="panel-body" style="padding-top: 0px;padding-left: 0px;">
                            <br>
                            <div class="text-center">
                                <a class="btn btn-default btn-sm" style="font-size:13px;" id="addl1a" href="#" role="button">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;เพิ่มของที่เสีย
                                </a>
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
                                                        @foreach($listNgAll as $ng)
                                                            <option style="font-size:16px;" data-tokens="{{ $ng->lng_name }}" value="{{ $ng->lng_id }}">
                                                                {{ $ng->lng_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-left">
                                                    <input type="number" value="" name="amg_amount[]" placeholder="จำนวน" required />
                                                    <input type="hidden" value="{{ $wipBarcodes->first()->wip_id ?? '' }}" name="amg_wip_id[]" id="inputng_idchild">
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="text-right">
                                            <button id="removelistng" class="btn btn-warning btn-sm" type="button" name="button">
                                                <span class="fas fa-redo-alt"></span>&nbsp;ทำใหม่
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
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
                <h3 class="modal-title" id="EditEnpWip"><b>แก้ไขข้อมูลผู้คัด</b></h3>
                <h4><b>Barcode : <u><i id="empwipbarcode">{{ $wipBarcodes->first()->wip_barcode ?? 'ไม่มีข้อมูล' }}</i></u></b></h4>
            </div>
            <div class="container-fluid">
            <form id="editempwipform" action="{{ route('update.empgroup', ['id' => 0]) }}" method="POST">
            @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="text-center">
                            <!-- Select ผู้คัด -->
                            <select name="wip_empgroup_id" 
        id="wip_empgroup_id_1"
        class="margin-select selectpicker show-tick form-control move-up" 
        aria-required="true" 
        data-size="9" 
        data-dropup-auto="true" 
        data-live-search="true" 
        data-style="btn-info btn-md text-white" 
        data-width="fit" 
        data-container="body" 
        required>
    <option style="font-size:15px;" value="0">เลือกผู้คัด</option>
    @foreach ($empGroups as $group)
        <option style="font-size:15px;" 
                value="{{ $group->id }}" 
                data-emp1="{{ $group->emp1 }}" 
                data-emp2="{{ $group->emp2 }}">
            {{ $group->emp1 }} - {{ $group->emp2 }}
        </option>
    @endforeach
</select>



                        </div>
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="id" id="empwipid">
                        <input type="hidden" name="wip_empgroup_id_old" id="empgropidwip">
                        <input type="hidden" name="emp1_old" id="emp1_old">
                        <input type="hidden" name="emp2_old" id="emp2_old">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-success" id="save-btn">บันทึก</button>
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

        var inputngid = '<input type="hidden" value="" name="amg_wip_id[]" id="inputng_idchild">';
        var workid = '';
        var line = '';
        var enddate = "";
        var group = '';
        var hiddeninput = '<input type="hidden" name="eg_line[]" value=""><input type="hidden" name="eg_division[]" value="QC"><input type="hidden" name="eg_emp_id_1[]" value=""><input type="hidden" name="eg_emp_id_2[]" value=""><input type="hidden" name="eg_status[]" value="1">';
        var addscanwipemp = '<select name="wip_empgroup_id" class="margin-select selectpicker show-tick form-control" aria-required="true" data-size="9" data-dropup-auto="true" data-live-search="true" data-style="btn-info btn-sm text-white" data-width="fit" data-container="body" required><option style="font-size:15px;" value="0">เลือกผู้คัด</option></select>';
            var inputbarcode = '<input type="text" id="pe_user_emp" name="pe_working_id" value=""><input type="text" id="pe_type_code" name="pe_type_code"><input type="text" name="wip_amount" id="wip_amount" class="form-control text-center" data-toggle="tooltip" title="จำนวนที่คัดแล้ว" placeholder="จำนวนที่คัดแล้ว" required/><input type="text" name="wip_working_id" value=""><input id="wip_barcode" name="wip_barcode" type="text" class="form-control text-center" data-toggle="tooltip" title="สแกนบาร์โค้ด" style="width:40%;" placeholder="สแกนบาร์โค้ดยิงรับเข้า WIP" autofocus><input type="text" name="wp_working_id" value=""><input type="text" id="wp_date_product" name="wp_date_product"><br>';
        </script>
        @endsection


 