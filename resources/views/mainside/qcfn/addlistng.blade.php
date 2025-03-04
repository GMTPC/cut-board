@extends('layouts.app')

@section('content')

    <div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                    <a href="{{ route('main') }}" class="btn btn-warning"  name="button"><em class="text-white fa fa-home"><b>  กลับไปยังเมนูหลัก</b></em></a>
                </div>
                <div class="container-fluid">
                    <h2><b>รายการของเสีย</b></h2>
                    @if (Auth::user()->name == 'Manager' || Auth::user()->name == 'arnut')
                        <a class="btn btn-success btn-md fa fa-plus" data-target="#notiaddnglist" data-toggle="modal">  เพิ่มรายการ</a>
                        <table id="nglisttable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">ชื่อชนิดของเสีย</th>
                                    <th class="text-center">สถานะเปิดใช้งาน</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nglist as $nglist)
                                    <tr>
                                        <td class="text-center">{{ $count++ }}</td>
                                        <td class="text-center">{{ $nglist->lng_name }}</td>
                                        <td class="text-center">
                                            <input data-id="{{ $nglist->lng_id }}" class="toggle-lngstatus" type="checkbox" netliva-switch data-active-text="เปิด" data-passive-text="ปิด" data-active-color="#40bf40" data-passive-color="#ff4d4d" {{ $nglist->lng_status ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <center>
                            <h3 style="color:red;">คุณไม่มีสิทธิ์ใช้งานในส่วนนี้</h3>
                        </center>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="notiaddnglist" tabindex="-1" role="dialog" aria-labelledby="AddNgList" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="AddNgList">เพิ่มรายการแบรนด์</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form id="addnglistform" class="form-inline md-form form-sm mt-0 text-right" enctype="multipart/form-data" method="post">
                        <div class="modal-body">
                        </div>{{ csrf_field() }}
                        <div class="col-md-12 text-center">
                            <b style="font-size:18px;">ชื่อรายการของเสีย : </b><input style="width:30%;" class="form-group text-center" type="text" name="lng_name" placeholder="ใส่ชื่อรายการของเสีย เช่น ขอบแตก">
                        </div>
                        <br>
                        <br>
                        <br>
                        <center>
                            <button class="btn btn-success" type="submit" name="button">บันทึกข้อมูล   <i class="fas fa-save"></i></button>
                        </center>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>




@endsection
