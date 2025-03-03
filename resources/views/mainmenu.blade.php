@extends('layouts.app')

@section('content')
<style>
   .small-box {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .small-box:hover {
        transform: scale(1.05); /* ขยายการ์ดเล็กน้อย */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
    }

    .small-box .inner p, 
    .small-box .inner h3 {
        color: white !important; /* คงข้อความเป็นสีขาว */
    }
    .modal .small-box .inner h3,
    .modal .small-box .inner p {
        color: white !important;
    }
    .modal .small-box .inner:hover h3,
    .modal .small-box .inner:hover p {
        color: white !important;
    }
   
</style>

<div class="box box-success">
    <div class="box-header with-border">
        <p class="box-title" style="font-size:23px; color:black;">เมนูหลัก</p><br><br>
        <u style="font-size:14px;" class="text-danger">เรียนแจ้งผู้ใช้ระบบทุกท่าน : เพื่อการทำงานที่สมบูรณ์ กรุณาใช้ google chrome ในการใช้ระบบ</u>
    </div>
</div>

<div class="box box-success">
    <div class="box-header with-border">
        <p class="box-title" style="font-size:18px; color:black;">เกี่ยวข้องงานคัดบอร์ด</p>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <a data-target="#selectwh" data-toggle="modal">
                    <div class="small-box bg-blue card-shadow">
                        <div class="inner">
                            <br>
                            <p class="text-center"><b>ระบบสนับสนุนงานคลังสินค้า</b></p>
                            <p class="text-center">การออกของในประเทศ โอนย้ายในประเทศ และออกของนอกประเทศ</p>
                        </div>
                        <div class="icon">
                            <i class='fas fa-warehouse' style='font-size:55px;'></i>
                        </div>
                        <a data-target="#selectwh" data-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </a>
            </div>

            <div class="col-sm-4">
                <a data-toggle="modal" data-target="#selectline">
                    <div class="small-box bg-blue card-shadow">
                        <div class="inner">
                            <br>
                            <p class="text-center"><b>ระบบสนับสนุนงาน QC / FN</b></p>
                            <p class="text-center">การคัดแยกสินค้าเพื่อแปลงรหัสเป็น FG</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clipboard-check" style='font-size:60px;'></i>
                        </div>
                        <a data-toggle="modal" data-target="#selectline" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </a>
            </div>

            <div class="col-sm-4">
                <a href="">
                    <div class="small-box bg-blue card-shadow">
                        <div class="inner">
                            <br>
                            <p class="text-center"><b>ระบบจัดการการย้ายสินค้า</b></p>
                            <p class="text-center">ย้ายสินค้า และตรวจสอบจุดจัดเก็บสินค้า</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-home" style='font-size:60px;'></i>
                        </div>
                        <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="box box-success">
    <div class="box-header with-border">
        <p class="box-title" style="font-size:18px; color:black;">เกี่ยวข้องงานติดตั้ง</p>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <a href="">
                    <div class="small-box bg-aqua card-shadow">
                        <div class="inner">
                            <br>
                            <p class="text-center"><b>ระบบจัดการงานพฤกษา</b></p>
                            <p class="text-center">ระบบจัดงานในส่วนไฟล์ และ แบบบ้าน</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-home" style='font-size:60px;'></i>
                        </div>
                        <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </a>
            </div>

            <div class="col-sm-4">
                <a href="">
                    <div class="small-box bg-aqua card-shadow">
                        <div class="inner">
                            <br>
                            <p class="text-center"><b>ระบบติดตั้ง</b></p>
                            <p class="text-center">การสั่งของออกไฟล์และรายละเอียดโครงการทั้งหมด</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-home" style='font-size:60px;'></i>
                        </div>
                        <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="box box-success">
    <div class="box-header with-border">
        <p class="box-title" style="font-size:18px; color:black;">เกี่ยวข้องงานปฏิทิน</p>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <a data-target="#modalhomeselectwl" data-toggle="modal">
                    <div class="small-box bg-blue card-shadow">
                        <div class="inner">
                            <br>
                            <p class="text-center"><b>ปฏิทินแสดงรายการจัดส่ง</b></p>
                            <p class="text-center">แสดงรายการเอกสาร ตามวันที่นั้นๆ ในรูปแบบของปฏิทิน</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar" style='font-size:60px;'></i>
                        </div>
                        <a data-target="#modalhomeselectwl" data-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="box box-success">
    <div class="box-header with-border">
        <p class="box-title" style="font-size:18px; color:black;">งานอื่นๆ</p>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <a href="">
                    <div class="small-box bg-green card-shadow">
                        <div class="inner">
                            <br>
                            <p class="text-center"><b>Report Bplus</b></p>
                            <p class="text-center">รายงานต่างๆ</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-alt" style='font-size:60px;'></i>
                        </div>
                        <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </a>
            </div>

            <div class="col-sm-4">
                <a data-target="#selectposition" data-toggle="modal">
                    <div class="small-box bg-green card-shadow">
                        <div class="inner">
                            <br>
                            <p class="text-center"><b>ระบบเบิกใช้ของโรงงาน</b></p>
                            <p class="text-center">ระบบขอเบิกใช้ภายในโรงงาน เช่น ใบขอโอนย้าย ใบขอเบิก ใบขอซื้อ</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-alt" style='font-size:60px;'></i>
                        </div>
                        <a data-target="#selectposition" data-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </a>
            </div>

            <div class="col-sm-4">
                <a href="">
                    <div class="small-box bg-green card-shadow">
                        <div class="inner">
                            <br>
                            <p class="text-center"><b>คู่มือการใช้งานระบบ</b></p>
                            <p class="text-center">คู่มือการใช้งานระบบทั้งหมด</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-book" style='font-size:60px;'></i>
                        </div>
                        <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- งานคลัง ส่วน modal -->
<div class="modal fade" id="selectwh">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header งานคลัง -->
            <div class="modal-header">
                <h3 class="modal-title">เลือกคลังสินค้า</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p class="text-danger">เลือกคลังสินค้าตามส่วนงานที่ทำ</p>
            </div>

            <!-- Modal body -->
            <div class="panel-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4 col-xs-12 text-white">
                            <a href="{{ route('warehouse.nk') }}">
                                <div class="small-box bg-orange card-shadow">
                                    <div class="inner">
                                        <br>
                                        <h3 class="text-center" style="font-size:1.5vw;">นครสวรรค์</h3>
                                        <p class="text-center">การทำงานในส่วนของคลังนครสวรรค์</p>
                                    </div>
                                    <a href="{{ route('warehouse.nk') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-xs-12">
                            <a href="">
                                <div class="small-box bg-purple card-shadow">
                                    <div class="inner">
                                        <br>
                                        <h3 class="text-center" style="font-size:1.5vw;">บางพลี</h3>
                                        <p class="text-center">การทำงานในส่วนของคลังบางพลี</p>
                                    </div>
                                    <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-xs-12">
                            <a href="">
                                <div class="small-box bg-green card-shadow">
                                    <div class="inner">
                                        <br>
                                        <h3 class="text-center" style="font-size:1.5vw;">บางใหญ่</h3>
                                        <p class="text-center">การทำงานในส่วนของคลังบางใหญ่</p>
                                    </div>
                                    <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
<!-- จบ process งานคลัง ส่วน modal -->

<!-- งาน QC/FN ส่วน modal -->
<div class="modal fade" id="selectline">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h3 class="modal-title">เลือกไลน์ผลิต</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p class="text-danger">เลือกไลน์ตามความเป็นจริง เพื่อข้อมูลที่ถูกต้อง</p>
            </div>

            <!-- Modal body -->
            <div class="panel-body">
                <div class="container-fluid" style="width:90%;">
                    <div class="row">
                        <div class="col-lg-4 col-xs-4 text-white">
                            <a href="{{ route('manufacture', ['line' => 1]) }}">
                                <div class="small-box bg-green card-shadow">
                                    <div class="inner">
                                        <br>
                                        <h3 class="text-center" style="font-size:1.2vw;">ไลน์ 1</h3>
                                        <p class="text-center">Line 1</p>
                                    </div>
                                    <a href="{{ route('manufacture', ['line' => 1]) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-xs-4">
                            <a href="{{ route('manufacture', ['line' => 2]) }}">
                                <div class="small-box bg-yellow card-shadow">
                                    <div class="inner">
                                        <br>
                                        <h3 class="text-center" style="font-size:1.2vw;">ไลน์ 2</h3>
                                        <p class="text-center">Line 2</p>
                                    </div>
                                    <a href="{{ route('manufacture', ['line' => 2]) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-xs-4">
                            <a href="{{ route('manufacture', ['line' => 3]) }}">
                                <div class="small-box bg-blue card-shadow">
                                    <div class="inner">
                                        <br>
                                        <h3 class="text-center" style="font-size:1.2vw;">ไลน์ 3</h3>
                                        <p class="text-center">Line 3</p>
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
<!-- จบ process งาน QC/FN ส่วน modal -->

<!-- ปฏิทิน ส่วน modal --> 
<div class="modal fade" id="modalhomeselectwl">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h3 class="modal-title">เลือกคลังสินค้า</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p class="text-danger">เลือกข้อมูลตามความเป็นจริง เพื่อข้อมูลที่ถูกต้อง</p>
            </div>

            <!-- Modal body -->
            <div class="panel-body">
                <div class="container-fluid" style="width:90%;">
                    <div class="row">
                        <div class="col-lg-6 col-xs-6 text-white">
                            <div class="small-box bg-purple card-shadow">
                                <div class="inner">
                                    <br>
                                    <h3 class="text-center" style="font-size:1.2vw;">นครสวรรค์</h3>
                                    <p class="text-center">แสดงในส่วนงานนครสวรรค์</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-6">
                            <div class="small-box bg-purple card-shadow">
                                <div class="inner">
                                    <br>
                                    <h3 class="text-center" style="font-size:1.2vw;">บางพลี</h3>
                                    <p class="text-center">แสดงในส่วนงานบางพลี</p>
                                </div>
                            </div>
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

<!-- จบ process ปฏิทิน ส่วน modal -->
@include('frontend.selectposition')

@endsection
