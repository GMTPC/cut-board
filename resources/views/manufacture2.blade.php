@extends('dashboard')

@section('title', 'ระบบ QC')

@section('content')

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ระบบ QC</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    
    html, body {
        height: 100%;
        margin: 0;
    }

    .container-fluid {
        padding: 20px;
    }

    .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border: 2px solid #007bff; /* เพิ่มกรอบสีน้ำเงิน */
    border-radius: 8px; /* มุมโค้ง */
    padding: 10px; /* เพิ่มพื้นที่ภายใน */
}

    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-bottom: 20px;
    }
    .table-container {
    margin: 20px 0;
    border: 2px solid #28a745; /* เพิ่มกรอบสีเขียว */
    border-radius: 8px;
    padding: 10px;
}

.card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    border: 2px solid #fd7e14; /* เพิ่มกรอบสีส้ม */
    border-radius: 8px;
    padding: 20px;
    justify-content: space-around; /* จัด card ให้เว้นระยะห่าง */
    flex-wrap: wrap; /* ให้ card เลื่อนลงเมื่อมีขนาดเล็ก */
}

    .card {
        flex: 1;
        max-width: 220px;
        min-width: 180px;
        padding: 20px;
        border-radius: 8px;
        color: #fff;
        text-align: center;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    .card h6 {
        margin: 0 0 10px;
    }

    .card.bg-red {
        background-color: #dc3545;
    }

    .card.bg-blue {
        background-color: #007bff;
    }

    .card.bg-orange {
        background-color: #fd7e14;
    }

    .card.bg-green {
        background-color: #28a745;
    }

    .footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #343a40;
    color: white;
    text-align: center;
    padding: 10px 0;
    font-size: 0.9rem;
    box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.1);
}

    .btn {
        font-size: 0.85rem;
        padding: 10px 15px;
    }
    .container-fluid {
    border: 3px solid #007bff; /* กรอบสีน้ำเงิน */
    border-radius: 15px; /* มุมโค้ง */
    padding: 20px; /* เพิ่มพื้นที่ภายใน */
    margin: 20px auto; /* เพิ่มระยะห่างจากขอบหน้าจอ */
    background-color: #f9f9f9; /* พื้นหลังสีอ่อน */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* เงาสำหรับกรอบ */
}
.card.orange-card {
    background-color: white; /* สีพื้นหลังการ์ด */
    color: #007bff; /* สีข้อความ */
    text-align: center;
    width: 90%; /* ขนาดของการ์ด */
    padding: 20px;
    border: 2px solid #007bff; /* เส้นขอบสีฟ้า */
    border-radius: 8px; /* มุมมน */
    text-decoration: none;
    transition: transform 0.2s, box-shadow 0.2s;
    font-weight: bold; /* ทำให้ข้อความดูเด่นขึ้น */
}

.card.orange-card:hover {
    transform: scale(1.03); /* ขยายเมื่อ hover */
    box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.2); /* เพิ่มเงา */
}

.card.orange-card h4 {
    margin: 0; /* ตัดระยะห่างขอบของ h4 */
}

.card.orange-card p {
    margin: 5px 0 0 0; /* เว้นระยะห่างระหว่าง p และ h4 */
    font-size: 0.9rem; /* ลดขนาดข้อความย่อย */
    color: gray; /* เปลี่ยนสีข้อความย่อย */
}

