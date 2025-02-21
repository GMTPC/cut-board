<!-- modals.blade.php -->

<!-- Modal: notiwipperday -->
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
        @foreach($groupedData as $data)
        <tr>
            <td class="text-center">{{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') }}</td> {{-- ✅ แสดงวันที่ --}}
            <td class="text-center">{{ $data->total_wip_amount ?? 0 }}</td> {{-- ✅ แสดงผลรวม wip_amount --}}
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
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center">PQC </td>
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
        
