@extends('layouts.app')

@section('content')

    <div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                    <a href="{{ route('main') }}" class="btn btn-warning"  name="button"><em class="text-white fa fa-home"><b>  กลับไปยังเมนูหลัก</b></em></a>
                </div>
                <div class="container-fluid">
                    <h2><b>รายการสีชนิดสินค้า </b></h2>
                    @if (Auth::user()->name == 'Manager' || Auth::user()->name == 'arnut')
                        <a class="btn btn-success btn-md fa fa-plus" data-target="#notiaddnglist" data-toggle="modal">  เพิ่มรายการ</a>
                        <table id="pdcolortable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">สีชนิดสินค้า</th>
                                    <th class="text-center">หมายเหตุ</th>
                                    <th class="text-center">สถานะเปิดใช้งาน</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detail as $detail)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $detail->pcs_color }}</td>
                                        <td class="text-center">{{ $detail->pcs_remark }}</td>
                                        <td class="text-center"></td>
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

@endsection
