@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/notif/0.1.0/notif.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/notif/0.1.0/notif.min.js"></script>
@include('model')

<style>
    /* สไตล์ของปุ่มสลับ */
.switch {
    position: relative;
    display: inline-block;
    width: 70px;
    height: 30px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ff4d4d; /* สีแดงเมื่อปิด */
    transition: 0.4s;
    border-radius: 30px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 24px;
    width: 24px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}
.is-invalid {
    border: 2px solid red;
}


/* ข้อความ "เปิด" และ "ปิด" */
.text-on,
.text-off {
    position: absolute;
    font-size: 14px;
    font-weight: bold;
    line-height: 30px;
    color: white;
    transition: 0.4s;
    pointer-events: none;
}

.text-on {
    left: 10px;
    opacity: 0;
}

.text-off {
    right: 10px;
    opacity: 1;
}

/* เมื่อ Checkbox ถูกเปิด */
input:checked + .slider {
    background-color: #40bf40; /* สีเขียวเมื่อเปิด */
}

input:checked + .slider:before {
    transform: translateX(40px); /* ขยับวงกลมไปทางขวา */
}

/* ข้อความเมื่อเปิด */
input:checked + .slider .text-on {
    opacity: 1;
}

input:checked + .slider .text-off {
    opacity: 0;
}

    .text-so-white {
    color: white !important;
}
.small-box {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .small-box:hover {
        transform: scale(1.05); /* ขยายขนาดขึ้น 5% */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
    }
    .button-container {
    display: flex;
    gap: 15px;
}

.btn-custom {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    color: white !important; /* ทำให้ตัวอักษรเป็นสีขาวเสมอ */
    text-decoration: none;
    border: none;
    border-radius: 5px;
    background-clip: padding-box;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.btn-custom i {
    margin-right: 8px;
}

.btn-home {
    background-color: #fcb123; /* สีเหลือง */
}

.btn-select {
    background-color: #1dbfa0; /* สีเขียว */
}

.btn-custom:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3); /* เพิ่มเงาเมื่อ Hover */
}
.custom-danger-btn {
    background-color: #ff4d4d !important; /* สีแดงสด */
    color: #fff !important; /* สีตัวอักษรขาว */
    border-radius: 8px; /* ขอบโค้ง */
    padding: 12px 24px; /* เพิ่มขนาดปุ่ม */
    font-size: 1.2em; /* ขนาดตัวอักษร */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* เพิ่มเงาเริ่มต้น */
    transition: all 0.3s ease; /* เพิ่มเอฟเฟกต์ลื่นไหล */
    text-align: center;
    display: inline-block;
}

.custom-danger-btn:hover {
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อเอาเม้าส์ไปวาง */
    transform: translateY(-2px); /* ขยับขึ้นเล็กน้อย */
}
.custom-select-container {
    position: relative;
    display: inline-block;
    width: auto;
}

.custom-select-container {
    position: relative;
    display: inline-block;
    width: auto;
}

.custom-select-box {
    appearance: none; /* ซ่อน UI ดั้งเดิมของ Select */
    background-color: #00a6e0; /* สีพื้นหลัง */
    color: white; /* สีตัวอักษร */
    border: none; /* ไม่มีขอบ */
    border-radius: 5px; /* มุมโค้ง */
    padding: 8px 20px; /* ระยะห่างด้านใน */
    padding-right: 30px; /* ระยะสำหรับสามเหลี่ยม */
    font-size: 16px; /* ขนาดตัวอักษร */
    line-height: 1.5; /* จัดข้อความให้ตรงกลาง */
    text-align: center; /* จัดข้อความให้อยู่ตรงกลาง */
    cursor: pointer;
    width: auto; /* ปรับขนาดอัตโนมัติตามเนื้อหา */
    display: inline-block; /* แสดงผลแบบอินไลน์ */
    min-width: 100px; /* กำหนดความกว้างขั้นต่ำ */
}

.custom-select-container::after {
    content: "▼"; /* สามเหลี่ยมคว่ำ */
    font-size: 12px; /* ขนาดสามเหลี่ยม */
    color: white; /* สีสามเหลี่ยม */
    position: absolute;
    top: 50%; /* วางตรงกลางในแนวตั้ง */
    right: 10px; /* ลดระยะให้ชิดข้อความ */
    transform: translateY(-50%); /* จัดตำแหน่งให้ตรงกลางพอดี */
    pointer-events: none; /* ไม่ให้คลิกที่สามเหลี่ยมได้ */
}
.custom-select-box:hover {
    background-color: #007bb5; /* สีเมื่อ Hover */
}

.custom-select-box:focus {
    outline: none; /* ลบเส้นขอบ Focus ดั้งเดิม */
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25); /* เพิ่มเงา Focus */
}






    </style>