</style>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ระบบ QC</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container-fluid">
    <!-- Header -->
    <div class="header">
        <button class="btn btn-warning"><i class="bi bi-arrow-left"></i> กลับไปยังเมนูหลัก</button>
        <h3 class="text-primary">ระบบ QC (คัดบอร์ด) : ไลน์ 2</h3>
        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalLine">เลือกไลน์ผลิต</button>
    </div>

    <div class="action-buttons" style="display: flex; align-items: center; gap: 10px;">
    <form action="{{ route('workprocess.start') }}" method="POST" style="display: flex; align-items: center; gap: 10px;">
        @csrf
        <!-- Dropdown เลือกกลุ่ม -->
        <div class="dropdown d-inline-block">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                เลือกกลุ่ม
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                @foreach ($groupLines as $groupLine)
                    <li>
                        <a class="dropdown-item" href="#" onclick="setGroup('{{ $groupLine->group }}', '{{ $groupLine->line }}'); event.preventDefault();">
                            Group: {{ $groupLine->group }} | Line: {{ $groupLine->line }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Hidden Inputs -->
        <input type="hidden" id="groupInput" name="group">
        <input type="hidden" id="lineInput" name="line">

        <!-- Date Input -->
        <input 
            type="date" 
            id="dateInput" 
            name="date" 
            class="form-control d-inline-block" 
            style="max-width: 200px;" 
            value="{{ now()->timezone('Asia/Bangkok')->format('Y-m-d') }}" 
            readonly>
        
            <button id="startWorkBtn" class="btn btn-warning" style="white-space: nowrap; padding: 10px 20px; text-align: center;">
    เริ่มงานใหม่ <i class="bi bi-arrow-repeat"></i>
</button>

    </form>
</div>
<script>
    // Set group and line values
    function setGroup(group, line) {
        document.getElementById('groupInput').value = group;
        document.getElementById('lineInput').value = line;
        document.getElementById('dropdownMenuButton').textContent = `Group: ${group} | Line: ${line}`;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const startWorkForm = document.querySelector('form[action="{{ route('workprocess.start') }}"]');
        if (startWorkForm) {
            startWorkForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const group = document.getElementById('groupInput').value;
                const line = document.getElementById('lineInput').value;
                const date = document.getElementById('dateInput').value;

                if (!group || !line) {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'กรุณาเลือกกลุ่มและไลน์ก่อนเริ่มงาน',
                        confirmButtonText: 'ตกลง'
                    });
                    return;
                }

                Swal.fire({
                    icon: 'question',
    title: 'ยืนยันการเริ่มงานใหม่',
    text: 'คุณต้องการเริ่มงานใหม่ใช่หรือไม่?',
    showCancelButton: true,
    confirmButtonText: '<i class="bi bi-check-circle"></i> ใช่',
    cancelButtonText: '<i class="bi bi-x-circle"></i> ยกเลิก',
    buttonsStyling: false,
    customClass: {
        confirmButton: 'btn btn-success btn-lg mx-2', // ปุ่ม "ใช่" สีเขียว ขยายใหญ่ขึ้น และเพิ่มระยะห่าง
        cancelButton: 'btn btn-danger btn-lg mx-2' // ปุ่ม "ยกเลิก" สีแดง ขยายใหญ่ขึ้น และเพิ่มระยะห่าง
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: startWorkForm.action,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                group: group,
                                line: line,
                                date: date
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'สำเร็จ!',
                                    text: response.message || 'เริ่มงานใหม่สำเร็จ!',
                                    confirmButtonText: 'ตกลง',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false
                                }).then(() => {
                                    window.location.href = '{{ route('line3cut') }}';
                                });
                            },
                            error: function (error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: error.responseJSON?.message || 'ไม่สามารถเริ่มงานได้ โปรดลองอีกครั้ง',
                                    confirmButtonText: 'ตกลง'
                                });
                            }
                        });
                    }
                });
            });
        }
    });
</script>
 
