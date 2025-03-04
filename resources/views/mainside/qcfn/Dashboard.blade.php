@extends('layouts.app')

@section('content')

    <div class="container-fluid bg-white">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-left">
                    <a href="{{ route('main') }}" class="btn btn-warning"  name="button"><em class="text-white fa fa-home"><b>  กลับไปยังเมนูหลัก</b></em></a>
                </div>
                <br>
                @if (Auth::user()->name == 'Manager' || Auth::user()->name == 'arnut' || Auth::user()->name == 'r' || Auth::user()->name == 'adminemployees')

        <h2><b>สรุปรายการผู้คัด</b></h2>
        <ul class="nav nav-tabs">
          <li class="active tab-size-md"><a href="#empFG"><h4>FG</h4></a></li>
          <li class="tab-size-md"><a href="#empWIP"><h4 class="conseorso">WIP</h4></a></li>
      </ul>
      <br>
      <div class="tab-content">
      <div id="empFG" class="tab-pane fade in active">
  
        {{-- <a href="{{ route('csvemployeegroup') }}" ><button class="btn btn-warning btn-sm" type="submit" name="filter"  id="filter" "><b>บันทึก CSV FG</b>&nbsp;&nbsp;<i class="fas fa-file-import"></i></button> </a> --}}
        <h4><p class="text-danger">หมายเหตุ : ค้นหาวันก่อน Export to Excel </p></h4>

<br />
            {{-- <table class="table table-hover table-striped  border: 2px solid brown" id="dashboardtable">     --}}
            <table id="dashboardtable" class="display nowrap" style="width:100%">   
        <thead >
          <tr>
            <th scope="col">#</th>
            <th scope="col">LOT FG</th>
            <th scope="col">Out FG code</th>
            <th scope="col">แบร์น</th>
            <th scope="col">ประเภทแผ่น</th>
            <th scope="col">วันที่</th>
            <th scope="col">ไลน์</th>
            <th scope="col">กะคัด</th>
            <th scope="col">จำนวน</th>
            <th scope="col">ผู้คัด</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($boardFG as $boardFG)     
          <tr>
          <th scope="row">{{$countFG++}}</th>
            <td>{{ $boardFG->brd_lot }} </td>
            <td>BX{{ $boardFG->bl_code }}-{{ $boardFG->pe_type_code }}{{ $boardFG->brd_lot }}{{ $boardFG->brd_amount }}</td> 
            <td>{{ $boardFG->bl_name }}</td> 
            <td>{{ $boardFG->pe_type_code }}</td>
            <td>{{ substr($boardFG->brd_outfg_date,0,10)}}</td>
            <td>{{ $boardFG->ww_line }}</td>
            <td>{{ substr($boardFG->brd_lot,6,1)}}</td>
            <td>{{ $boardFG->brd_amount }}</td>
            <td>{{ $boardFG->name1 }} - {{ $boardFG->name2 }}</td>
          </tr>
          @endforeach 
        </tbody>
        
        </tbody>
      </table>  
      </div>


        

      <div id="empWIP" class="tab-pane fade">
       
    {{-- <a href="{{ route('csvemployeegroupWIP') }}" ><button class="btn btn-warning btn-sm" type="submit" name="filter"  id="filter" "><b>บันทึก CSV WIP</b>&nbsp;&nbsp;<i class="fas fa-file-import"></i></button> </a> --}}
    <h4><p class="text-danger">หมายเหตุ : ค้นหาวันก่อน Export to Excel</p></h4>

<br> <br>

  {{-- <table class="table table-hover table-striped  border: 2px solid brown" id="dashboardtablewip"> --}}
  <table id="dashboardtablewip" class="display nowrap" style="width:100%">
        <thead >
          <tr>
            <th scope="col">#</th>
            <th scope="col">LOT WIP</th>
            <th scope="col">Out WIP code</th>
            <th scope="col">แบร์น</th>
            <th scope="col">ชนิดสินค้า</th>
            <th scope="col">ประเภทแผ่น</th>
            <th scope="col">วันที่</th>
            <th scope="col">ไลน์กะคัด</th>
            <th scope="col">จำนวน</th>
            <th scope="col">ผู้คัด</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($boardWIP as $boardWIP)
          <tr>
            <th scope="row">{{$countWIP++}}</th>
            <td>{{  substr($boardWIP->wip_barcode,11,10) }} </td>
            <td>{{ $boardWIP->wip_barcode }}</td> 
            <td>{{  substr($boardWIP->wip_barcode,2,2) }}</td> 
            <td>{{ $boardWIP->wip_sku_name }}</td>
            <td>{{  substr($boardWIP->wip_barcode,5,6) }}</td>
            <td>{{ substr($boardWIP->ww_start_date,0,10)}}</td>
            <td>{{ $boardWIP->ww_line }}</td>
            <td>{{  substr($boardWIP->wip_barcode,21,3) }}</td>
            <td>{{ $boardWIP->name1 }} - {{ $boardWIP->name2 }}</td>
           </tr>
          @endforeach 
        </tbody>
      </table>
        </div>
      </div>
    @else
    <center>
        <h3 style="color:red;">คุณไม่มีสิทธิ์ใช้งานในส่วนนี้</h3>
    </center>
    @endif
  </div>
  </div>
</div>  
@endsection
