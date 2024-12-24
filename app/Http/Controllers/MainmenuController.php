<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupQC;
use Illuminate\Support\Facades\DB; // นำเข้า DB class
use App\Models\WorkprocessQC; // ตรวจสอบว่าโมเดลนี้มีอยู่
use App\Models\Employee;

class MainmenuController extends Controller
{
    public function mainmenu()
    {
        return view('mainmenu'); // ตรวจสอบว่ามีไฟล์ `resources/views/mainmenu.blade.php`
    }
    
    public function manufacture($line = null)
{
    // ดึงข้อมูลกลุ่มเฉพาะไลน์ที่เลือก
    if ($line) {
        $groups = GroupQC::where('line', $line)->pluck('group'); // ดึงเฉพาะชื่อกลุ่ม
        $lineheader = 'Line ' . $line; // ตั้งชื่อหัวข้อ
        return view('manufacture', compact('groups', 'lineheader', 'line'));
    } else {
        $groups = GroupQC::pluck('group'); // ดึงกลุ่มทั้งหมด
        $lineheader = 'All Lines'; // ตั้งชื่อหัวข้อ
        return view('manufacture', compact('groups', 'lineheader'));
    }
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

    public function manufacture2()
    {
        $groupLines = DB::table('groupQC')
        ->where('line', '2') // เงื่อนไขสำหรับ line 1
        ->get();
        $employees = Employee::where('line', '2')->get(); // แสดงเฉพาะพนักงานในไลน์ 3

        return view('manufacture2', compact('groupLines','employees')); // ส่งตัวแปร $groupLines ไปยัง View
    }
    public function manufacture3()
    {
        // ดึงข้อมูลเฉพาะไลน์ 3
        $groupLines = DB::table('groupQC')
            ->where('line', '3')
            ->get();
    
        $employees = Employee::where('line', '3')->get(); // แสดงเฉพาะพนักงานในไลน์ 3
    
        return view('manufacture3', compact('groupLines', 'employees'));
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
    

    


    public function line3cut()
{
    // ดึงข้อมูลทั้งหมดจากตาราง groupQC
    $groupLines = GroupQC::all();

    // ดึงข้อมูลล่าสุดจากตาราง WorkProcessQC
    $latestWork = WorkProcessQC::orderBy('created_at', 'desc')->first();

    // ส่งข้อมูลไปยัง View
    return view('L3cut', compact('groupLines', 'latestWork'));
}

public function warehousenk()
{
   
    return view('warehouseNK');
}
public function warehouseby()
{
   
    return view('warehouseBY');
}
public function warehousebp()
{
   
    return view('warehouseBP');
}
}