<script>
$(document).ready(function() {
    let employeeCount = $('#employeeTableBody tr').length; // เริ่มนับจำนวนข้อมูลจากที่มีอยู่

    // ฟังก์ชันบันทึกข้อมูลพนักงาน
    $('#saveEmployee').click(function() {
        const name = $('#name').val().trim();
        const note = $('#note').val().trim();

        if (name) {
            Swal.fire({
                title: 'กำลังบันทึกข้อมูล...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ route("employees.save.line2") }}', // เส้นทางไปยัง Backend
                type: 'POST',
                data: {
                    name: name,
                    note: note,
                    line: '2', // ล็อกไลน์เป็น 3
                    _token: '{{ csrf_token() }}'
                },
                success: function(employee) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: 'บันทึกข้อมูลพนักงานสำเร็จ!',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        // เพิ่มข้อมูลใหม่ในตาราง
                        employeeCount++;
                        $('#employeeTableBody').append(`
                            <tr id="employee-${employee.id}">
                                <td>${employeeCount}</td>
                                <td>${employee.name}</td>
                                <td>${employee.note ?? ''}</td>
                                <td>
                                    <!-- ปุ่ม Edit -->
                                    <button class="btn btn-warning btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal" 
                                            data-id="${employee.id}" 
                                            data-name="${employee.name}" 
                                            data-note="${employee.note}">
                                        <i class="bi bi-pencil"></i> แก้ไข
                                    </button>
                                    <!-- ปุ่ม Delete -->
                                    <button class="btn btn-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal" 
                                            data-id="${employee.id}">
                                        <i class="bi bi-trash"></i> ลบ
                                    </button>
                                </td>
                            </tr>
                        `);

                        // เคลียร์ค่าในฟอร์ม
                        $('#name').val('');
                        $('#note').val('');
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถบันทึกข้อมูลได้',
                        confirmButtonText: 'ตกลง'
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'คำเตือน',
                text: 'กรุณากรอกชื่อพนักงาน',
                confirmButtonText: 'ตกลง'
            });
        }
    });
});
</script>



<script>
    $('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // ปุ่มที่คลิก
    var id = button.data('id'); // รับ id
    var name = button.data('name'); // รับ name
    var note = button.data('note'); // รับ note
    var modal = $(this);
    
    // กำหนดค่าภายใน modal
    modal.find('#editEmployeeId').val(id);
    modal.find('#editName').val(name);
    modal.find('#editNote').val(note);
});

// ฟังก์ชันบันทึกการแก้ไข
$('#saveEdit').click(function() {
    var id = $('#editEmployeeId').val();
    var name = $('#editName').val();
    var note = $('#editNote').val();

    Swal.fire({
        title: 'กำลังบันทึกข้อมูล...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '/employees/' + id,
        method: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            name: name,
            note: note
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: 'แก้ไขข้อมูลสำเร็จ!',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                location.reload(); // รีเฟรชหน้า
            });
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถแก้ไขข้อมูลได้',
                confirmButtonText: 'ตกลง'
            });
        }
    });
});


    </script>

<script>
        $('#deleteModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // ปุ่มที่คลิก
    var id = button.data('id'); // รับ id
    var modal = $(this);
    
    // กำหนดค่า id ให้กับปุ่มลบ
    modal.find('#confirmDelete').data('id', id);
});

$('#confirmDelete').click(function() {
    var id = $(this).data('id');

    Swal.fire({
        title: 'กำลังลบข้อมูล...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '/employees/' + id,
        method: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}',
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: 'ลบข้อมูลสำเร็จ!',
                confirmButtonText: 'ตกลง'
            }).then(() => {
                location.reload(); // รีเฟรชหน้า
            });
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถลบข้อมูลได้',
                confirmButtonText: 'ตกลง'
            });
        }
    });
});


    </script>

    <script>
        function setGroup(group, line) {
            document.getElementById('groupInput').value = group;
            document.getElementById('lineInput').value = line;
            document.getElementById('dropdownMenuButton').textContent = `Group: ${group} | Line: ${line}`;
        }
    </script>

    <!-- Table Section -->
    <div class="table-container">
        <h4>รายการงานคัดบอร์ดวันนี้</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>กลุ่ม</th>
                    <th>ชนิดสินค้า</th>
                    <th>สถานะ</th>
                    <th>วันที่</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center">No data available in table</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Cards Section -->
    <div class="card-container">
        <a href="#" class="card bg-red">
            <h6>รายการงานที่ผ่านมา</h6>
            <p>More info</p>
        </a>
        <a href="#" class="card bg-primary text-white text-center p-3" data-bs-toggle="modal" data-bs-target="#employeeModal" style="text-decoration: none;">
        <h6>รายชื่อพนักงาน</h6>
            <p>More info</p>
        </a>
        <a href="#" class="card bg-orange">
            <h6>จัดกลุ่มพนักงาน</h6>
            <p>More info</p>
        </a>
        <a href="#" class="card bg-green">
            <h6>สรุปข้อมูลต่อวัน</h6>
            <p>More info</p>
        </a>
    </div>