<div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                <a href="{{ route('mainmenu') }}" class="btn-custom btn-home" name="button">
        <i class="fa fa-home"></i> กลับไปยังเมนูหลัก
    </a>
    <a data-toggle="modal" data-target="#selectline" class="btn-custom btn-select" name="button">
        <i class="fa fa-list-ol"></i> เลือกไลน์ผลิต
    </a>

                </div>
                <h2><b>ระบบ QC (คัดบอร์ด) : {{ $lineheader }}</b></h2>
                <div class="text-center">
                <form id="formworking" class="form-inline md-form form-sm mt-0" method="post" action="{{ route('workgroup.start') }}">
    @csrf
  
    <div class="custom-select-container">
        <select class="custom-select-box" id="groupSelector" name="groupSelector" required>
            <option value="">เลือกกลุ่ม</option>
            @foreach ($groups as $group)
                <option value="{{ $group }}">{{ $group }}</option>
            @endforeach
        </select>
    </div>
    <input class="form-control text-center groupreadonly" type="text" id="groupDisplay" name="groupDisplay" placeholder="กลุ่ม" disabled>
    <input type="hidden" name="ww_group" id="ww_group">
    <input type="hidden" name="ww_line" id="ww_line" value="{{ $line }}">
    <input type="hidden" name="ww_status" value="W">
    <input type="hidden" name="ww_division" value="QC">
    <button class="btn btn-warning" type="submit" name="button">
        <b>เริ่มงานใหม่</b>&nbsp;&nbsp;<i class="fas fa-file-import"></i>
    </button>
    <input type="date" id="datePicker" name="ww_lot_date" class="form-control text-center" data-toggle="tooltip" title="วันที่" style="width:12%;" placeholder="วันที่">
</form>

                </div>
                <h3><b><u>รายการงานคัดบอร์ดวันนี้ 
                    </u></b></h3><br>
                   
                       
                    <div class="text-left">
    <a data-target="#endworktimenoti" data-toggle="modal" class="btn btn-danger custom-danger-btn">
        <b>จบกะทำงาน&nbsp;&nbsp;<i class="fas fa-share-square"></i></b>
    </a>
    <br><br>
</div>
         <script>
         document.addEventListener('DOMContentLoaded', function () {
    const groupSelector = document.getElementById('groupSelector');
    const groupDisplay = document.getElementById('groupDisplay');
    const wwGroupInput = document.getElementById('ww_group');
    const wwLineInput = document.getElementById('ww_line');
    const datePicker = document.getElementById('datePicker');

    // ตั้งค่าวันที่เริ่มต้นเป็นวันที่ปัจจุบัน
    const today = new Date();
    const currentDate = today.toISOString().split('T')[0]; // แปลงวันที่เป็นรูปแบบ 'YYYY-MM-DD'
    datePicker.value = currentDate;

    // เปลี่ยนค่าช่องแสดงผลเมื่อเลือกกลุ่ม
    groupSelector.addEventListener('change', function () {
        const selectedGroup = groupSelector.value;
        const currentLine = wwLineInput.value || '1'; // ใช้ไลน์ปัจจุบัน หรือกำหนดค่าเริ่มต้นเป็น '1'

        if (selectedGroup) {
            const displayValue = `${currentLine}${selectedGroup}`;
            groupDisplay.value = displayValue;
            wwGroupInput.value = selectedGroup;
        } else {
            groupDisplay.value = '';
            wwGroupInput.value = '';
        }
    });
});

         </script>
<script>
   $(document).ready(function () {
    // กำหนดค่า ID และชื่อพนักงานใน Modal
    $(document).on('click', '.deleteemp', function () {
        var id = $(this).data('id'); // รับค่า ID จากปุ่มลบ
        var name = $(this).data('name'); // รับชื่อพนักงานจากปุ่มลบ

        $('#delete_empid').val(id); // ตั้งค่า ID ใน hidden input
        $('#delete_empname').text(name); // แสดงชื่อในข้อความยืนยัน
    });

    // ฟังก์ชันลบข้อมูลพนักงาน
    $('#deletempform').on('submit', function (e) {
        e.preventDefault();

        var id = $('#delete_empid').val();
        console.log("ID ที่จะลบ:", id); // ตรวจสอบ ID

        if (!id) {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่พบข้อมูลพนักงานที่จะลบ!',
            });
            return;
        }

        $.ajax({
            type: "DELETE",
            url: "/deleteemp/" + id,
            data: { _token: "{{ csrf_token() }}" }, // CSRF Token
            success: function (response) {
                console.log("ลบสำเร็จ:", response); // Debug Response
                Swal.fire({
                    icon: 'success',
                    title: 'ลบข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                });
                window.setTimeout(function () {
                    location.reload();
                }, 1200);
            },
            error: function (xhr) {
                console.error("ข้อผิดพลาด:", xhr.responseText); // Debug Error
                Swal.fire({
                    icon: 'error',
                    title: 'ลบข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true,
                });
            }
        });
    });
});

</script>
        
          

<script>
document.getElementById('addempname').addEventListener('click', function (e) {
    e.preventDefault();

    const newRow = `
        <div class="row">
            <div class="col-md-6 text-center">
                <b style="font-size:16px;">ชื่อ : </b>
                <input type="text" name="ue_name[]" class="form-control text-center" data-toggle="tooltip" title="กรอกชื่อ" style="width:70%;" placeholder="กรอกชื่อ" required>
            </div>
            <div class="col-md-6 text-center">
                <b style="font-size:16px;">หมายเหตุ : </b>
                <input type="text" name="ue_remark[]" class="form-control text-center" data-toggle="tooltip" title="หมายเหตุ" style="width:50%;" maxlength="50" placeholder="หมายเหตุ">
            </div>
        </div>`;
    document.getElementById('addmoreemp').insertAdjacentHTML('beforeend', newRow);
});
    </script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formemployee');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // ป้องกันการรีเฟรชหน้า

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response Data:', data);

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลแล้ว',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                });

                // ตั้งเวลาให้รีเฟรชหน้าหลังจาก 1.3 วินาที
                window.setTimeout(function () {
                    location.reload();
                }, 1300);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: true
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'ข้อผิดพลาด!',
                html: '<small style="color:red;">ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้</small>',
                showConfirmButton: true
            });
        });
    });
});




    </script>

