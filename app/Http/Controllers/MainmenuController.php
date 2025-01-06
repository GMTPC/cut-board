<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupQC;
use Illuminate\Support\Facades\DB; // นำเข้า DB class
use App\Models\WorkprocessQC; // ตรวจสอบว่าโมเดลนี้มีอยู่
use App\Models\Employee;
use App\Models\GroupEmp; // Import Model GroupEmp

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
    // ตรวจสอบว่าค่า line และ group ถูกส่งมาหรือไม่
    if (!$request->has('ww_line') || !$request->has('ww_group')) {
        return back()->withErrors('Line and Group are required.');
    }

    // บันทึกข้อมูลลงในฐานข้อมูล
    $workprocess = WorkProcessQC::create([
        'line' => $request->input('ww_line'),
        'group' => $request->input('ww_group'),
        'date' => $request->input('ww_lot_date'),
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
    // ค้นหา WorkProcess ตาม id
    $workprocess = WorkProcessQC::find($id);

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if (!$workprocess) {
        abort(404, 'ไม่พบข้อมูล');
    }

    // ตรวจสอบว่า line ที่ส่งมาตรงกับ line ในฐานข้อมูลหรือไม่
    if ($workprocess->line !== $line) {
        abort(404, 'ข้อมูล Line ไม่ถูกต้อง');
    }

    // ส่งข้อมูลไปยัง View
    return view('datawip', [
        'workprocess' => $workprocess,
        'line' => $line, // ส่ง line ไปด้วย
    ]);
}

    
   

    public function startWork(Request $request)
{
    $validated = $request->validate([
        'group' => 'required|string',
        'line' => 'required|string',
        'date' => 'required|date',
    ]);

    WorkProcessQC::create([
        'group' => $validated['group'],
        'line' => $validated['line'],
        'date' => $validated['date'],
        'status' => 'กำลังคัด',
    ]);

    // เก็บข้อความสำเร็จใน Session
    return redirect()->route('line3cut')->with('success', 'เริ่มงานใหม่สำเร็จ!');
    
    }
    

    


    

}