</div>

  

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
<!-- Modal -->
<div class="modal fade" id="modalLine" tabindex="-1" aria-labelledby="modalWarehouseSupportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalWarehouseSupportLabel">เลือกไลน์ผลิต</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="color: red; text-align: center;">เลือกไลน์ตามความเป็นจริง เพื่อข้อมูลที่ถูกต้อง</p>
                <div class="card-container">
                    <!-- Card สำหรับ Line 1 -->
                    <a href="{{ route('manufacture') }}" class="card orange-card">
                        <h4>ไลน์ 1</h4>
                        <p>Line 1</p>
                    </a>
                    <!-- Card สำหรับ Line 2 -->
                    <a href="{{ route('manufacture2') }}" class="card orange-card">
                        <h4>ไลน์ 2</h4>
                        <p>Line 2</p>
                    </a>
                    <!-- Card สำหรับ Line 3 -->
                    <a href="{{ route('manufacture3') }}" class="card orange-card">
                        <h4>ไลน์ 3</h4>
                        <p>Line 3</p>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeModalLabel">เพิ่มข้อมูลพนักงาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <!-- ฟอร์มกรอกข้อมูล -->
                    <div class="row mb-3">
                    <div class="col-md-6">
    <label for="name" class="form-label fw-bold">ชื่อพนักงาน:</label>
    <input type="text" id="name" class="form-control" placeholder="กรอกชื่อพนักงาน">
</div>

<div class="col-md-6">
    <label for="note" class="form-label fw-bold">หมายเหตุ:</label>
    <div class="input-group">
        <input type="text" id="note" class="form-control" placeholder="กรอกหมายเหตุ (ถ้ามี)">
       
    </div>
</div>

                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="saveEmployee">
                            <i class="bi bi-check-circle"></i> บันทึกข้อมูล
                        </button>
                    </div>

                    <!-- ตารางแสดงข้อมูลใน Modal -->
                    <h5 class="mt-4">รายชื่อพนักงาน</h5>
                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr>
                            <th>#</th>
        <th>ชื่อพนักงาน</th>
        <th>หมายเหตุ</th>
        <!-- เพิ่มคอลัมน์ฟันเฟือง -->
        <th>
            <i class="bi bi-gear"></i> <!-- ไอคอนฟันเฟือง -->
        </th>
    </tr>
</thead>
                        <tbody id="employeeTableBody">
                            <!-- ข้อมูลจะถูกแสดงที่นี่ -->
                            @foreach($employees as $index => $employee)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->note ?? '' }}</td>
                                <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $employee->id }}" data-name="{{ $employee->name }}" data-note="{{ $employee->note }}">
            <i class="bi bi-pencil"></i> แก้ไข
        </button>
        
        <!-- ปุ่ม Delete -->
        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $employee->id }}">
            <i class="bi bi-trash"></i> ลบ
        </button>
        </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal แก้ไข -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">แก้ไขข้อมูลพนักงาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editEmployeeId">
                    <div class="mb-3">
                        <label for="editName" class="form-label">ชื่อพนักงาน</label>
                        <input type="text" class="form-control" id="editName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editNote" class="form-label">หมายเหตุ</label>
                        <input type="text" class="form-control" id="editNote">
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-success" id="saveEdit">บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal ลบ -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">ยืนยันการลบพนักงาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                คุณต้องการลบข้อมูลพนักงานนี้หรือไม่?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">ลบข้อมูล</button>
            </div>
        </div>
    </div>
</div>

