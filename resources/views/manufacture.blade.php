@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/notif/0.1.0/notif.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/notif/0.1.0/notif.min.js"></script>

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

        // ตรวจสอบข้อมูลในฟอร์ม (หากมี input ว่าง)
        var isValid = true;
        $('input[name="eg_emp1[]"], input[name="eg_emp2[]"]').each(function () {
            if ($(this).val().trim() === '') {
                isValid = false;
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                html: '<small style="color:red;">มีช่องข้อมูลที่ยังว่างอยู่</small>',
                showConfirmButton: true
            });
            return; // หยุดการส่งฟอร์ม
        }

        // ส่งข้อมูลผ่าน AJAX
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'), // ใช้ URL ที่กำหนดใน action ของฟอร์ม
            data: $(this).serialize(), // แปลงข้อมูลในฟอร์มเป็นรูปแบบ URL Encoded
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกเรียบร้อย',
                    html: '<small style="color:green;">ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง</small>',
                    showConfirmButton: false,
                    timer: 1500
                });

                setTimeout(function () {
                    location.reload(); // รีเฟรชหน้า
                }, 1350);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'เกิดข้อผิดพลาดในการบันทึก';
                Swal.fire({
                    icon: 'error',
                    title: 'บันทึกข้อมูลไม่สำเร็จ',
                    html: '<small style="color:red;">' + errorMessage + '</small>',
                    showConfirmButton: true
                });

                console.error(xhr.responseText); // สำหรับ Debugging
            }
        });
    });

    // ปุ่มทำใหม่ (รีเซ็ตฟอร์ม)
    $('#removegroup').on('click', function () {
        $('#formgroupemp')[0].reset(); // รีเซ็ตค่าทั้งหมดในฟอร์ม
        $('#empgroupadded').empty(); // ล้างข้อมูลใน #empgroupadded (ถ้ามีการเพิ่ม input ไดนามิก)
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
            var workId = $(this).data("id");
            var line = $(this).data("line");

            // อัปเดตค่าใน input hidden
            $("#delete_id").val(workId);

            // อัปเดต action ของ form (แต่ใช้ AJAX แทน)
            $("#deleteForm").attr("action", "/delete-workprocess/" + workId);
        });

        // เมื่อกดปุ่ม Submit ใน Modal
        $("#deleteForm").submit(function(event) {
            event.preventDefault(); // ป้องกัน Form เปิดหน้าใหม่

            var form = $(this);
            var actionUrl = form.attr("action");

            $.ajax({
                url: actionUrl,
                type: 'POST', // ใช้ POST
                data: form.serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ลบข้อมูลสำเร็จ',
                        html: '<small style="color:green;">ข้อมูลถูกลบแล้ว</small>',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ลบข้อมูลไม่สำเร็จ',
                        html: '<small style="color:red;">' + xhr.responseText + '</small>',
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
            @foreach($workProcessQC as $index => $wpqc)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td> {{-- ลำดับที่ (loop index) --}}
                <td class="text-center">{{ $wpqc->line }}{{ $wpqc->group }}</td> {{-- ดึง line --}}
                <td class="text-center">{{ $wpqc->pe_type_name ?? '-' }}</td>
                <td class="text-center">
    @if ($wpqc->status == 'กำลังคัด')
        <span class="text-success"><b>{{ $wpqc->status }}</b></span>
    @else
        <span class="text-danger"><b>{{ $wpqc->status }}</b></span>
    @endif
</td>
<td class="text-center">{{ date('d-m-Y', strtotime($wpqc->date)) }}</td>

                <td class="text-center">
                <a href="#" class="btn btn-success btn-sm fas fa-file-import enter-work" 
   data-toggle="tooltip" 
   title="เข้าสู่งาน" 
   style="font-size:15px;" 
   data-id="{{ $wpqc->id }}" 
   data-line="{{ $wpqc->line }}">
</a>
<a href="#" class="btn btn-danger btn-sm fa fa-trash delete-work"
   data-toggle="modal"
   data-target="#notideletework"
   data-id="{{ $wpqc->id }}"
   data-line="{{ $wpqc->line }}">
</a>


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
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="GroupEm"><b>จัดกลุ่มพนักงาน</b></h3>
                </div>
                <div class="container-fluid">
                    <div class="col-md-5">
                        <div class="text-center">
                        <h4 class="text-center"><b><u>รายชื่อพนักงาน</u></b></h4>
<div class="panel panel-default">
    <!-- รายชื่อพนักงาน -->
    <div id="emplist" class="panel-body" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: start; padding: 10px;">
        @foreach($employees as $employee)
            <div 
                style="color: black; font-size: 14px; padding: 5px 10px; border-radius: 3px; border: 1px solid #ddd; box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.2); cursor: grab;"
                draggable="true"
                ondragstart="drag(event)"
                data-name="{{ $employee->name }}" 
                id="employee-{{ $loop->index }}">
                {{ $employee->name }}
            </div>
        @endforeach
    </div>
</div>

                            </div>
                        </div>
                        <div class="col-md-1">
                            <br>
                            <br>
                            <a class="btn btn-success btn-sm" 
    style="background-color: #00b5ad; border-color: #00b5ad; color: #fff; font-size:13px;" 
    id="addempgroup" 
    href="#" 
    role="button">
    <span class="glyphicon glyphicon-plus"></span>
</a>

                        </div>
                        <form id="formgroupemp" class="form-inline form-sm mt-0" method="post" action="{{ route('saveEmpGroup', ['line' => $line]) }}">
    @csrf 
                            <div class="col-md-6">
                                <div id="empgroupadded" class="text-center">
                                    <h4><b><u>กลุ่ม</u></b><br></h4>
                                </div>
                                <div class="text-right">
                                <button id="removegroup" class="btn btn-warning btn-sm" type="button" name="button">
        <span class="fas fa-redo-alt"></span>&nbsp;ทำใหม่
    </button>                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-success fas fa-save" type="submit" name="button">บันทึก</button>
                        </div>
                    </form> 

                    <div class="container-fluid">
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
                    <input 
    type="checkbox" 
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



<!-- จบ process modal ของจัดกลุ่มพนักงาน -->
<!-- รายการงานคัดบอร์ดที่ผ่านมา -->
        <div class="modal fade" id="notiallworked" tabindex="-1" role="dialog" aria-labelledby="AllWorked" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="AllWorked"><b>รายการงานคัดบอร์ดที่ผ่านมา</b></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="table-responsive">
                            <table id="workedtable" class="table table-striped table-bordered display">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">ชื่อไฟล์</th>
                                        {{-- <th class="text-center">ชนิดสินค้า</th> --}}
                                        <th class="text-center">วันที่</th>
                                        <th class="text-center"><em class="fa fa-cog"></em></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                        <tr>
                                            <td class="text-center"></td>
                                        <!--
                                            @ if ($worked->wwt_status == '0')
                                                <td style="color:green;" class="text-center"><b>กำลังคัด</b></td>
                                            @ else
                                                <td style="color:red;" class="text-center"><b>จบกะทำงาน</b></td>
                                            @ endif
                                        -->
                                            <td class="text-center">PQC </td> <!-- //$workdetail->value('ww_group') }}(ครั้ง) ----$workpgroup   $workpgrouplot = $workdetail->value('ww_group');-->
                                            {{-- <td class="text-center"></td> --}}
                                            <td class="text-center"></td>
                                            <td class="text-center">
                                                <a href="" class="btn btn-success btn-sm fas fa-file-import" data-toggle="tooltip" title="เข้าสู่งาน" style="font-size:15px;"></a>
                                            </td>
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
<!-- จบ process modal รายการงานคัดบอร์ดที่ผ่านมา -->
<!-- สรุปข้อมูลต่อวัน -->
        <div class="modal fade" id="notiwipperday" tabindex="-1" role="dialog" aria-labelledby="Wipperday" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="Wipperday"><b>สรุปข้อมูลต่อวัน</b></h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="table-responsive">
                        @php
    // จัดกลุ่มข้อมูลตามวันที่ และนับจำนวนรายการ
    $groupedData = $workProcessQC->groupBy('date')->map(function ($items) {
        return count($items);
    });
@endphp

<table id="wipperdaytable" class="table table-striped table-bordered display">
    <thead>
        <tr>
            <th class="text-center">วันที่</th>
            <th class="text-center">จำนวน</th>
        </tr>
    </thead>
    <tbody>
        @foreach($groupedData as $date => $count)
        <tr>
            <td class="text-center">{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</td> {{-- แสดงวันที่ --}}
            <td class="text-center">{{ $wpqc->total_wip_amount ?? 0 }}</td> {{-- แสดงจำนวนของรายการในวันนั้น --}}
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
<!-- จบ process modal สรุปข้อมูลต่อวัน -->
        <div class="modal fade" id="endworktimenoti" tabindex="-1" role="dialog" aria-labelledby="Endworktime" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title" id="Endworktime"><b>จบกะการทำงาน</b></h3>
                        <p style="color:red;font-size:15px;">เมื่อกดยืนยัน ข้อมูลจะถูกบันทึกและเป็นการ<u>จบกะการทำงาน</u> ข้อมูลทั้งหมดจะไม่สามารถแก้ไขได้ โปรดตรวจสอบข้อมูลให้เรียบร้อยก่อนกดยืนยัน</p>
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
                            </div>
                            <div class="col-md-3 col-xs-3">
                                <h4 class="text-center"></h4>
                            </div>
                            <div class="col-md-3 col-xs-3">
                                <h4 class="text-center"></h4>
                            </div>
                            <div class="col-md-3 col-xs-3">
                                <h4 class="text-center"></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <form id="endworktimeform" class="md-form">
                    <div class="text-center">
                        <h4><b><u>ใส่จำนวน END TAPE</u></b></h4>
                        <input style="width:30%;font-size:25px;" class="text-center" id="endtape" step='0.0001' type="number" name="wz_amount" value="" placeholder="ใส่จำนวน END TAPE" min="1"required>
                        <input type="hidden" name="wwd_amount" value="">
                    </div>
                    
                    <div class="modal-footer">
                            <input type="hidden" name="wwt_status" value="1">
                            <button type="submit" class="btn btn-success" name="button">ยืนยัน</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                    </div>
                </form>

                    </div>
                </div>
        </div>
        

        <script type="text/javascript">
            var line = '';
            var hiddeninput = '<input type="hidden" name="eg_line[]" value=""><input type="hidden" name="eg_division[]" value="QC"><input type="hidden" name="eg_emp_id_1[]" value=""><input type="hidden" name="eg_emp_id_2[]" value=""><input type="hidden" name="eg_status[]" value="1">';
            var hiddenempline = '<input type="hidden" name="ue_line[]" value="">';
        </script>
@endsection
