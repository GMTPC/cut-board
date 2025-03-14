@extends('layouts.app')

@section('content')
<style>
    .center-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh; /* ให้ใช้เต็มหน้าจอ */
        padding-top: 50px;  /* ดันให้ลงมาข้างล่าง */
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".btn-navigate-datawip").forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault(); // ป้องกันการเปลี่ยนหน้าโดยตรง

            let workprocessId = this.getAttribute("data-id");
            let line = this.getAttribute("data-line");

            // ใช้ line ตรง ๆ โดยไม่ต้องเพิ่ม 'L'
            let url = `/production/datawip/${line}/${workprocessId}`;
            
            console.log("Navigating to:", url); // แสดงใน Console เพื่อ debug
            window.location.href = url; // เปลี่ยนเส้นทางไปยัง route ใหม่
        });
    });
});
</script>
<script>
$(document).ready(function() {
    $("#btn-manufacture").click(function(event) {
        event.preventDefault(); // ป้องกันการเปลี่ยนหน้าโดยตรง

        // ใช้ AJAX ดึงค่า line จาก Controller
        $.ajax({
            url: "{{ route('getLine') }}", // เรียก Route getLine
            type: "GET",
            success: function(response) {
                if (response.error) {
                    console.error("Error:", response.error);
                    return;
                }

                let line = response.line; // รับค่า line จาก Controller

                // Redirect ไปยัง Route manufacture
                let url = "{{ route('manufacture', ['line' => 'REPLACE_LINE']) }}".replace('REPLACE_LINE', line);
                
                console.log("Navigating to:", url);
                window.location.href = url; // เปลี่ยนหน้าไปยัง manufacture
            },
            error: function(xhr) {
                console.error("Error:", xhr.responseText);
            }
        });
    });
});
</script>
<script>
function openPopup(url) {
    window.open(url, "popupWindow", "width=800,height=600,scrollbars=yes");
}
</script>

    <div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                    <a href="{{ route('mainmenu')}}" class="btn btn-warning"  name="button"><em class="text-white fa fa-home"><b>  กลับไปยังเมนูหลัก</b></em></a>
                    <a href="{{ route('manufacture', ['line' => $cleanLine]) }}" class="btn btn-default">
    <em class="text-white fa fa-arrow-left"><b> งานคัดบอร์ด</b></em>
</a>


                </div>
                <h2><b>ระบบ QC (คัดบอร์ด) :</b></h2>
                <h3><b><u>รายการงานคัดบอร์ด</u></b></h3>
                <div class="text-center">
                <a href="{{ route('dowloadcsvendtime', ['line' => $line, 'wwt_id' => $wwt_id]) }}"
   class="btn btn-success">
   บันทึก CSV
</a>
<a href="javascript:void(0);" 
   class="btn btn-warning" 
   onclick="openPopup('{{ route('tagc', ['line' => $line, 'wwt_id' => $wwt_id]) }}')">
   พิมพ์ TAG แผ่นเสีย
</a>


                </div>
                <div class="table-responsive">
                <table class="table table-striped table-bordered display" id="worktable">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">กลุ่ม</th>
            <th class="text-center">ชนิดสินค้า</th>
            <th class="text-center">สถานะ</th>
            <th class="text-center">วันที่</th>
            <th class="text-center"><em class="fa fa-cog"></em></th>
        </tr>
    </thead>
    <tbody>
    @if($wipWorkingData->isEmpty())
        <tr>
            <td colspan="6" class="text-center">No data available</td>
        </tr>
    @else
        @foreach($wipWorkingData as $index => $wip)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $wip->ww_group }}</td>
                <td class="text-center">{{ $wip->pe_type_name ?? '-' }}</td>
                <td class="text-center">
                    <b style="color: {{ $wip->ww_status == 'กำลังคัด' ? 'green' : 'red' }};">
                        {{ $wip->ww_status == 'กำลังคัด' ? 'กำลังคัด' : 'จบการทำงาน' }}
                    </b>
                </td>
                <td class="text-center">{{ date('d-m-Y', strtotime($wip->ww_end_date)) }}</td>
                <td class="text-center">
                    <a href="#" 
                        class="btn btn-success btn-sm fas fa-file-import btn-navigate-datawip"
                        data-id="{{ $wip->ww_id }}"
                        data-line="{{ $line }}"
                        data-toggle="tooltip" title="เข้าสู่งาน">
                    </a>
                </td>
            </tr>
        @endforeach
    @endif
</tbody>

</table>






                </div>
            </div>
        </div>
    </div>
    @endsection

