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
        $('#brd_brandlist_id_01').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
            // ดึงค่าที่เลือกใหม่
            const selectedValue = $(this).find('option').eq(clickedIndex).val();

            // ลบ "ติ๊กถูก" อันเก่าทั้งหมด
            $('#brd_brandlist_id_01 option').prop('selected', false);

            // ทำให้เฉพาะตัวที่ถูกเลือกใหม่ "ติ๊กถูก"
            $('#brd_brandlist_id_01 option[value="' + selectedValue + '"]').prop('selected', true);

            // รีเฟรช selectpicker เพื่ออัปเดตการแสดงผล
            $(this).selectpicker('refresh');
        });
    });
</script>


        </script>
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

    // ✅ ใช้ Event Delegation เพื่อรองรับปุ่มที่ถูกเพิ่มเข้าไปใน DOM
    $(document).on("click", "#addl1a", function (event) {
        event.preventDefault(); // ✅ ป้องกันการรีโหลดหน้า

        // ✅ ดึง WIP ID จาก `<input type="hidden" id="selectedWipId">`
        let wipID = $("#selectedWipId").val() || "";

        // ✅ แสดงค่า WIP ID ใน Console
        console.log("📌 WIP ID ที่ใช้เพิ่มแถว:", wipID);

        let selectOptions = `@foreach($listNgAll as $ng)
                                <option style="font-size:16px;" data-tokens="{{ $ng->lng_name }}" value="{{ $ng->lng_id }}">
                                    {{ $ng->lng_name }}
                                </option>
                            @endforeach`;

        let newRow = `
            <tr data-wip-id="${wipID}">
                <td class="text-left">
                    <select name="amg_ng_id[]" class="btn btn-info btn-sm" style="font-size:16px;">
                        <option value="">เลือกของเสีย</option>
                        ${selectOptions}
                    </select>
                </td>
                <td class="text-left">
                    <input type="number" name="amg_amount[]" placeholder="จำนวน" required />
                    <input type="hidden" value="${wipID}" name="amg_wip_id[]" class="inputng_idchild">
                </td>
            </tr>`;

        $("#wipline1awaste").append(newRow);
    });

    // ✅ ตรวจสอบข้อมูลก่อน Submit Form
    $('#inputngform').on('submit', function (e) {
        e.preventDefault();
        let isValid = true;

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
                text: 'กรุณากรอกข้อมูลให้ครบถ้วนก่อนบันทึก',
            });
            return;
        }

        // ✅ ส่งข้อมูลผ่าน AJAX
        $.ajax({
            type: "POST",
            url: "{{ route('addng') }}",
            data: $(this).serialize(),
            success: function () {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    text: 'โปรดรีเฟรชหน้าหากไม่มีการเปลี่ยนแปลง',
                    timer: 1500
                });
                setTimeout(() => location.reload(), 1200);
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    text: 'โปรดรีเฟรชหน้าแล้วลองอีกครั้ง',
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



<script>
$(document).ready(function () {
    // เปิด Modal และตั้งค่า
    $(document).on('click', '.open-edit-modal', function () {
        const workingId = $(this).data('working-id');
        $('#empwipid').val(workingId);
        $('#editempwipform').attr('action', `/update-empgroup/${workingId}`);
        $('#editempwip').modal('show');
    });

    // ปิด Modal และรีเซ็ตค่า
    $('#editempwip').on('hidden.bs.modal', function () {
        $('#wip_empgroup_id_1 option').prop('selected', false); // ล้างตัวเลือกทั้งหมด
        $('#wip_empgroup_id_1').val('0'); // ตั้งค่าเริ่มต้น
        $('#wip_empgroup_id_1').selectpicker('refresh'); // รีเฟรช Dropdown
    });

    // ส่งฟอร์มด้วย AJAX
    $('#editempwipform').on('submit', function (e) {
        e.preventDefault();

        const form = $(this)[0];
        const formData = new FormData(form); // ใช้ FormData เพื่อให้รองรับ `_method=PUT`
        const actionUrl = $(this).attr('action');

        console.log("📤 ส่งข้อมูลไปที่:", actionUrl);
        console.log("📌 ข้อมูลที่ส่ง:", Object.fromEntries(formData));

        $.ajax({
            url: actionUrl,
            type: 'POST', // ใช้ POST + `_method=PUT`
            data: formData,
            processData: false, // สำคัญ: ปิด processData เพื่อให้ส่ง `FormData` ได้ถูกต้อง
            contentType: false, // สำคัญ: ปิด contentType
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
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
$(document).ready(function () {
    $('.editBrandBtn').on('click', function () {
        let brd_id = $(this).data('id'); // ✅ ดึงค่า `brd_id`
        let bl_id = $(this).data('bl-id'); // ✅ ดึงค่า `bl_id`
        let brd_lot = $(this).data('brd-lot'); // ✅ ดึงค่า `brd_lot`

        console.log("📝 กดปุ่มแก้ไข -> brd_id:", brd_id, "bl_id:", bl_id, "brd_lot:", brd_lot);

        // ✅ ใส่ค่า `brd_id`, `brd_lot` ลงในฟอร์ม
        $('#editbrandid').val(brd_id);
        $('#editbrandidlot').val(brd_lot); // ✅ ตั้งค่า brd_lot ให้ input
        $('#brd_brandlist_id_03').val(bl_id); // ✅ ตั้งค่า `bl_id` ให้ select
        $('#lot_display').text(brd_lot); // ✅ แสดง LOT No. ใน modal

        // ✅ ตั้งค่า Form Action ให้ส่ง `brd_id`
        let actionUrl = `/wip/editbrand/${brd_id}`;
        $('#editbrandform').attr('action', actionUrl);

        console.log("✅ Form Action:", actionUrl);
    });

    // ✅ เมื่อกด "บันทึก"
    $('#editbrandform').on('submit', function (e) {
        e.preventDefault();

        var actionUrl = $(this).attr('action');
        var formData = $(this).serialize();

        console.log('🚀 Submitting to URL:', actionUrl);
        console.log('📄 Form Data:', formData);

        $.ajax({
            type: "PUT",
            url: actionUrl,
            data: formData,
            beforeSend: function () {
                Swal.fire({
                    title: 'กำลังบันทึก...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    showConfirmButton: false,
                    timer: 1500
                });

                setTimeout(() => location.reload(), 1350);
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    text: 'กรุณาลองใหม่อีกครั้ง'
                });
            }
        });
    });
});
</script>


<script>
$(document).ready(function () {
    $('#outfgform').on('submit', function (e) {
        e.preventDefault(); // ❌ ป้องกันการ Submit ปกติ

        let actionUrl = $(this).attr('action'); // ดึง URL จาก form
        let formData = $(this).serialize(); // ดึงค่าจาก form

        console.log("🚀 ส่งคำขอไปยัง:", actionUrl);
        console.log("📄 Form Data:", formData);

        // ✅ แสดง Loader ระหว่างรอการตอบกลับจากเซิร์ฟเวอร์
        Swal.fire({
            title: 'กำลังบันทึกข้อมูล...',
            text: 'โปรดรอสักครู่',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // ✅ ส่งคำขอ AJAX
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1600
                });

                // ✅ สร้าง URL ของ Route `/production/tagfg/{line}/{work_id}/{brd_id}`
                let tagfgUrl = `/production/tagfg/${response.line}/${response.work_id}/${response.brd_id}`;

                // ✅ เปิดหน้าป๊อปอัพ
                window.open(tagfgUrl, "_blank", "width=800,height=600");

                setTimeout(() => location.reload(), 1600); // ✅ รีโหลดหลังจาก 1.6 วินาที
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">โปรดตรวจสอบและลองใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                });
            }
        });
    });
});
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // ดึง path จาก URL (ตัวอย่าง: "/production/datawip/L2/60081")
        let urlParts = window.location.pathname.split("/");

        // ดึงค่า `line` (ส่วนที่ 3 จากหลัง) และ `work_id` (ส่วนสุดท้าย)
        let line = urlParts[urlParts.length - 2];  // เช่น "L2"
        let workId = urlParts[urlParts.length - 1]; // เช่น "60081"

        document.querySelectorAll('.printBtn').forEach(button => {
            button.addEventListener('click', function () {
                let brdId = this.getAttribute('data-id');

                // สร้าง URL ไปที่ "/production/tagfg/{line}/{work_id}/{brd_id}"
                let url = `/production/tagfg/${line}/${workId}/${brdId}`;

                // เปิดหน้าใหม่แบบ Pop-up Window
                window.open(url, "_blank", "width=900,height=800,top=100,left=200,scrollbars=yes");
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // ดึงค่า brd_id เมื่อกดปุ่มลบ
        document.querySelectorAll('.deleteBrandBtn').forEach(button => {
            button.addEventListener('click', function () {
                let brdId = this.getAttribute('data-id');
                let lotNo = this.getAttribute('data-lot');

                console.log("🗑️ กำลังลบ BRD ID:", brdId);
                console.log("🎯 Lot No ที่จะแสดง:", lotNo);

                // อัปเดตค่าใน Modal
                document.getElementById("showoutfg").innerText = lotNo;
                document.getElementById("delete_outfgid").value = brdId; // เก็บ brd_id
            });
        });

        // ส่งคำขอลบเมื่อกดปุ่ม "ลบบาร์โค้ด"
        document.getElementById("confirmDelete").addEventListener("click", function () {
            let brdId = document.getElementById("delete_outfgid").value;

            fetch(`/wip/deletebrand/${brdId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Content-Type": "application/json"
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ลบข้อมูลแล้ว',
                        html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(() => location.reload(), 1200);
                } else {
                    throw new Error(data.error || "ไม่สามารถลบข้อมูลได้");
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'ลบข้อมูลไม่สำเร็จ',
                    html: `<small style="color:red;">${error.message}</small>`,
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#forminputend").submit(function (e) {
    e.preventDefault();

    var form = $(this);
    var url = form.attr("action");
    var formData = new FormData(this);

    let requiredFields = ["ws_input_amount", "ws_output_amount", "ws_holding_amount", "ws_ng_amount", "ws_working_id", "wh_working_id", "wh_lot"];
    let isValid = true;
    let missingFields = [];

    console.log("📌 ตรวจสอบค่าก่อนส่ง:");

    requiredFields.forEach(field => {
        let value = formData.get(field);
        console.log(`✅ ${field}:`, value);

        // ✅ ปรับเงื่อนไขให้ค่า 0 สามารถบันทึกได้
        if (value === null || value.trim() === "" || value === "null") { 
            isValid = false;
            missingFields.push(field);
        }
    });

    if (!isValid) {
        console.error("❌ ข้อมูลที่ขาด:", missingFields);
        Swal.fire({
            title: "ข้อมูลไม่ครบถ้วน!",
            text: "กรุณากรอกข้อมูลให้ครบทุกช่องก่อนบันทึก",
            icon: "warning",
            confirmButtonText: "ตกลง"
        });
        return;
    }

    // ✅ ส่ง AJAX
    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            Swal.fire({
                title: "บันทึกสำเร็จ!",
                text: response.message || "ข้อมูลถูกบันทึกเรียบร้อย",
                icon: "success",
                confirmButtonText: "ตกลง"
            }).then(() => {
                if (response.redirect_url) {
                    window.open(response.redirect_url, "_blank", "width=800,height=600");
                }
                location.reload();
            });
        },
        error: function (xhr) {
            let errorMessage = "ไม่สามารถบันทึกข้อมูลได้";
            if (xhr.responseJSON) {
                errorMessage = xhr.responseJSON.message || errorMessage;
                console.error("❌ เกิดข้อผิดพลาด:", xhr.responseJSON);
            }
            
            Swal.fire({
                title: "เกิดข้อผิดพลาด!",
                text: errorMessage,
                icon: "error",
                confirmButtonText: "ตกลง"
            });
        }
    });
});
});

</script>
<script>
    function openTagFgPopup(brd_id) {
        // ✅ ดึง URL Path ของหน้าเว็บ
        let path = window.location.pathname;

        // ✅ ใช้ Regular Expression เพื่อดึงค่า line และ work_id จาก URL
        let match = path.match(/\/production\/datawip\/L(\d+)\/(\d+)/);

        if (!match) {
            console.error('❌ ไม่พบค่า line หรือ work_id ใน URL');
            alert("ไม่พบค่าที่จำเป็น!");
            return;
        }

        // ✅ แยกค่าที่ดึงมา
        let line = "L" + match[1]; // เติม L ด้านหน้าเสมอ
        let work_id = match[2]; // ดึงค่า work_id

        // ✅ ตรวจสอบค่าก่อนส่งไปที่ Route
        if (!brd_id) {
            console.error('❌ ไม่พบค่า brd_id');
            alert("ไม่พบค่า brd_id!");
            return;
        }

        // ✅ แสดงค่าทั้งหมดใน Console เพื่อตรวจสอบ
        console.log("✅ brd_id:", brd_id);
        console.log("✅ work_id:", work_id);
        console.log("✅ line:", line);

        // ✅ สร้าง URL สำหรับ Route `/production/tagfg/{line}/{work_id}/{brd_id}`
        let popupUrl = `/production/tagfg/${line}/${work_id}/${brd_id}`;

        // ✅ เปิด Popup Window
        window.open(popupUrl, 'tagfgPopup', 'width=1000,height=600');
    }
</script>

<script>
    $(document).ready(function () {
        $('#wip_empgroup_id_2').on('changed.bs.select', function () {
            $(this).selectpicker('refresh'); // รีเฟรช dropdown
            $('.bootstrap-select .dropdown-toggle').dropdown('toggle'); // ปิด dropdown อัตโนมัติหลังเลือก
        });

        // ปิด dropdown เมื่อเลือกค่า
        $('#wip_empgroup_id_2').on('change', function () {
            $('.bootstrap-select .dropdown-toggle').dropdown('toggle'); // ปิด dropdown
        });

        // ปิด dropdown เมื่อคลิกที่อื่น
        $(document).click(function (event) {
            if (!$(event.target).closest('.bootstrap-select').length) {
                $('.bootstrap-select .dropdown-menu').removeClass('show');
            }
        });
    });
</script>


<script>
$(document).ready(function() {
    $("#searchCode tr").each(function() {
        let row = $(this);
        let wipId = row.data("wip-id");

        if (wipId) {
            $.ajax({
                url: `/get-amount-ng/${wipId}`,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    console.log(`✅ สำเร็จ: ดึงข้อมูลสำเร็จสำหรับ WIP ID: ${wipId}`, response);

                    if (response.status === "success" && response.amg_amount !== null) {
                        row.find(".amg-amount").text(response.amg_amount);
                    } else {
                        row.find(".amg-amount").text("ไม่พบข้อมูล").css("color", "red");
                        console.warn(`⚠️ คำเตือน: ไม่พบข้อมูล amg_amount สำหรับ WIP ID: ${wipId}`);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(`❌ เกิดข้อผิดพลาดกับ WIP ID: ${wipId}`, {
                        status: status,
                        error: error,
                        responseText: xhr.responseText
                    });
                    row.find(".amg-amount").text("ไม่มีของเสีย").css("color", "red");
                }
            });
        }
    });
});
</script>
<script>
$(document).ready(function () {
    // ✅ เมื่อเปิดฟอร์ม ให้ใช้ WIP ID ล่าสุด
    $("#notiinputng").on("shown.bs.modal", function () {
        let wipID = $("#inputng_id").val() || "";

        // ✅ คงค่า WIP ID และ Barcode ไว้
        $("#selectedWipId").val(wipID);

        console.log("🔄 อัปเดตค่า WIP ID ในฟอร์ม:", wipID);

        // ✅ ใช้ AJAX ดึงข้อมูล WIP Barcode ตาม WIP ID
        fetch(`/get-wip-barcode/${wipID}`)
            .then(response => response.json())
            .then(data => {
                let barcode = data.barcode || "ไม่มีข้อมูล";

                console.log("📌 WIP ID หลังเปิดฟอร์ม:", wipID);
                console.log("📌 Barcode ที่ดึงมา:", barcode);

                // ✅ คงค่า Barcode ไว้
                $("#showbarcodewip").text(barcode);
                $("#selectedWipBarcode").val(barcode);
            })
            .catch(error => console.error("❌ Error fetching barcode:", error));
    });

    // ✅ เมื่อกดปุ่มเลือก WIP ID ใหม่
    $(document).on("click", ".open-ng-modal", function (event) {
        event.preventDefault();
        let wipID = $(this).closest("tr").data("wip-id-ng");

        // ✅ อัปเดตค่า WIP ID ลงใน `<input>` ที่ซ่อนอยู่
        $("#selectedWipId").val(wipID);

        console.log("🔄 อัปเดตค่า WIP ID ใหม่ในฟอร์ม:", wipID);

        // ✅ ใช้ AJAX ดึงข้อมูล WIP Barcode ตาม WIP ID
        fetch(`/get-wip-barcode/${wipID}`)
            .then(response => response.json())
            .then(data => {
                let barcode = data.barcode || "ไม่มีข้อมูล";

                console.log("📌 WIP ID ที่เลือก:", wipID);
                console.log("📌 Barcode ที่เลือก:", barcode);

                $("#inputng_id").val(wipID);
                $("#showbarcodewip").text(barcode);
                $("#selectedWipBarcode").val(barcode);

                $("#notiinputng").modal("show");
            })
            .catch(error => console.error("❌ Error fetching barcode:", error));
    });

    // ✅ เพิ่ม Event Listener ให้ `<input>` แสดงค่าที่เปลี่ยนใน Console
    $("#selectedWipId").on("change", function () {
        console.log("✅ ค่า WIP ID ในฟอร์มเปลี่ยนเป็น:", $(this).val());
    });

});



</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function() {
            let wipId = this.getAttribute("data-wip-id");

            // อัปเดต WIP ID ที่แสดงผล
            document.getElementById("showWipId").innerText = wipId;

            // ✅ Fetch ข้อมูล WIP Barcode ตาม wip_working_id
            fetch(`/get-wip-barcode/${wipId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("showbarcodewip").innerText = data.barcode ?? "ไม่มีข้อมูล";
                })
                .catch(error => console.error("Error fetching barcode:", error));
        });
    });
});
</script>
<script>
    $(document).ready(function() {
        $(".go-back").click(function(event) {
            event.preventDefault(); // ป้องกันปุ่มทำงานเป็นลิงก์ปกติ

            // ดึง URL ปัจจุบัน
            var currentUrl = window.location.pathname; // ตัวอย่าง: /production/datawip/L5/10083

            // ใช้ Regex ดึงค่า `line` จาก URL
            var match = currentUrl.match(/\/production\/datawip\/L(\d+)\/\d+/);
            if (match) {
                var line = match[1]; // ดึงค่า line ออกมา

                // Redirect ไปที่ `/manufacture/L{line}`
                var targetUrl = "/manufacture/L" + line;
                window.location.href = targetUrl;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถดึงค่า Line ได้'
                });
            }
        });
    });
