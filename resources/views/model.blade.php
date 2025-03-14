<!-- modals.blade.php -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Modal: notiwipperday -->


<!-- Modal: notiallworked -->
<div class="modal fade" id="notiallworked" tabindex="-1" role="dialog" aria-labelledby="AllWorked" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="AllWorked"><b>รายการงานคัดบอร์ดที่ผ่านมา</b></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="workedtable" class="table table-striped table-bordered display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">ชื่อไฟล์</th>
                                <th class="text-center">วันที่</th>
                                <th class="text-center"><em class="fa fa-cog"></em></th>
                            </tr>
                        </thead>
                        <tbody>
                        @isset($worked)
    @foreach($worked as $index => $work)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">PQC{{ date('dmYHi', strtotime($work->wwt_date)) }}</td>
            <td class="text-center">{{ date('d-m-Y H:i', strtotime($work->wwt_date)) }}</td>
            <td class="text-center">
    <a href="{{ route('workedprevious', ['line' => 'L' . $line, 'wwt_id' => $work->wwt_id]) }}" 
       class="btn btn-success btn-sm fas fa-file-import" 
       data-toggle="tooltip" title="เข้าสู่งาน" style="font-size:15px;">
    </a>
</td>

        </tr>
        @endforeach
@endisset
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

<!-- Modal: endworktimenoti -->
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
                    @php
    $totalWIP = 0;
    $totalFG  = 0;
    $totalNG  = 0;
    $totalHD  = 0;
@endphp

@isset($workProcessQC)
    @foreach($workProcessQC as $wpqc)
    @php
        $totalWIP += $wpqc->sumwipendtime;
        $totalFG  += $wpqc->sumfgendtime;
        $totalNG  += $wpqc->sumngendtime;
        $totalHD  += $wpqc->sumhdendtime;
    @endphp
    @endforeach
@endisset

<div class="col-md-3 col-xs-3">
    <h4 class="text-center">{{ $totalWIP }}</h4>
</div>
<div class="col-md-3 col-xs-3">
    <h4 class="text-center">{{ $totalFG }}</h4>
</div>
<div class="col-md-3 col-xs-3">
    <h4 class="text-center">{{ $totalHD }}</h4>
</div>
<div class="col-md-3 col-xs-3">
    <h4 class="text-center">{{ $totalNG }}</h4>
</div>




                        </div>
                    </div>
                </div>
            </div>
            <form id="endworktimeform" class="md-form" method="POST" action="{{ route('endworktime', ['line' => $line]) }}">
    @csrf  <!-- 🔹 ต้องมี CSRF Token -->
    <input type="hidden" id="line" name="line" value="{{ $line }}">
    <input type="hidden" id="ww_ids_input" name="ww_ids">

    <div class="text-center">
        <h4><b><u>ใส่จำนวน END TAPE</u></b></h4>
        <input style="width:30%;font-size:25px;" class="text-center" id="endtape" step="0.0001" type="number" name="wz_amount" placeholder="ใส่จำนวน END TAPE" min="1" required>
        <input type="hidden" name="wwd_amount" id="wwd_amount" value="{{ $totalNG }}">
        <input type="hidden" name="wwt_status" value="1">
    </div>
    
    <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="button">ยืนยัน</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
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
    @if(isset($groupedData) && !empty($groupedData))
    @foreach($groupedData as $data)
            <tr>
                <td class="text-center">{{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') }}</td>
                <td class="text-center">{{ number_format($data->total_wip_amount, 2) }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="2" class="text-center">ไม่มีข้อมูล WIP</td>
        </tr>
    @endif
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