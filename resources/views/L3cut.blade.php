@extends('dashboard')

@section('title', 'ระบบ QC')

@section('content')
<div class="qc-container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">

    <!-- Tabs -->
    <div style="display: flex; justify-content: space-between; gap: 20px; margin-bottom: 20px;">
        <a href="#" class="tab-item active" style="
            flex: 1; 
            text-align: center; 
            padding: 15px; 
            background-color: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 10px; 
            transition: transform 0.3s, background-color 0.3s;">
            ข้อมูลเข้า (WIP) และ ข้อมูลออก (FG)
        </a>
        <a href="#" class="tab-item" style="
            flex: 1; 
            text-align: center; 
            padding: 15px; 
            background-color: #0056b3; 
            color: white; 
            text-decoration: none; 
            border-radius: 10px; 
            transition: transform 0.3s, background-color 0.3s;">
            จัดกลุ่มพนักงาน
        </a>
        <a href="#" class="tab-item" style="
            flex: 1; 
            text-align: center; 
            padding: 15px; 
            background-color: #ff9800; 
            color: white; 
            text-decoration: none; 
            border-radius: 10px; 
            transition: transform 0.3s, background-color 0.3s;">
            ข้อมูลรายงานเข้าแบบละเอียด
        </a>
    </div>

    <!-- Header -->
    <div style="border: 2px solid #007bff; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
    @if($latestWork)
            <p>
                กลุ่มที่คัด : <strong>{{ $latestWork->line }}{{ $latestWork->group }}</strong>
            </p>
            <p>วันที่เริ่ม : <strong>{{ \Carbon\Carbon::parse($latestWork->date)->format('d-m-Y') }}</strong></p>
            <p>สถานะ : <strong style="color: green;">{{ $latestWork->status }}</strong></p>
        @else
            <p>ยังไม่มีข้อมูลการคัดล่าสุด</p>
        @endif
    </div>

    <!-- Summary -->
    <div style="display: flex; gap: 20px; justify-content: space-between; margin-bottom: 20px;">
        <div class="summary-box" style="flex: 1; text-align: center; border: 1px solid #007bff; border-radius: 5px; padding: 10px; transition: transform 0.3s;">
            <strong>จำนวนแผ่นเข้า</strong><br>0
        </div>
        <div class="summary-box" style="flex: 1; text-align: center; border: 1px solid #007bff; border-radius: 5px; padding: 10px; transition: transform 0.3s;">
            <strong>จำนวนแผ่นออก</strong><br>0
        </div>
        <div class="summary-box" style="flex: 1; text-align: center; border: 1px solid #007bff; border-radius: 5px; padding: 10px; transition: transform 0.3s;">
            <strong>คงค้าง (HD)</strong><br>0
        </div>
        <div class="summary-box" style="flex: 1; text-align: center; border: 1px solid #007bff; border-radius: 5px; padding: 10px; transition: transform 0.3s;">
            <strong>เสีย (NG)</strong><br>0
        </div>
    </div>
 <!-- Input Section -->
 <div style="display: flex; gap: 20px; justify-content: center; margin-bottom: 20px;">
        <div>
            <label for="barcode" style="font-weight: bold; color: #007bff;">กรอกบาร์โค้ด WIP:</label>
            <input type="text" id="barcode" name="barcode" placeholder="กรอกบาร์โค้ด WIP" style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 200px;">
        </div>
        <div>
            <label for="selector" style="font-weight: bold; color: #007bff;">เลือกผู้คัด:</label>
            <select id="selector" name="selector" style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 200px;">
                <option value="" disabled selected>กรุณาเลือกผู้คัด</option>
                <option value="user1">ผู้คัด 1</option>
                <option value="user2">ผู้คัด 2</option>
                <option value="user3">ผู้คัด 3</option>
            </select>
        </div>
    </div>

    <!-- WIP Table -->
    <h4 style="color: #007bff; text-align: center; margin-bottom: 10px;">ข้อมูลเข้า (WIP)</h4>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="background-color: #007bff; color: white; padding: 10px;">#</th>
                <th style="background-color: #007bff; color: white; padding: 10px;">มาร์โค้ด</th>
                <th style="background-color: #007bff; color: white; padding: 10px;">ผู้คัด</th>
                <th style="background-color: #007bff; color: white; padding: 10px;">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <tr class="table-row">
                <td style="padding: 10px; text-align: center;">1</td>
                <td style="padding: 10px; text-align: center;">มาร์โค้ด</td>
                <td style="padding: 10px; text-align: center;">ผู้คัด</td>
                <td style="padding: 10px; text-align: center;">-</td>
            </tr>
        </tbody>
    </table>

    <!-- FG Table -->
    <h4 style="color: #007bff; text-align: center; margin-bottom: 10px;">ข้อมูลออก (FG)</h4>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="background-color: #007bff; color: white; padding: 10px;">#</th>
                <th style="background-color: #007bff; color: white; padding: 10px;">LOT FG</th>
                <th style="background-color: #007bff; color: white; padding: 10px;">จำนวน</th>
                <th style="background-color: #007bff; color: white; padding: 10px;">OUT FG CODE</th>
            </tr>
        </thead>
        <tbody>
            <tr class="table-row">
                <td style="padding: 10px; text-align: center;">1</td>
                <td style="padding: 10px; text-align: center;">LOT FG</td>
                <td style="padding: 10px; text-align: center;">จำนวน</td>
                <td style="padding: 10px; text-align: center;">OUT FG CODE</td>
            </tr>
        </tbody>
    </table>
     <!-- ปุ่มข้อมูลออก FG -->
     <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
        <button onclick="exportFgData()" style="
            padding: 10px 20px; 
            background-color: #007bff; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: background-color 0.3s, transform 0.3s;
        ">
            ข้อมูลออก FG
        </button>
    </div>
</div> 
</div>

<style>
    /* Animation for Tabs */
    .tab-item {
        display: inline-block;
        font-weight: bold;
    }

    .tab-item:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        background-color: #004080; /* สีเมื่อ Hover */
        color: #fff;
    }

    .tab-item.active {
        background-color: #007bff;
        color: #fff;
    }

    /* Animation for Summary Boxes */
    .summary-box:hover {
        transform: translateY(-5px);
        background-color: #007bff;
        color: white;
    }
</style>
@endsection