</script>
<div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                <a href="#" class="btn-custom go-back">
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
@if (isset($workprocess->status) && trim($workprocess->status) === 'จบการทำงาน' && !empty($wwEndDate))
    <h4><b>วันที่จบการทำงาน : {{ date("d-m-Y H:i", strtotime($wwEndDate)) }}</b></h4>
@endif

<h4><b>สถานะ :</b> 
    <b style="color: {{ trim($workprocess->status ?? '') == 'จบการทำงาน' ? 'red' : 'green' }};">
        {{ trim($workprocess->status ?? 'ไม่มีข้อมูล') }}
    </b>
</h4>
@if ($wipBarcodes->count() > 0 && $productTypes->count() > 0)
<h4><b>ชนิดสินค้า :</b> <b>{{ $peTypeName ?? 'ไม่พบข้อมูล' }}</b></h4>
@endif


            </div>
            @if (isset($workprocess->status) && trim($workprocess->status) === 'กำลังคัด')
    <h3>
        <p class="text-danger">
            ต้องรอการตรวจสอบรับเข้าคลังสินค้าให้หมด จึงจะสามารถจบทำงานได้
        </p>
    </h3>
    <br>
@endif

    @if (isset($workprocess->status) && trim($workprocess->status) === 'จบการทำงาน')
    <h4><b>สรุปข้อมูล</b></h4>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">จำนวนแผ่นยิงรับเข้า</th>
                    <th class="text-center">จำนวนแผ่นออก</th>
                    <th class="text-center">จำนวนคงค้าง</th>
                    <th class="text-center">จำนวนแผ่นเสีย</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td class="text-center info">{{ $wipSummary->ws_input_amount ?? 0 }}</td>
