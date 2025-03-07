@extends('layouts.app')

@section('content')
    <div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                    <a href="{{ route('mainmenu') }}" class="btn btn-warning"  name="button"><em class="text-white fa fa-th"><b>  {{ trans('billading.backtomain') }}</b></em></a>
                    <a href="{{ route('checkcsvtobplus') }}" class="btn btn-default"  name="button"><em class="text-white fa fa-arrow-left"><b>  กลับไปก่อนหน้า</b></em></a>
                </div>
                <h2><b>รายการสินค้าที่บันทึกแล้ว</b></h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-left">
                            <a href="{{ route('outcheckcsvwh',$no) }}" class="btn btn-success fa fa-save">&nbsp;&nbsp;&nbsp;บันทึก CSV</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-right">
                            <a class="btn btn-default fa fa-file" data-target="#noticsvallfile" data-toggle="modal">&nbsp;&nbsp;&nbsp;ไฟล์ที่บันทึกแล้ว</a>
                        </div>
                    </div>
                </div>
                    <div class="panel panel-gmt">
                        <div class="panel-heading text-center" style="font-size:18px;">แปลรูปสินค้า {{ $no }}</div>
                        <div class="panel-body" style="
                        padding-top: 0px;
                        padding-left: 0px;
                        ">
                        <div class="col-md-6 col-xs-6">
                            <h4 class="text-center">บาร์โค้ด</h4>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <h4 class="text-center">Lot</h4>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <h4 class="text-center">จำนวน</h4>
                        </div>
                    </div>
                    <div class="panel-body" style="
                    padding-top: 0px;
                    padding-left: 0px;
                    ">
                    @foreach ($detailall as $detailall)
                        <div class="col-md-6 col-xs-6">
                            <h4 class="text-center">{{ $detailall->ccw_barcode }}</h4>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <h4 class="text-center">{{ $detailall->ccw_lot }}</h4>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <h4 class="text-center">{{ $detailall->ccw_amount }}</h4>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
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
                            @foreach ($savedfiles as $savedfiles)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">PWH{{ date('dmYHi',strtotime($savedfiles->created_at)+7) }}</td> <!--แปลรูปสินค้า { { $savedfiles->cswi_index }} -->
                                    <td class="text-center">{{ date('d-m-Y H:i',strtotime($savedfiles->created_at)+7) }}</td>
                                    <td class="text-center"><a class="btn btn-default btn-sm" href="{{ route('csvwhsaved',$savedfiles->cswi_index) }}" data-toggle="tooltip" title="ดูข้อมูล"><i style="font-size:17px;" class="fa fa-file-text"></i></a></td>
                                </tr>
                            @endforeach
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
@endsection
