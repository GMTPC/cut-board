@extends('layouts.app')

@section('content')
@include('modelwarehouse')

<div id="app">
    <lodingspinner></lodingspinner>
</div>
<style>
.bg-darkteal {
    background-color: #0E766D !important; /* ใช้สีเขียวอมฟ้าตามภาพ */
    color: white !important;
}
.bg-tomato {
    background-color: #F05039 !important; /* สีแดงตามภาพ */
    color: white !important;
}
.text-white {
    color: white !important;
}

    </style>
    <div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <h2 class="header-select"><b>เลือกคลังสินค้า</b></h2>
                <p class="text-danger">เรียนแจ้งผู้ใช้ระบบทุกท่าน : เพื่อการทำงานที่สมบูรณ์ กรุณาใช้ google chrome ในการใช้ระบบ</p>
            </br>
            <div class="text-left">
                <a href="{{ route('mainmenu') }}" class="btn btn-warning"  name="button"><em class="text-white fas fa-th"><b>  กลับไปยังเมนูหลัก</b></em></a>
            </div>
            <div class="container-fluid" style="width:90%;">
                <h2 class="header-select"><b>นครสวรรค์</b></h2>
                <div class="row">
                    <div class="col-lg-4 text-white">
                        <!-- small box -->
                        <a data-target="#so_search" data-toggle="modal">
                            <div class="small-box bg-aqua card-shadow">
                                <div class="inner">
                                    <br>
                                    <h2 class="text-center text-so-white" >แปรรหัสออกของในประเทศ</h2>
                                    <p class="text-so-white text-center">เลือกข้อมูลใบจองรหัส SO</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-dolly" style='font-size:70px;'></i>
                                </div>
                                <a data-target="#so_search" data-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </a>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4">
                        <!-- small box -->
                        <a data-target="#se_search" data-toggle="modal">
                            <div class="small-box bg-green card-shadow">
                                <div class="inner">
                                    <br>
                                    <h2 class="text-center text-so-white" >แปรรหัสออกของต่างประเทศ</h2>
                                    <p class="text-center text-so-white">เลือกข้อมูลใบจองรหัส SE</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-globe-asia" style='font-size:70px;'></i>
                                </div>
                                <a data-target="#se_search" data-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 text-white">
                        <!-- small box -->
                        <a data-target="#rntobp_search" data-toggle="modal"> {{--  --}}
                            <div class="small-box bg-yellow card-shadow">
                                <div class="inner">
                                    <br>
                                    <h2 class="text-center text-so-white">โอนย้ายไปยังบางพลี</h2>
                                    
                                    <p class="text-so-white text-center">จากนครสวรรค์ไปบางพลี</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-truck" style='font-size:60px;'></i>
                                </div>
                                <a data-target="#rntobp_search" data-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 text-white">
                        <!-- small box -->
                        <a data-target="#rntotoa_search" data-toggle="modal">
                            <div class="small-box bg-darkteal card-shadow">
                                <div class="inner">
                                    <br>
                                    <h2 class="text-center text-so-white">โอนย้ายไปยัง TOA(นครสวรรค์)</h2>
                                    <p class="text-so-white text-center">จากนครสวรรค์ไป TOA</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-truck" style='font-size:60px;'></i>
                                </div>
                                <a data-target="#rntotoa_search" data-toggle="modal" daata-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </a>
                    </div>
                    <!-- ./col -->

                    <div class="col-lg-4 text-white">
                        <!-- small box -->
                        <a data-target="#fcnw_search" data-toggle="modal"> {{--  --}}
                            <div class="small-box bg-tomato card-shadow">
                                <div class="inner">
                                    <br>
                                    <h2 class="text-center text-so-white">งานรับของ(นครสวรรค์)</h2>
                                    <p class="text-so-white text-center">งานรับของนครสวรรค์</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-boxes" style='font-size:60px;'></i>
                                </div>
                                <a data-target="#fcnw_search" data-toggle="modal" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-4 text-white">
                        <!-- small box -->
                        <a href="{{ route('checkcsvtobplus') }}">
                            <div class="small-box bg-purple card-shadow">
                                <div class="inner">
                                    <br>
                                    <h2 class="text-center text-white">ตรวจสอบสินค้าเข้าสู่ระบบ</h2>
                                    <p class="text-white text-center">ตรวจสอบสินค้าและเพื่อนำเข้าสู่ระบบ</p>
                                    </div>
                                <div class="icon">
                                    <i class="fa fa-check-square-o" style='font-size:60px;'></i>
                                </div>
                                <a href="{{ route('checkcsvtobplus') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-4">
    <!-- small box -->
    <a href="">
    <div class="small-box bg-green card-shadow text-white">
        <div class="inner">
            <br>
            <h2 class="text-center text-white">โฟล์คลิฟท์รับเข้าและย้ายสินค้า</h2>
            <p class="text-center text-white">สแกนบาร์โค้ด เพื่อระบุจุดจัดเก็บ</p>
        </div>
        <div class="icon">
            <i class="fas fa-dolly icon-shadow"></i> <!-- ใช้ class ใหม่ -->
        </div>
        <a href="" class="small-box-footer text-white">More info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</a>




                    -- <div class="col-lg-4 text-white">
                        <!-- small box - ->
                        <a href="" > 
                            <div class="small-box bg-yellow card-shadow">
                                <div class="inner">
                                    <br>
                                    <h3 class="text-center text-so-white"">โฟล์คลิฟท์ย้ายสินค้า</h3>
                                    <p class="text-so-white text-center">สแกนบาร์โค้ด เพื่อระบุจุดย้าย</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-truck" style='font-size:60px;'></i>
                                </div>
                                <a href=""  class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </a>
                    </div> --}}


                    
                </div>
            </div>
        </div>
    </div>
</div>

 ./col -->
<div class="modal fade" id="so_search">
    <div class="modal-dialog modal-lg" style="width:90%; height:100%;">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">ค้นหาใบจองภายในประเทศ</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p class="text-danger">สามารถค้นหาด้วยเลขที่ใบจอง หรือชื่อลูกค้าได้ในช่องค้นหา</p>
            </div>

            <!-- Modal body -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered display" id="sotable">
                        <thead>
                            <tr class="text-table-so">
                                <th class="text-center">#</th>
                                <th class="text-center">เลขที่ใบจอง</th>
                                <th class="text-center">ชื่อลูกค้า</th>
                                <th class="text-center"><em class="fa fa-cog"></em></th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-center"><a href=""></a></td>
                                    <td class="text-center"></td>
                                    <td aligr="center">
                                    <a href="" class="btn btn-default btn-sm"><em class="fa fa-file-text" style="font-size:18px;" data-toggle="tooltip" data-placement="left" title="ดูข้อมูล"></em></a>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
              
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>









@endsection

