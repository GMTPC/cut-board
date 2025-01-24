<?php

namespace App\Http\Controllers;
use App\Models\AmountNg;  // ✅ นำเข้า Model ที่ถูกต้อง
use Illuminate\Http\Request;
use App\Models\GroupQC;
use Illuminate\Support\Facades\DB; // นำเข้า DB class
use App\Models\WorkprocessQC; // ตรวจสอบว่าโมเดลนี้มีอยู่
use App\Models\Employee;
use App\Models\GroupEmp; // Import Model GroupEmp
use App\Models\Wipbarcode;
use App\Models\Listngall;
use App\Models\ProductTypeEmp;
use App\Models\BrandList;
use App\Models\WipWorktime;
use Illuminate\Support\Facades\Log;
use App\Models\WipWorking;
use App\Models\Brand;

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
        if ($currentDate->hour < 8) {
            $currentDate->subDay(); // ลบ 1 วัน
        }
    }

    // ใช้วันที่และเวลาปัจจุบันหรือปรับตามเงื่อนไข
    $dateForWork = $currentDate->format('Y-m-d H:i:s'); // วันที่พร้อมเวลา
    $lotDate = $currentDate->format('Y-m-d') . ' 00:00:00'; // วันที่เวลา 00:00:00

    // บันทึกข้อมูลลงใน work_process_qc
    $workprocess = WorkProcessQC::create([
        'line' => $request->input('ww_line'),
        'group' => $group,
        'date' => $dateForWork,
        'status' => 'กำลังคัด',
    ]);

    $line = $request->input('ww_line'); // Line
    $id = $workprocess->id; // ID จาก work_process_qc
    $wwGroupFormatted = $line . $group;
    $wwWwtIndex = DB::table('wip_working')->max('ww_wwt_index') + 1;

    // เปิดใช้งาน IDENTITY_INSERT

    DB::table('wip_working')->insert([
        'ww_id' => $id, // ระบุค่า id ที่ต้องการ
        'ww_line' => 'L' . $line,
        'ww_group' => $wwGroupFormatted,
        'ww_division' => 'QC',
        'ww_start_date' => $dateForWork,
        'ww_lot_date' => $lotDate,
        'ww_status' => 'W',
        'ww_wwt_index' => $wwWwtIndex,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    
    // เพิ่ม Log หรือกระบวนการอื่น
    Log::info("Data for line $line and group $group has been saved successfully.");

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

    // ดึงข้อมูล `wip_working` ที่เกี่ยวข้อง
    $workdetail = WipWorking::where('ww_id', $id)->first();

    // ตรวจสอบว่ามีข้อมูลใน WipWorking หรือไม่
    if (!$workdetail) {
        abort(404, 'ไม่พบข้อมูลในตาราง wip_working');
    }

    // ดึงข้อมูลจาก `wip_working`
    $workpgroup = $workdetail->ww_group;
    $workstatus = $workdetail->ww_status;
    $workdate = $workdetail->ww_lot_date;
    $enddate = $workdetail->ww_end_date;
    $workline = $workdetail->ww_line;

    // คำนวณ Lot Check
    $lotcheck = Brand::leftJoin('brandlist', 'brands.brd_brandlist_id', '=', 'brandlist.bl_id')
        ->leftJoin('wip_working', 'brands.brd_working_id', '=', 'wip_working.ww_id')
        ->where('wip_working.ww_group', '=', $workpgroup)
        ->where('wip_working.ww_line', '=', $workline)
        ->where('wip_working.ww_division', '=', 'QC')
        ->whereDate('wip_working.ww_lot_date', '=', $workdate)
        ->count();

    // สร้าง Lot Generator
    $lotgenerator = date('ymd', strtotime($workdate)) . substr($workpgroup, 1, 1) . str_pad($lotcheck + 1, 3, 0, STR_PAD_LEFT);

    // ดึงข้อมูลผู้คัดเฉพาะ line และ status = 1
    $empGroups = GroupEmp::where('line', $line)
                         ->where('status', 1)
                         ->get();

    // ดึงข้อมูลบาร์โค้ดที่เกี่ยวข้องกับ workprocess โดยใช้ Relation
    $wipBarcodes = $workprocess->wipBarcodes()->with('groupEmp')->get();

    // คำนวณผลรวมของ wip_amount จาก Relation
    $totalWipAmount = $workprocess->wipBarcodes()->sum('wip_amount');

    // ดึงข้อมูลทั้งหมดจากตาราง listngall ที่มี lng_status = 1
    $listNgAll = Listngall::where('lng_status', 1)->get();

    // ดึงข้อมูล ProductTypeEmp ที่ pe_working_id ตรงกับ $id
    $productTypes = ProductTypeEmp::where('pe_working_id', $id)->get();

    // ดึงผลรวม amg_amount จาก AmountNg ที่ amg_wip_id ตรงกับ wip_id
    $totalNgAmount = AmountNg::whereIn('amg_wip_id', $wipBarcodes->pluck('wip_id'))->sum('amg_amount');

    // ดึงข้อมูลแบรนด์จากตาราง brandlist
    $brandLists = BrandList::select('bl_id', 'bl_name')->get();

    // ดึงข้อมูล wip_sku_name โดยตรงจาก wipbarcodes
    $wipSkuNames = Wipbarcode::where('wip_working_id', $id)
                             ->pluck('wip_sku_name');
$brandsLots = Brand::where('brd_working_id', $id)->pluck('brd_lot');

    // ส่งข้อมูลไปยัง View
    return view('datawip', [
        'workprocess'       => $workprocess,
        'line'              => $line,
        'empGroups'         => $empGroups,
        'work_id'           => $id,
        'wipBarcodes'       => $wipBarcodes,
        'totalWipAmount'    => $totalWipAmount,
        'listNgAll'         => $listNgAll,
        'productTypes'      => $productTypes,
        'totalNgAmount'     => $totalNgAmount,
        'brandLists'        => $brandLists,
        'wipSkuNames'       => $wipSkuNames,
        'lotgenerator'      => $lotgenerator,
        'brandsLots'        => $brandsLots, // ส่งข้อมูล `brd_lot` ไปยัง View

    ]);
}


    
    public function BrandLis()
    {
        $brandLists = BrandList::select('bl_id', 'bl_name')->get();
        return view('frontend.selectbrand', compact('brandLists'));
    }
    
    
    }
    
    
    
   

  

    


    


