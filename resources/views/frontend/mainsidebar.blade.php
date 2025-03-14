<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Menu </li>
            <!-- Optionally, you can add icons to the links -->

            <li><a href="{{ route('mainmenu') }}"><i class="glyphicon glyphicon-th"></i> <span>เมนูหลัก</span></a></li>
            <li><a href=""><i class="fa fa-book"></i> <span>คู่มือการใช้งาน</span></a></li>
            <li class="treeview">
                <a href="#"><i class="fas fa-warehouse"></i> <span>&nbsp;&nbsp;&nbsp;คลังสินค้า</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a class="fa fa-calendar" data-target="#selectwl" data-toggle="modal">&nbsp;ปฏิทินรายการจัดส่ง</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fas fa-clipboard-check"></i> <span>&nbsp;&nbsp;&nbsp;QC \ FN</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a class="fas fa-tachometer-alt" href="">&nbsp;รายงานผู้คัด</a></li>
                    <li><a class="fas fa-truck-loading" href="">&nbsp;WPS</a></li>
                    <li><a class="fa fa-fw fa-sort-numeric-desc" href="">&nbsp;คัดบอร์ดเปลี่ยนสเตตัส</a></li>
                    {{-- <li><a class="fas fa-camera" href="">&nbsp;Pruksa รูปงานช่าง</a></li> --}}
                    <li><a class="fas fa-certificate" href="{{ route('addbrandslist') }}">&nbsp;รายการแบรนด์</a></li>
                    <li><a class="fas fa-file-alt" href="{{ route('addlistng') }}">&nbsp;รายการของเสีย</a></li>
                    <li><a class="fa fa-adjust" href="}">&nbsp;สีวันที่ผลิต</a></li>
                    <li><a class="fa fa-adjust" href="{">&nbsp;สีชนิดสินค้า</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="fas fa-clipboard-check"></i> <span>&nbsp;&nbsp;&nbsp;Pruksa</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a class="fas fa-camera" href="">&nbsp;Pruksa รูปงานช่าง</a></li>
                </ul>
            </li>
            
            <li></br></li>
            <li class="treeview">
                <a href="#"><i class="fa fa-cogs"></i> <span>การตั้งค่า</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <!-- error
                <ul class="treeview-menu">
                    <li><a class="fa fa-user-circle " href="">  การตั้งค่าผู้ใช้งาน</a></li>
                    <li><a class="fa fa-desktop" href="#">  การตั้งค่าเว็บไซต์</a></li>
                </ul>
                -->
            </li>
            {{-- @if (Auth::user()->name == 'Manager' || Auth::user()->name == 'arnut' || Auth::user()->name == 'r')
            <li class="treeview">
                <a href="#"><i class="fas fa-user-cog"></i><span>&nbsp;&nbsp;&nbsp;จัดการรายชื่อพนักงงาน</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a class="fas fa-user-plus" href="{{ route('adduser') }}">&nbsp;เพิ่มผู้ใช้งาน</a></li> 
                    <li><a class="fas fa-users" href="{{ route('adjustuser') }}">&nbsp;รายชื่อผู้ใช้งาน</a></li>

                </ul>
            </li>
             @endif --}}
        </ul>

    </section>
    <!-- /.sidebar -->
</aside>

<div class="modal fade" id="selectwl">
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
                            <!-- small box -->
                            <a href="">
                                <div class="small-box bg-green card-shadow">
                                    <div class="inner">
                                        <br>
                                        <h3 class="text-center text-so-white" style="font-size:1.2vw;">นครสวรรค์</h3>
                                        <p class="text-so-white text-center">แสดงในส่วนงานนครสวรรค์</p>
                                    </div>
                                    <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <a href="">
                                <div class="small-box bg-yellow card-shadow">
                                    <div class="inner">
                                        <br>
                                        <h3 class="text-center text-so-white" style="font-size:1.2vw;">บางพลี</h3>
                                        <p class="text-center text-so-white">แสดงในส่วนงานบางพลี</p>
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
