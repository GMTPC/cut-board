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

    <div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                    <a href="" class="btn btn-warning"  name="button"><em class="text-white fa fa-home"><b>  กลับไปยังเมนูหลัก</b></em></a>
                    <a href="" class="btn btn-default"  name="button"><em class="text-white fa fa-arrow-left"><b>  งานคัดบอร์ด</b></em></a>
                </div>
                <h2><b>ระบบ QC (คัดบอร์ด) :</b></h2>
                <h3><b><u>รายการงานคัดบอร์ด</u></b></h3>
                <div class="text-center">
                <a href="{{ route('dowloadcsvendtime', ['line' => $line, 'wwt_id' => $wwt_id]) }}"
   class="btn btn-success">
   บันทึก CSV
</a>
                    <a href="" class="btn btn-warning " name="button">พิมพ์ TAG แผ่นเสีย</a>
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
        @if($workProcessQC->isEmpty())
            <tr>
                <td colspan="6" class="text-center">No data available</td>
            </tr>
        @else
            @foreach($workProcessQC as $index => $wpqc)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center"> {{ $wpqc->line }}{{ $wpqc->group }}</td>
                    <td class="text-center">{{ $wpqc->pe_type_name ?? '-' }}</td>
                    <td class="text-center">
                        <b style="color: {{ $wpqc->status == 'กำลังคัด' ? 'green' : 'red' }};">
                            {{ $wpqc->status == 'กำลังคัด' ? 'กำลังคัด' : 'จบการทำงาน' }}
                        </b>
                    </td>
                    <td class="text-center">{{ date('d-m-Y H:i', strtotime($wpqc->date)) }}</td>
                    <td class="text-center">
                        <a href="{{ route('workedprevious', ['line' => $line, 'wwt_id' => $wwt_id, 'id' => $wpqc->id]) }}" 
                           class="btn btn-success btn-sm fas fa-file-import"
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

