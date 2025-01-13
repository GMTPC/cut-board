<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupQC;
use Illuminate\Support\Facades\DB; // นำเข้า DB class
use App\Models\WorkprocessQC; // ตรวจสอบว่าโมเดลนี้มีอยู่
use App\Models\Employee;
use App\Models\GroupEmp; // Import Model GroupEmp
use App\Models\Wipbarcode;
use App\Models\Listngall;
class MainmenuController extends Controller
{
    public function mainmenu()
    {
        return view('mainmenu'); // ตรวจสอบว่ามีไฟล์ `resources/views/mainmenu.blade.php`
    }
    
    public function manufacture($line = null)
    {
        // ดึงข้อมูลพนักงานเฉพาะไลน์ หรือทั้งหมดถ้า $line เป็น null
        $employees = Employee::when($line, function ($query, $line) {
            return $query->where('line', $line);
        })->get();
    
        // ดึงข้อมูล GroupQC
        $groups = GroupQC::when($line, function ($query, $line) {
            return $query->where('line', $line);
        })->pluck('group'); // ดึงเฉพาะชื่อกลุ่ม
    
        // ตั้งชื่อหัวข้อ
        $lineheader = $line ? 'Line ' . $line : 'All Lines';
    
        // ดึงข้อมูล GroupEmp เฉพาะไลน์ที่ระบุ
        $groupemps = GroupEmp::when($line, function ($query, $line) {
            return $query->where('line', $line);
        })->get();
    
        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if ($groupemps->isEmpty() && $groups->isEmpty() && $employees->isEmpty()) {
            // หากไม่มีข้อมูลเลย อาจต้องส่งข้อความแจ้งเตือนไปยัง View
            $message = 'ไม่พบข้อมูลสำหรับ ' . ($line ? 'Line ' . $line : 'ทุก Line');
        } else {
            $message = null; // ไม่มีข้อความแจ้งเตือน
        }
    
        // ส่งข้อมูลไปยัง view
        return view('manufacture', compact('groups', 'groupemps', 'lineheader', 'employees', 'line', 'message'));
    }
    

    public function workgroup(Request $request)
    {
        // ตรวจสอบค่าจาก Request
        if (!$request->has('ww_line') || !$request->has('ww_group')) {
            return back()->withErrors('Line and Group are required.');
        }
    
        // ตรวจสอบว่าค่า ww_group มีหรือไม่
        $group = $request->input('ww_group');
        if (empty($group)) {
            return back()->withErrors('Group is required.');
        }
    
        // กำหนดวันที่ปัจจุบัน
        $currentDate = now(); // เวลาปัจจุบันในไทย (Asia/Bangkok)
        
        // ตรวจสอบ Group และปรับวันที่ตามเงื่อนไข
        if ($group === 'B') {
            // กะกลางคืน: ตรวจสอบเวลา หากเวลาไม่เกิน 8 โมงเช้า ให้ลบ 1 วัน
            if ($currentDate->hour < 8) {
                $currentDate->subDay(); // ลบ 1 วัน
            }
        }
        // หากเป็น Group A ให้ใช้วันที่ปัจจุบัน
        $dateForWork = $currentDate->format('Y-m-d'); // แปลงวันที่ให้อยู่ในรูปแบบ Y-m-d
    
        // บันทึกข้อมูลลงในฐานข้อมูล
        $workprocess = WorkProcessQC::create([
            'line' => $request->input('ww_line'),
            'group' => $group,
            'date' => $dateForWork, // ใช้วันที่ที่ปรับตามเงื่อนไข
            'status' => 'กำลังคัด',
        ]);
    
        // ดึงค่า $line และ $id
        $line = $request->input('ww_line');
        $id = $workprocess->id;
    
        // เปลี่ยนเส้นทางไปยัง route 'datawip'
        return redirect()->route('datawip', ['line' => $line, 'id' => $id]);
    }
    
    

    public function datawip($line, $id)
{
    // ค้นหา WorkProcess ตาม id และ line ให้ตรงกัน
    $workprocess = WorkProcessQC::where('id', $id)
                                ->where('line', $line)
                                ->first();

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if (!$workprocess) {
        abort(404, 'ไม่พบข้อมูลหรือ Line ไม่ถูกต้อง');
    }

    // ดึงข้อมูลผู้คัดเฉพาะ line และ status = 1
    $empGroups = GroupEmp::where('line', $line)
                         ->where('status', 1)
                         ->get();

    // ดึงข้อมูลบาร์โค้ดที่เกี่ยวข้องกับ workprocess โดยใช้ Relation
    $wipBarcodes = $workprocess->wipBarcodes()->with('groupEmp')->get();

    // คำนวณผลรวมของ wip_amount จาก Relation
    $totalWipAmount = $workprocess->wipBarcodes()->sum('wip_amount');

    // ✅ ดึงข้อมูลทั้งหมดจากตาราง listngall ที่มี lng_status = 1
    $listNgAll = Listngall::where('lng_status', 1)->get();

    // ส่งข้อมูลไปยัง View
    return view('datawip', [
        'workprocess'    => $workprocess,
        'line'           => $line,
        'empGroups'      => $empGroups,
        'work_id'        => $id,
        'wipBarcodes'    => $wipBarcodes,
        'totalWipAmount' => $totalWipAmount,
        'listNgAll'      => $listNgAll,  // ✅ ส่งข้อมูลไปยัง View
    ]);
}

    
    }
    
    
    
   

  

    


    