<td class="text-center info">{{ $wipSummary->ws_output_amount ?? 0 }}</td>
<td class="text-center info">{{ $wipSummary->ws_holding_amount ?? 0 }}</td>
<td class="text-center info">{{ $wipSummary->ws_ng_amount ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>

    <h4><b>คงค้าง(HD)</b></h4>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">บาร์โค้ด</th>
                    <th class="text-center">Lot HD</th>
                    <th class="text-center"><i class="fa fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
            <tr>
    @foreach ($wipHoldings as $holding)
        <td class="text-center warning">{{ $loop->iteration }}</td> {{-- ✅ ใช้ $loop->iteration แทน $index --}}
        <td class="text-center warning">{{ $holding->wh_barcode }}</td>
        <td class="text-center warning">{{ $holding->wh_lot }}</td>
        <td class="text-center warning">
            <a href="#" class="btn btn-success btn-sm fa fa-print" 
               onclick="openPopup()"
               data-toggle="tooltip" title="พิมพ์" 
               style="font-size:15px;">
            </a>
        </td>
    </tr>
    @endforeach


            </tbody>
        </table>
    </div>
    <hr>

    <h4><b>บาร์โค้ดยิงรับเข้า</b></h4>
    <table id="myTableCode" class="table table-hover bg-white text-center">
    <thead>
    <tr>
        <th>#</th>
        <th>บาร์โค้ด</th>
        <th>จำนวน</th>
        <th>เสีย</th>
    </tr>
</thead>
<tbody id="searchCode">
    @forelse ($wipBarcodes as $index => $barcode)
        <tr data-wip-id="{{ $barcode->wip_id }}">
            <td class="success">{{ $index + 1 }}</td>
            <td class="wipline1code success">{{ $barcode->wip_barcode }}</td>
            <td class="success">{{ $barcode->wip_amount }}</td>
            <td class="success amg-amount">
                กำลังโหลด...
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center">ไม่มีข้อมูล</td>
        </tr>
    @endforelse
</tbody>




        <tfoot>
            <tr>
                <th>#</th>
                <th>บาร์โค้ด</th>
                <th>จำนวน</th>
                <th>เสีย</th>
            </tr>
        </tfoot>
    </table>

                        <hr>
                        <h4><b>บาร์โค้ดออก FG</b></h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">บาร์โค้ด</th>
                                        <th class="text-center">Lot FG</th>
                                        <th class="text-center"><i class="fa fa-cog"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
    @foreach ($brandsLots as $index => $lot)
    <tr>
        <td class="text-center danger">{{ $index + 1 }}</td>
        <td class="text-center danger">
            @if ($lot && $lot->brd_brandlist_id !== null && $brandList && $peTypeCode && $lot->brd_amount !== null && $workdetail->ww_line < 100)
                BX{{ str_pad($lot->brd_brandlist_id, 2, '0', STR_PAD_LEFT) }}-{{ $peTypeCode }}{{ $workdetail->ww_line }}++++++++000{{ $lot->brd_amount }}
            @elseif ($lot && $lot->brd_brandlist_id !== null && $brandList && $peTypeCode && $lot->brd_amount !== null)
                {{ str_pad($lot->brd_brandlist_id, 2, '0', STR_PAD_LEFT) }}-{{ $peTypeCode }}{{ $workdetail->ww_line }}++++++++{{ $lot->brd_amount }}
            @else
                N/A
            @endif
        </td>
        <td class="text-center danger">{{ $lot->brd_lot ?? 'N/A' }}</td>
        <td class="text-center danger">
            <a href="#" 
                onclick="openTagFgPopup({{ $lot->brd_id ?? 'null' }})" 
                class="btn btn-success btn-sm fa fa-print" 
                data-toggle="tooltip" 
                title="พิมพ์" 
                style="font-size:15px;">
            </a>
        </td>
    </tr>
    @endforeach
</tbody>

                            </table>
                        </div>
@endif


            @if (isset($workprocess->status) && $workprocess->status == 'กำลังคัด')

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
                <h4>{{ $brdAmount ?? 0}}</h4>
            </div>
            <div class="col-md-3 col-xs-3">
                <h4>คงค้าง (HD)</h4>
                <h4>{{ ($totalWipAmount ?? 0) - ($totalNgAmount ?? 0) - ($brdAmount ?? 0) }}</h4>
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
        <tr data-wip-id-ng="{{ $barcode->wip_id }}">
            <td>{{ $index + 1 }}</td>
            <td class="barcodeValue" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
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
                    <a href="javascript:void(0);" 
                        class="btn btn-warning btn-xs open-ng-modal" 
                        title="แก้ไขข้อมูล" 
                        style="padding: 5px 10px; font-size: 12px; background-color: #f0ad4e; color: white; border-color: #f0ad4e;">
                        <i class="fa fa-pencil-square-o"></i>
                    </a>
                    <a href="javascript:void(0);" 
                        class="btn btn-info btn-xs open-noti-amount" 
                        title="แก้ไขจำนวน" 
                        style="padding: 5px 10px; font-size: 12px; background-color: #5bc0de; color: white; border-color: #5bc0de;">
                        <i class="fa fa-sort-numeric-asc"></i>
                    </a>
                    <a href="javascript:void(0);" 
                        class="btn btn-danger btn-xs open-delete-modal" 
                        title="ลบข้อมูล" 
                        style="padding: 5px 10px; font-size: 12px; background-color: #d9534f; color: white; border-color: #d9534f;" 
                        data-toggle="modal" data-target="#notideleteline1">
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
    @foreach ($brandsLots as $index => $lot)
        <tr>
            <td>{{ $index + 1 }}</td>
            
            <!-- แสดง brd_id -->
            <td>
                @if (!empty($lot->brd_lot))
                    ✅
                @endif
                {{ $lot->brd_lot }} </span>
            </td>

            <td>{{ $lot->brd_amount }}</td>

            <td>
    @if ($lot && $lot->brd_brandlist_id !== null && $brandList && $peTypeCode && $lot->brd_amount !== null && $workdetail->ww_line < 100)
        BX{{ str_pad($lot->brd_brandlist_id, 2, '0', STR_PAD_LEFT) }}-{{ $peTypeCode }}{{ $workdetail->ww_line }}++++++++000{{ $lot->brd_amount }}
    @elseif ($lot && $lot->brd_brandlist_id !== null && $brandList && $peTypeCode && $lot->brd_amount !== null)
        {{ str_pad($lot->brd_brandlist_id, 2, '0', STR_PAD_LEFT) }}-{{ $peTypeCode }}{{ $workdetail->ww_line }}++++++++{{ $lot->brd_amount }}
    @else
        N/A
    @endif
</td>


            <td>
                <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                <button class="editBrandBtn"
        data-toggle="modal"
        data-target="#notieditbrand"
        data-id="{{ $lot->brd_id }}"
        data-bl-id="{{ $lot->brd_brandlist_id }}"
        data-brd-lot="{{ $lot->brd_lot }}">
    <i class="fa fa-edit" style="font-size: 20px; color: #000;"></i>
</button>






<button class="printBtn" 
        data-id="{{ $lot->brd_id }}" 
        style="border: none; background-color: transparent; cursor: pointer;" 
        title="Print">
    <i class="fa fa-print" style="font-size: 20px; color: green;"></i>
</button>



                    <!-- ปุ่มลบ -->
                    <button style="border: none; background-color: transparent; cursor: pointer;"
        title="Delete" 
        data-toggle="modal" 
        data-target="#notideleteoutfg"
        data-id="{{ $lot->brd_id }}"
        data-lot="{{ $lot->brd_lot }}"
        class="deleteBrandBtn">
    <i class="fa fa-trash" style="font-size: 20px; color: red;"></i>
</button>


                </div>
            </td>
        </tr>
    @endforeach
</tbody>

                </table>
                
            </div>
        </div>
        
    </div>
</div>

   



    <h3>
        <p class="text-danger">
            ต้องรอการตรวจสอบรับเข้าคลังสินค้าให้หมด จึงจะสามารถจบทำงานได้
        </p>
    </h3>
    <br>
       
        <!--ปิดปุ่มgขียว 27/05/21  -->
        
        
        
        <div class="text-center">
            <a class="btn btn-success" data-target="#inputend" data-toggle="modal" name="button" ><b>บันทึกจบ (END) <i class="fas fa-file-export"></i></b></a>
        </div>

        @endif

  

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
<script>       
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function() {
            let wipId = this.getAttribute("data-wip-id");

            console.log("📌 WIP ID ที่เลือก:", wipId); // ตรวจสอบว่าค่า WIP ID ถูกต้อง

            fetch(`/get-wip-barcode/${wipId}`)
                .then(response => response.json())
                .then(data => {
                    console.log("📌 Barcode ที่ได้รับจาก API:", data); // Debug Response API
                    document.getElementById("showbarcodewip").innerText = data.barcode ?? "ไม่มีข้อมูล";
                })
                .catch(error => console.error("❌ Error fetching barcode:", error));
        });
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
                <h4><b>Barcode : <i id="showbarcodewip">ไม่มีข้อมูล</i></b></h4>   
</i></b></h4>
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

    <tr data-wip-id-ng="{{ $wipBarcodes->first()->wip_id ?? '' }}">
        <td class="text-left" style="width: 50%;">
        <select name="amg_ng_id[]" class="btn btn-info btn-sm" data-live-search="true" style="font-size:16px; width: 100%;">
        <option value="">เลือกของเสีย</option>
                @foreach($listNgAll as $ng)
                    <option data-tokens="{{ $ng->lng_name }}" value="{{ $ng->lng_id }}">
                        {{ $ng->lng_name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td class="text-center" style="width: 50%;">
        <input type="hidden" name="amg_wip_id[]" id="selectedWipId" class="inputng_idchild">
        <input type="number" name="amg_amount[]" placeholder="จำนวน" required class="form-control" style="font-size:16px;" />
        </td>
    </tr>
</table>


            <div class="text-right">
                <button id="removelistng" class="btn btn-warning btn-sm" type="button">
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
                <form id="forminputend" class="md-form text-center" enctype="multipart/form-data" method="POST" action="{{ route('endprocess', ['line' => $line, 'work_id' => $work_id]) }}">
    @csrf <!-- ป้องกัน CSRF -->
    <div class="modal-body">
        <div class="panel panel-gmt">
            <div class="panel-heading text-center" style="font-size:18px;">สรุปรายการ</div>
            <div class="panel-body" style="padding-top: 0px; padding-left: 0px;">
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
            <div class="panel-body" style="padding-top: 0px; padding-left: 0px;">
                <div class="col-md-3 col-xs-3">
                    <h4 class="text-center">{{ $totalWipAmount ?? '0' }}</h4>
                    <input class="form-control text-center" type="hidden" name="ws_input_amount" value="{{ $totalWipAmount ?? '0' }}" readonly>
                </div>
                <div class="col-md-3 col-xs-3">
                    <h4 class="text-center">{{ $brdAmount ?? 0}}</h4>
                    <input class="form-control text-center" type="hidden" name="ws_output_amount" value="{{ $brdAmount ?? 0 }}" readonly>
                </div>
                <div class="col-md-3 col-xs-3">
                    <h4 class="text-center">{{ ($totalWipAmount ?? 0) - ($totalNgAmount ?? 0) - ($brdAmount ?? 0) }}</h4>
                    <input class="form-control text-center" type="hidden" name="ws_holding_amount" value="{{ ($totalWipAmount ?? 0) - ($totalNgAmount ?? 0) - ($brdAmount ?? 0) }}" readonly>
                </div>
                <div class="col-md-3 col-xs-3">
                    <h4 class="text-center">{{ $totalNgAmount ?? 0 }}</h4>
                    <input class="form-control text-center" type="hidden" name="ws_ng_amount" value="{{ $totalNgAmount ?? 0 }}" readonly>
                    <input type="hidden" name="ws_working_id" value="{{ $work_id }}" readonly>
                    <input type="hidden" name="wh_working_id" value="{{ $work_id }}" readonly>
                </div>
                <input type="hidden" name="wh_barcode" value="{{ $hdbarcode }}" readonly>
                <input type="hidden" name="wh_lot" value="{{ $lothdgenerator }}" readonly>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
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
            <form id="deleteoutfg" onsubmit="return false;">
    <div class="modal-body">
        @csrf
        <input type="hidden" name="id" id="delete_outfgid"> <!-- เก็บ brd_id -->
        <h4 class="text-center" style="color:red;">
            <p>คุณต้องการลบข้อมูลบาร์โค้ด <b>Lot No :</b> <b id="showoutfg"></b> หรือไม่</p>
        </h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
        <button type="submit" class="btn btn-danger" id="confirmDelete">ลบบาร์โค้ด</button>
    </div>
</form>
        </div>
    </div>
</div>

<div class="modal fade" id="notieditbrand" tabindex="-1" role="dialog" aria-labelledby="EditBrand" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            @if(isset($lot))
    <h3 class="modal-title" id="EditBrand">
    <b>แก้ไขข้อมูล LOT No : <span id="lot_display">{{ $lot->brd_lot }}</span></b>
    </h3>
@else
    <h3 class="modal-title" id="EditBrand">
        <b>ไม่พบข้อมูล LOT</b> <b id="showoutlot"></b>
    </h3>
@endif                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editbrandform">
    <div class="modal-body">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="text-center">
        <select name="bl_id" id="brd_brandlist_id_03"
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
        </div>
        <!-- ✅ ใส่ `brd_id` ในฟอร์ม -->
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
            <form id="editempwipform" method="POST">
    @csrf
    @method('PUT') <!-- ใช้ `_method=PUT` เพื่อรองรับการอัปเดต -->

    <div class="modal-body">
        <div class="text-center">
            <select name="wip_empgroup_id_1" class="margin-select selectpicker show-tick form-control"
                    aria-required="true" data-size="9" data-dropup-auto="true" data-live-search="true"
                    data-style="btn-info btn-md text-white" data-width="fit" data-container="body" required>
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


 