<script>
    function allowDrop(event) {
        event.preventDefault(); // อนุญาตให้ปล่อยข้อมูลในพื้นที่นี้
    }

    function drag(event) {
        // ดึงชื่อพนักงานจาก data-name
        const employeeName = event.target.getAttribute('data-name');
        if (employeeName) {
            event.dataTransfer.setData("text", employeeName); // ส่งชื่อพนักงานไปยัง drop
        }
    }

    function drop(event) {
        event.preventDefault();
        // ดึงข้อมูลชื่อพนักงานจาก dataTransfer
        const employeeName = event.dataTransfer.getData("text");

        // ตรวจสอบว่ามีชื่อหรือไม่ก่อนใส่ในช่อง
        if (employeeName) {
            if (event.target.value) {
                event.target.value += `, ${employeeName}`; // ถ้ามีชื่ออยู่แล้วให้เพิ่มต่อท้าย
            } else {
                event.target.value = employeeName; // ถ้ายังไม่มีชื่อ ให้ใส่ชื่อแรก
            }
        }
    }
</script>

<script>
    $(document).ready(function () {
        $('#formgroupemp').on('submit', function (e) {
            e.preventDefault(); // ป้องกันการรีเฟรชหน้า

            // ตรวจสอบว่า dropdown ถูกเลือกหรือไม่
            var isValid = true;
            $('select[name="addempgroup1[]"], select[name="addempgroup2[]"]').each(function () {
                if ($(this).val() === "0" || $(this).val().trim() === '') {
                    isValid = false;
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'กรุณาเลือกพนักงานให้ครบถ้วน',
                    html: '<small style="color:red;">ห้ามมีช่องว่าง</small>',
                    showConfirmButton: true
                });
                return; // หยุดการส่งฟอร์ม
            }

            // ส่งข้อมูลผ่าน AJAX
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), // ใช้ URL จาก form action
                data: $(this).serialize(), // แปลงข้อมูลในฟอร์ม
                dataType: 'json',
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกเรียบร้อย',
                        html: '<small style="color:green;">ข้อมูลถูกบันทึกเรียบร้อย</small>',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    setTimeout(function () {
                        location.reload(); // รีเฟรชหน้า
                    }, 1350);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'เกิดข้อผิดพลาด';
                    Swal.fire({
                        icon: 'error',
                        title: 'บันทึกไม่สำเร็จ',
                        html: '<small style="color:red;">' + errorMessage + '</small>',
                        showConfirmButton: true
                    });

                    console.error(xhr.responseText); // Debug
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
    $('#empgrouptable').on('change', '.toggle-egstatus', function() {
        var status = $(this).prop('checked') ? 1 : 0; // ตรวจสอบว่าเปิดหรือปิด
        var id = $(this).data('id'); // รับ ID ของ GroupEmp

        $.ajax({
            type: "POST",
            url: "/egstatus/toggle", // URL ที่เชื่อมไปยัง Backend
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF Token
                id: id,
                status: status
            },
            success: function(response) {
                if (response.success) {
                    notif({
                        msg: "<b>" + (status === 1 
                            ? "เปิดการใช้งาน " + response.emp1 + " - " + response.emp2 + " แล้ว"
                            : "ปิดการใช้งาน " + response.emp1 + " - " + response.emp2 + " แล้ว") + "</b>",
                        type: status === 1 ? "success" : "warning"
                    });
                } else {
                    notif({
                        msg: "<b>" + response.message + "</b>",
                        type: "error"
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log ข้อผิดพลาด
                notif({
                    msg: "<b>เกิดข้อผิดพลาดในการเชื่อมต่อ</b>",
                    type: "error"
                });
            }
        });
    });
});



    </script>
<script>
  $(document).ready(function() {
    $(".delete-work").click(function() {
        var workId = $(this).data("id"); // ✅ ใช้ ww_id
        var line = $(this).data("line");

        console.log("Clicked delete for ID:", workId);

        // อัปเดตค่าใน input hidden
        $("#delete_id").val(workId);

        // อัปเดต action ของ form (แต่ใช้ AJAX แทน)
        $("#deleteForm").attr("action", "/delete-workprocess/" + workId);
    });

    $("#deleteForm").submit(function(event) {
        event.preventDefault(); 

        var form = $(this);
        var actionUrl = form.attr("action");

        console.log("Submitting form to:", actionUrl);

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                console.log("Success Response:", response);
                Swal.fire({
                    icon: 'success',
                    title: 'ลบข้อมูลสำเร็จ',
                    html: '<small style="color:green;">' + (response.message || "ข้อมูลถูกลบแล้ว") + '</small>',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                console.log("Error Response:", xhr);
                let errorMessage = "เกิดข้อผิดพลาดในการลบข้อมูล";
                try {
                    let response = JSON.parse(xhr.responseText);
                    errorMessage = response.message || errorMessage;
                } catch (e) {
                    console.error("JSON parse error:", e);
                }

                Swal.fire({
                    icon: 'error',
                    title: 'ลบข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">' + errorMessage + '</small>',
                    showConfirmButton: true
                });
            }
        });
    });
});

</script>

<script>
    $(document).ready(function() {
        $(".enter-work").click(function() {
            var workId = $(this).data("id"); // ดึงค่า work id
            var line = $(this).data("line"); // ดึงค่า line
            var targetUrl = "/production/datawip/L" + line + "/" + workId; // สร้าง URL ใหม่

            console.log("Redirecting to:", targetUrl); // Debug URL
            window.location.href = targetUrl; // เปลี่ยนหน้าไปยัง URL ใหม่
        });
    });
</script>


<script>
$(document).ready(function() {
    // เพิ่มแถวใหม่เมื่อกดปุ่ม "+"
    $("#addempgroup").click(function(e) {
        e.preventDefault();

        let newDropdownRow = `
            <div class="d-flex justify-content-center align-items-center gap-2 w-100 mt-1 extra-row">
                <select name="addempgroup1[]" class="selectpicker show-tick form-control-sm w-100 text-center" data-live-search="true" data-style="btn-info btn-sm text-white" required>
                    <option value="0">เลือกพนักงาน</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->name }}" data-id="employee-{{ $loop->index }}">
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>

                <select name="addempgroup2[]" class="selectpicker show-tick form-control-sm w-100 text-center" data-live-search="true" data-style="btn-info btn-sm text-white" required>
                    <option value="0">เลือกพนักงาน</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->name }}" data-id="employee-{{ $loop->index }}">
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        `;

        $("#dropdownContainer").append(newDropdownRow);
        $('.selectpicker').selectpicker('refresh');
    });
});
 
</script>
<script>
    $(document).ready(function() {
        // ลบเฉพาะแถวที่ถูกเพิ่มโดยปุ่ม "+"
        $("#removegroup").click(function() {
            $("#dropdownContainer .extra-row").remove();
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#formworking').on('submit', function (e) {
            e.preventDefault(); // ป้องกันการรีเฟรชหน้า

            Swal.fire({
                title: 'ยืนยันการเริ่มงานใหม่?',
                text: "คุณต้องการสร้างงานใหม่ใช่หรือไม่",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, เริ่มงานใหม่!',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false, 
                allowEscapeKey: false, 
                preConfirm: () => {
                    return new Promise((resolve) => {
                        let formData = $('#formworking').serialize(); // ดึงข้อมูลจากฟอร์ม

                        $.ajax({
                            type: 'POST',
                            url: $('#formworking').attr('action'),
                            data: formData,
                            dataType: 'json',
                            success: function (response) {
                                console.log("AJAX Response:", response); // ✅ Debug ค่า Response

                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'บันทึกสำเร็จ!',
                                        text: response.date, // ✅ แสดงวันที่แบบไทย
                                        showConfirmButton: false,
                                        timer: 3000
                                    });

                                    // ✅ Redirect ไปยัง datawip หลังจาก SweetAlert หายไป
                                    setTimeout(function () {
                                        console.log("Redirecting to:", response.redirect_url); // ✅ Debug URL
                                        window.location.href = response.redirect_url;
                                    }, 3000);

                                    resolve();
                                } else {
                                    Swal.showValidationMessage("❌ เกิดข้อผิดพลาด: " + response.message);
                                    resolve();
                                }
                            },
                            error: function (xhr) {
                                console.error("AJAX Error:", xhr); // ✅ Debug Error
                                Swal.showValidationMessage('❌ AJAX Error: ' + xhr.responseJSON.message);
                                resolve();
                            }
                        });
                    });
                }
            });
        });
    });
