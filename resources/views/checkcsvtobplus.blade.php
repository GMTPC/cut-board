@extends('layouts.app')

@section('content')
    <div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                    <a href="{{ route('mainmenu') }}" class="btn btn-warning"  name="button"><em class="text-white fa fa-th"><b>  กลับไปก่อนหน้า</b></em></a>
                </div>
                <h2><b>ระบบตรวจสอบข้อมูลสินค้าเข้าสู่ระบบ</b></h2>
                <div class="container-fluid">
                    <form id="checkcsvform" class="form-inline md-form form-sm mt-0 text-center" method="post">
                        <input id="ccw_barcode" style="width:30%;" class="text-center" type="text" name="ccw_barcode" placeholder="สแกนบาร์โค้ด" value=""> <button type="submit" name="button"><i style="font-size:25px;" class="fa fa-barcode"></i> </button>
                    </form>
                    <div class="row">
                        <div class="col-md-6">
                           
                            <div class="text-left">
                                <a class="btn btn-success fa fa-save" data-target="#noticsvindex" data-toggle="modal">&nbsp;&nbsp;&nbsp;ออก CSV</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-right">
                                <a class="btn btn-default fa fa-file" data-target="#noticsvallfile" data-toggle="modal">&nbsp;&nbsp;&nbsp;ไฟล์ที่บันทึกแล้ว</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-gmt">
                        <div class="panel-heading text-center" style="font-size:18px;">รายการยิงรับเข้า</div>
                        <div class="panel-body" style="
                        padding-top: 0px;
                        padding-left: 0px;
                        ">
                        {{-- <div class="col-md-1 col-xs-1">
                            <h4 class="text-center">#</h4>
                        </div> --}}
                        <div class="col-md-5 col-xs-5">
                            <h4 class="text-center">บาร์โค้ด</h4>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <h4 class="text-center">Lot</h4>
                        </div>
                        <div class="col-md-2 col-xs-2">
                            <h4 class="text-center">จำนวน</h4>
                        </div>
                        <div class="col-md-1 col-xs-1">
                            <h4 class="text-center"><i class="fa fa-cog"></i></h4>
                        </div>
                    </div>
                    <div class="panel-body" style="
                    padding-top: 0px;
                    padding-left: 0px;
                    ">
                    <div id="csvdetailrealtime">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- submit savne-->
<div class="modal fade" id="noticsvindex" tabindex="-1" role="dialog" aria-labelledby="CsvIndex" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="CsvIndex"><b>ยืนยันข้อมูล</b></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="csvindexform" method="post">
                <div class="modal-body">
                    <h4 class="text-center">ยืนยันการบันทึกข้อมูลเพื่อออก CSV</h4>
                    <input type="hidden" name="cswi_index" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-success">ยืนยัน</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="noticsvallfile">
    <div class="modal-dialog modal-lg" style="width:80%; height:100%;">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h3 class="modal-title">ไฟล์ที่บันทึกแล้ว</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p class="text-danger">สามารถค้นหาด้วยชื่อได้ที่ช่องค้นหา</p>
            </div>

            <!-- Modal body -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered display" id="csvallfiletable">
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
                                    <td class="text-center">
                                    </td>
                                    <td class="text-center"></td>
                                    <td class="text-center"><a class="btn btn-default btn-sm" href="" data-toggle="tooltip" title="ดูข้อมูล"><i style="font-size:17px;" class="fa fa-file-text"></i></a></td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteccwbarcode" tabindex="-1" role="dialog" aria-labelledby="DeleteCCW" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="DeleteCCW"><b>ลบข้อมูล </b> </h3>
            </div>
            <form id="deleteccwform">
                <div class="modal-body">
                    <h4><b>ต้องการลบข้อมูล</b> <u style="color:red;"><b id="ccwbarcodeheader"></b></u> <b>หรือไม่</b></h4>
                    <input type="hidden" id="ccw_id_hiden" name="ccw_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-success">ยืนยัน</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