</script>


<script>
$(document).ready(function () {
    $('#endworktimeform').on('submit', function (e) {
        e.preventDefault();

        let line = $("#line").val();
        let ww_ids = [];

        // ✅ ดึงค่า WW_ID จาก `.enter-work`
        $('.enter-work').each(function () {
            let ww_id = $(this).data('id');
            if (ww_id) ww_ids.push(ww_id);
        });

        console.log("✅ WW_IDs ที่ดึงจาก <tbody>: ", ww_ids);

        if (!line) {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: "ไม่พบค่า Line กรุณาตรวจสอบ",
                showConfirmButton: true
            });
            return;
        }

        // ✅ เปิด popup ล่วงหน้า
        let popupEndtime = window.open('', 'endtimeWindow', 'width=800,height=600');

        if (!popupEndtime) {
            Swal.fire({
                icon: 'warning',
                title: 'การเปิดหน้าต่างถูกบล็อค',
                text: "กรุณาอนุญาตให้เว็บไซต์เปิด popup",
                showConfirmButton: true
            });
            return;
        }

        $.ajax({
            type: "POST",
            url: "/endworktime/" + line,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                wwt_status: $("input[name='wwt_status']").val(),
                wz_amount: $("input[name='wz_amount']").val(),
                wwd_amount: $("input[name='wwd_amount']").val(),
                line: line,
                ww_ids: JSON.stringify(ww_ids) // ✅ ส่งเป็น JSON
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (result) {
                console.log("✅ Response Data:", result);

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกเรียบร้อย',
                        html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    if (result.wwt_index && ww_ids.length > 0) {
                        let workParam = ww_ids.join(',');
                        let urlEndtime = `/endtimeinterface/${line}/${result.wwt_index}/${workParam}`;
                        
                        console.log("✅ เปิด Popup URL:", urlEndtime);

                        // ✅ ใช้ setTimeout ให้ popup โหลดได้แน่นอน
                        setTimeout(() => {
                            popupEndtime.location.href = urlEndtime;
                            popupEndtime.focus();
                        }, 100);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: "ไม่พบ wwt_index หรือ WW_IDs กรุณาตรวจสอบระบบ",
                            showConfirmButton: true
                        });
                        popupEndtime.close();
                    }

                    // ✅ AJAX: บันทึก WorkprocessTemp
                    $.ajax({
                        type: "POST",
                        url: "/store-workprocess-temp",
                        data: JSON.stringify({
                            wwt_id: result.wwt_id,
                            line: line,
                            ww_ids: ww_ids
                        }),
                        contentType: "application/json",
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function (workprocessResult) {
                            console.log("✅ WorkprocessTemp Saved:", workprocessResult);
                        },
                        error: function (xhr) {
                            console.error("❌ Error saving WorkprocessTemp:", xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: xhr.responseJSON ? xhr.responseJSON.message : "บันทึก WorkprocessTemp ไม่สำเร็จ",
                                showConfirmButton: true
                            });
                        }
                    });

                    setTimeout(() => { location.reload(); }, 2000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: result.error || "ไม่สามารถบันทึกข้อมูลได้",
                        showConfirmButton: true
                    });
                    popupEndtime.close();
                }
            },
            error: function (xhr) {
                console.error("❌ AJAX Error:", xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    text: xhr.responseJSON ? xhr.responseJSON.message : "เกิดข้อผิดพลาด กรุณาลองใหม่",
                    showConfirmButton: true
                });
                popupEndtime.close();
            }
        });
    });
});


</script>
<script>
$(document).ready(function() {
    function loadWipData(line) {
        $.ajax({
            url: `/get-wip-data/${line}`,
            type: "GET",
            success: function(response) {
                let rows = "";
                if (response.length > 0) {
                    response.forEach(function(wip) {
                        rows += `<tr>
                            <td class="text-center">${formatDate(wip.date)}</td>
                            <td class="text-center">${parseFloat(wip.total_wip_amount).toLocaleString()}</td>
                        </tr>`;
                    });
                } else {
                    rows = `<tr><td colspan="2" class="text-center">ไม่มีข้อมูล WIP</td></tr>`;
                }
                $("#wipData").html(rows);
            },
            error: function() {
                $("#wipData").html(`<tr><td colspan="2" class="text-center text-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>`);
            }
        });
    }

    function formatDate(dateStr) {
        let date = new Date(dateStr);
        return date.toLocaleDateString("th-TH", { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    // ✅ ดึงค่า `line` จาก URL (ถ้า URL เป็น /manufacture/3 จะได้ line = 3)
    let line = window.location.pathname.split('/').pop();
    
    // ✅ โหลดข้อมูล WIP ตาม `line`
    loadWipData(line);
});
</script>
<script>
$(document).ready(function () {
    // ✅ เมื่อโมเดล `endworktimenoti` ถูกเปิด
    $('#endworktimenoti').on('shown.bs.modal', function () {
        let ww_ids = [];

        // ✅ ดึงค่า WW_ID จาก <tbody> โดยใช้ class .enter-work
        $('.enter-work').each(function() {
            let ww_id = $(this).data('id');
            if (ww_id) ww_ids.push(ww_id);
        });

        // ✅ ตรวจสอบว่ามี WW_ID หรือไม่
        if (ww_ids.length > 0) {
            console.log("✅ WW_IDs ที่ดึงจาก <tbody>: ", ww_ids);

            // ✅ แสดงค่าแต่ละ WW_ID แยกบรรทัดใน Console
            console.log("✅ รายการ WW_ID:");
            ww_ids.forEach((id, index) => {
                console.log(`🔹 WW_ID ${index + 1}: ${id}`);
            });

            // ✅ อัพเดตค่า WW_ID ลงใน hidden input (ใช้ส่งไปกับ Form)
            $('#ww_ids_input').val(JSON.stringify(ww_ids));

        } else {
            // ✅ แจ้งเตือนผ่าน SweetAlert2 แทน console.warn()
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่พบ WW_ID ใน <tbody>, โปรดตรวจสอบข้อมูล',
                showConfirmButton: true
            });
        }
    });
});
</script>





                <p> 
                    <h4>
                    เงื่อนไข <br>
                    {{-- &#9989;&nbsp; --}}1. คลังสินค้าต้องสแกนข้อมูลครบก่อน <br>
                    {{-- &#10060;&nbsp; --}}2. คลังสินค้าต้องออก CSV ก่อน<br> 
                    {{-- &#10060;&nbsp;--}}3. ต้องจบการทำงานทุกตัว<br>
                    </h4>
                </p><br>
                <div class="table-responsive">
                <table class="table table-striped table-bordered display" id="worktable">
    <thead>
        <tr class="text-table-so">
            <th class="text-center">#</th>
            <th class="text-center">กลุ่ม</th>
            <th class="text-center">ชนิดสินค้า</th>
            <th class="text-center">สถานะ</th>
            <th class="text-center">วันที่</th>
            <th class="text-center"><em class="fa fa-cog"></em></th>
            <th style="width:1px;"></th>
        </tr>
    </thead>
    <tbody>
    @if($workProcessQC->isEmpty())
        <tr>
            <td colspan="7" class="text-center">No data available in table</td>
        </tr>
    @else
        @foreach($workProcessQC->unique('id') as $index => $wpqc)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td> {{-- ลำดับที่ --}}
            <td class="text-center">{{ $wpqc->group }}</td> {{-- ดึง ww_group --}}
            <td class="text-center">{{ $wpqc->pe_type_name ?? '-' }}</td> {{-- ประเภทงาน --}}
            <td class="text-center"> 
                <span class="{{ $wpqc->status === 'W' ? 'text-success' : 'text-danger' }}">
                    <b>{{ $wpqc->status === 'W' ? 'กำลังคัด' : ($wpqc->status === 'E' ? 'จบการทำงาน' : 'ไม่ทราบสถานะ') }}</b>
                </span>
            </td>
            <td class="text-center">
    {{ !empty($wpqc->start_date) ? date('d-m-Y', strtotime($wpqc->start_date)) : '-' }}
</td>

                <td class="text-center">
                <a href="#" class="btn btn-success btn-sm fas fa-file-import enter-work" 
   data-toggle="tooltip" 
   title="เข้าสู่งาน" 
   style="font-size:15px;" 
   data-id="{{ $wpqc->id }}" 
   data-line="{{ $wpqc->line }}">
</a>
@if ($wpqc->status === 'W')
    <a href="#" class="btn btn-danger btn-sm fa fa-trash delete-work"
       data-toggle="modal"
       data-target="#notideletework"
       data-id="{{ $wpqc->id }}"
       data-line="{{ $wpqc->line }}">
    </a>
@endif



                    {{-- Modal สำหรับลบข้อมูล --}}
                   <!-- Modal ลบข้อมูล (ใช้ตัวเดียวกันทุกปุ่ม) -->
<div class="modal fade" id="notideletework" tabindex="-1" role="dialog" aria-labelledby="DeleteWork" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="DeleteWork"><b>ลบข้อมูลงาน</b></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                <input type="hidden" name="delete_id" id="delete_id">
                <h4 id="deleteMessage" style="color:red;">คุณต้องการดำเนินการต่อหรือไม่</h4>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-danger">ลบข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

                </td>
                <td style="width:1px;opacity:0;" class="text-center"></td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>

                </div>
                <br>
                <br>
<!-- รายการงานที่ผ่านมา -->
<div class="container-fluid">
    <div class="row">
        <!-- รายการงานที่ผ่านมา -->
        <div class="col-lg-3 col-xs-3">
            <!-- small box -->
            <a data-target="#notiallworked" data-toggle="modal">
                <div class="small-box bg-red card-shadow">
                    <div class="inner">
                        <br>
                        <h3 class="text-center" style="font-size:1.2vw; color: white;">รายการงานที่ผ่านมา <i class="fas fa-file-alt"></i></h3>
                        <p class="text-center" style="color: white;">รายการงานที่ผ่านมา : {{ $lineheader }}</p>
                    </div>
                    <a data-target="#notiallworked" data-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </a>
        </div>
        <!-- จบ รายการงานที่ผ่านมา -->
        
        <!-- รายชื่อพนักงาน -->
        <div class="col-lg-3 col-xs-3">
            <!-- small box -->
            <a data-target="#inputemnoti" data-toggle="modal">
                <div class="small-box bg-blue card-shadow">
                    <div class="inner">
                        <br>
                        <h3 class="text-center" style="font-size:1.2vw; color: white;">รายชื่อพนักงาน <i class="far fa-address-card"></i></h3>
                        <p class="text-center" style="color: white;">รายชื่อพนักงาน : {{ $lineheader }}</p>
                    </div>
                    <a data-target="#inputemnoti" data-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </a>
        </div>
        <!-- จบ รายชื่อพนักงาน -->

        <!-- จัดกลุ่มพนักงาน -->
        <div class="col-lg-3 col-xs-3">
            <!-- small box -->
            <a data-target="#groupemnoti" data-toggle="modal">
                <div class="small-box bg-orange card-shadow">
                    <div class="inner">
                        <br>
                        <h3 class="text-center" style="font-size:1.2vw; color: white;">จัดกลุ่มพนักงาน <i class="far fa-address-book"></i></h3>
                        <p class="text-center" style="color: white;">จัดกลุ่มพนักงาน : {{ $lineheader }} </p>
                    </div>
                    <a data-target="#groupemnoti" data-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </a>
        </div>
        <!-- จบ จัดกลุ่มพนักงาน -->

        <!-- สรุปข้อมูลต่อวัน -->
        <div class="col-lg-3 col-xs-3">
            <!-- small box -->
            <a data-target="#notiwipperday" data-toggle="modal">
                <div class="small-box bg-green card-shadow">
                    <div class="inner">
                        <br>
                        <h3 class="text-center" style="font-size:1.2vw; color: white;">สรุปข้อมูลต่อวัน <i class="fas fa-chart-bar"></i></h3>
                        <p class="text-center" style="color: white;">สรุปข้อมูลต่อวัน : {{ $lineheader }}</p>
                    </div>
                    <a data-target="#notiwipperday" data-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </a>
        </div>
        <!-- จบ สรุปข้อมูลต่อวัน -->
    </div>
</div>

<!-- ส่วนตัวเติม ของ modal เลือกไลน์ผลิต-->
    <div class="modal fade" id="selectline">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h3 class="modal-title">เลือกไลน์ผลิต </h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <p class="text-danger">เลือกไลน์ตามความเป็นจริง เพื่อข้อมูลที่ถูกต้อง</p>
                </div>

                <!-- Modal body -->
                <div class="panel-body">
                    <div class="container-fluid" style="width:90%;">
                        <div class="row">
                            <div class="col-lg-4 col-xs-4 text-white">
                                <!-- small box -->
                                <a href="{{ route('manufacture', ['line' => 1]) }}">
                                    <div class="small-box bg-green card-shadow">
                                        <div class="inner">
                                            <br>
                                            <h3 class="text-center text-so-white" style="font-size:1.2vw;">ไลน์ 1</h3>
                                            <p class="text-so-white text-center">Line 1</p>
                                        </div>
                                        <a href="{{ route('manufacture', ['line' => 1]) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </a>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-4 col-xs-4">
                                <!-- small box -->
                                <a href="{{ route('manufacture', ['line' => 2]) }}">
                                    <div class="small-box bg-yellow card-shadow">
                                        <div class="inner">
                                            <br>
                                            <h3 class="text-center text-so-white" style="font-size:1.2vw;">ไลน์ 2</h3>
                                            <p class="text-center text-so-white">Line 2</p>
                                        </div>
                                        <a href="{{ route('manufacture', ['line' => 2]) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-4 col-xs-4">
                                <!-- small box -->
                                <a href="{{ route('manufacture', ['line' => 3]) }}">
                                    <div class="small-box bg-blue card-shadow">
                                        <div class="inner">
                                            <br>
                                            <h3 class="text-center text-so-white" style="font-size:1.2vw;">ไลน์ 3</h3>
                                            <p class="text-center text-so-white">Line 3</p>
                                        </div>
                                        <a href="{{ route('manufacture', ['line' => 3]) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
<!-- จบ process modal เลือกไลน์ผลิต -->
<!-- ข้อมูลพนักงาน -->
<div class="modal fade" id="inputemnoti" tabindex="-1" role="dialog" aria-labelledby="DeleteEmp" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="DeletelEmp"><b>ข้อมูลพนักงาน</b></h3>
                </div>
                <form id="formemployee" class="form-inline md-form form-sm mt-0" method="POST" action="{{ route('save-employees', ['line' => request()->route('line')]) }}">
    @csrf
    <div class="container">
        <h4><b><u>เพิ่มข้อมูลพนักงาน</u></b></h4>
        <a href="#" class="btn btn-warning btn-sm fa fa-plus" id="addempname" role="button"></a>
        <a href="#" id="removeempmore" class="btn btn-info btn-sm fa fa-remove" role="button"></a>
    </div>
    <div id="addmoreemp">
        <div class="row">
            <div class="col-md-6 text-center">
                <b style="font-size:16px;">ชื่อ : </b>
                <input type="text" name="ue_name[]" class="form-control text-center" data-toggle="tooltip" title="กรอกชื่อ" style="width:70%;" placeholder="กรอกชื่อ" required>
                <input type="hidden" name="ue_remark[]" value="">
            </div>
            <div class="col-md-6 text-center">
                <b style="font-size:16px;">หมายเหตุ : </b>
                <input type="text" name="ue_remark[]" class="form-control text-center" data-toggle="tooltip" title="หมายเหตุ" style="width:50%;" maxlength="50" placeholder="หมายเหตุ">
            </div>
        </div>
    </div>
    <br>
    <div class="text-center">
    <button class="btn btn-success btn-md" id="saveEmployeesButton" type="submit">
    บันทึกข้อมูล <i class="fas fa-user-plus"></i>
</button>

    </div>
</form>

<div class="container-fluid">
                    <h4><b><u>รายชื่อพนักงาน</u></b></h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered display" id="emptable">
                            <thead>
                                <tr class="text-table-so">
                                    <th class="text-center">#</th>
                                    <th class="text-center">ชื่อพนักงาน</th>
                                    <th class="text-center">หมายเหตุ</th>
                                    <th class="text-center"><em class="fa fa-cog"></em></th>
                                    <th style="width:1px;"></th>
                                </tr>
                            </thead>
                            <tbody>
    @forelse($employees as $employee)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td> <!-- ลำดับ -->
            <td class="text-center">{{ $employee->name }}</td> <!-- ชื่อพนักงาน -->
            <td class="text-center">{{ $employee->note }}</td> <!-- หมายเหตุ -->
            <td class="text-center">
            <a href="#" 
   class="btn btn-danger btn-sm fa fa-trash deleteemp" 
   data-id="{{ $employee->id }}" 
   data-name="{{ $employee->name }}" 
   data-toggle="modal" 
   data-target="#notideleteemp" 
   title="ลบข้อมูล" 
   style="font-size:15px;">
</a>

            </td>
            <td style="width:1px;opacity:0;"></td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">ไม่มีข้อมูลพนักงาน</td>
        </tr>
    @endforelse
</tbody>

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
<!-- จบ process modal ของการเพิ่มพนังงาน -->
<!-- จัดกลุ่มพนักงาน -->
<div class="modal fade" id="groupemnoti" tabindex="-1" role="dialog" aria-labelledby="GroupEm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="GroupEm"><b>จัดกลุ่มพนักงาน</b></h3>
            </div>

            <!-- Modal Body -->
            <div class="container-fluid">
                <div class="row">
                    <!-- Group Form Area with Add Button and Dropdowns -->
                    <div class="col-md-12">
                        <form id="formgroupemp" class="form-inline form-sm mt-0" method="post" action="{{ route('saveEmpGroup', ['line' => $line]) }}">
                            @csrf 
                            <div id="empgroupadded" class="text-center mb-4">
                                <h4><b><u>กลุ่ม</u></b></h4>
                                <div class="row mt-3">
                                    <!-- Add Button Area -->
                                    <div class="col-md-1">
                                    <a class="btn btn-success btn-sm" 
   style="background-color: #00b5ad; border-color: #00b5ad; color: #fff; font-size:13px;" 
   id="addempgroup" 
   href="javascript:void(0);" 
   role="button">
   <span class="glyphicon glyphicon-plus"></span>
</a>


                                    </div>
                                    <!-- Employee Selection Area -->
                                    <div class="col-md-11">
    <div id="dropdownContainer" class="text-center">
        <!-- แถวเริ่มต้น -->
        <div class="d-flex justify-content-center align-items-center gap-3 w-100">
            <!-- First Employee Dropdown -->
            <select name="addempgroup1[]" 
    class="selectpicker show-tick form-control-sm w-100 text-center" 
    data-live-search="true" 
    data-style="btn-info btn-sm text-white" 
    required>
    <option value="0">เลือกพนักงาน</option>
    @foreach($employees as $employee)
        <option value="{{ $employee->name }}" data-id="employee-{{ $loop->index }}">
            {{ $employee->name }}
        </option>
    @endforeach
</select>


         <!-- Second Employee Dropdown -->
<select name="addempgroup2[]" 
    class="selectpicker show-tick form-control-sm w-100 text-center" 
    data-live-search="true" 
    data-style="btn-info btn-sm text-white" 
    required>
    <option value="0">เลือกพนักงาน</option>
    @foreach($employees as $employee)
        <option value="{{ $employee->name }}" data-id="employee-{{ $loop->index }}">
            {{ $employee->name }}
        </option>
    @endforeach
</select>



                            <div class="text-right mt-2">
                                <button id="removegroup" class="btn btn-warning btn-sm" type="button" name="button">
                                    <span class="fas fa-redo-alt"></span>&nbsp;ทำใหม่
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Save Button Area -->
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <button class="btn btn-success" type="submit" form="formgroupemp">
                            <i class="fas fa-save"></i>&nbsp;บันทึก
                        </button>
                    </div>
                </div>

                <!-- Table Area -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="empgrouptable" class="table table-striped table-bordered display">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center align-middle">#</th>
                                        <th class="text-center align-middle">กลุ่ม</th>
                                        <th class="text-center align-middle">สถานะเปิดใช้งาน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupemps as $index => $groupemp)
                                        <tr>
                                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                                            <td class="text-center align-middle">{{ $groupemp->emp1 }} - {{ $groupemp->emp2 }}</td>
                                            <td class="text-center align-middle">
                                                <label class="switch">
                                                    <input type="checkbox" 
                                                        class="toggle-egstatus" 
                                                        data-id="{{ $groupemp->id }}" 
                                                        data-emp1="{{ $groupemp->emp1 }}" 
                                                        data-emp2="{{ $groupemp->emp2 }}"
                                                        {{ $groupemp->status ? 'checked' : '' }}>
                                                    <span class="slider">
                                                        <span class="text-on">เปิด</span>
                                                        <span class="text-off">ปิด</span>
                                                    </span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

        <div class="modal fade" id="notideleteemp" tabindex="-1" role="dialog" aria-labelledby="DeleteEmp" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="DeletelEmp">ลบข้อมูลพนักงาน</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deletempform">
                <div class="modal-body">
                    <input type="hidden" name="id" id="delete_empid"> <!-- Input เก็บ ID -->
                    <h4 style="color:red;">คุณต้องการลบข้อมูล <span id="delete_empname"></span> หรือไม่?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-danger">ลบข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="notiwipperday" tabindex="-1" role="dialog" aria-labelledby="Wipperday" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="Wipperday"><b>สรุปข้อมูลต่อวัน</b></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="table-responsive">
                      
<table id="wipperdaytable" class="table table-striped table-bordered display">
    <thead>
        <tr>
            <th class="text-center">วันที่</th>
            <th class="text-center">จำนวน</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        @isset($groupedData)
 
        @foreach($groupedData as $data)
        <tr>
            <td class="text-center">{{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') }}</td> {{-- ✅ แสดงวันที่ --}}
            <td class="text-center">{{ $data->total_wip_amount ?? 0 }}</td> {{-- ✅ แสดงผลรวม wip_amount --}}
        </tr>
        @endforeach
        @endisset

    </tbody>
</table>  
        </tr>
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

<!-- จบ process modal ของจัดกลุ่มพนักงาน -->
<!-- รายการงานคัดบอร์ดที่ผ่านมา -->
       
<!-- จบ process modal รายการงานคัดบอร์ดที่ผ่านมา -->
<!-- สรุปข้อมูลต่อวัน -->
      
<!-- จบ process modal สรุปข้อมูลต่อวัน -->
        
        

        <script type="text/javascript">
            var line = '';
            var hiddeninput = '<input type="hidden" name="eg_line[]" value=""><input type="hidden" name="eg_division[]" value="QC"><input type="hidden" name="eg_emp_id_1[]" value=""><input type="hidden" name="eg_emp_id_2[]" value=""><input type="hidden" name="eg_status[]" value="1">';
            var hiddenempline = '<input type="hidden" name="ue_line[]" value="">';
        </script>
@endsection
