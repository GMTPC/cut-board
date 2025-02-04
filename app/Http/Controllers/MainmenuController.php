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

public function datawip($line, $id, $brd_id = null)
{
    // ค้นหา WorkProcess ตาม id และ line
    $workprocess = WorkProcessQC::where('id', $id)
                                ->where('line', $line)
                                ->firstOrFail();

    // ดึงข้อมูล `wip_working` ที่เกี่ยวข้อง
    $workdetail = WipWorking::findOrFail($id);

    // ข้อมูลที่เกี่ยวข้องกับ `wip_working`
    $workpgroup = $workdetail->ww_group;
    $workstatus = $workdetail->ww_status;
    $workdate = $workdetail->ww_lot_date;
    $workline = $workdetail->ww_line;

    // คำนวณจำนวน lot check
    $lotcheck = Brand::leftJoin('brandlist', 'brands.brd_brandlist_id', '=', 'brandlist.bl_id')
        ->leftJoin('wip_working', 'brands.brd_working_id', '=', 'wip_working.ww_id')
        ->where('wip_working.ww_group', $workpgroup)
        ->where('wip_working.ww_line', $workline)
        ->where('wip_working.ww_division', 'QC')
        ->whereDate('wip_working.ww_lot_date', $workdate)
        ->count();

    // สร้าง Lot Generator
    $lotgenerator = date('ymd', strtotime($workdate)) . substr($workpgroup, 1, 1) . str_pad($lotcheck + 1, 3, '0', STR_PAD_LEFT);

    // ดึงข้อมูลพนักงานใน group ที่ line และ status = 1
    $empGroups = GroupEmp::where('line', $line)
                         ->where('status', 1)
                         ->get();

    // ดึงข้อมูลบาร์โค้ดที่เกี่ยวข้องกับ workprocess
    $wipBarcodes = $workprocess->wipBarcodes()->with('groupEmp')->get();

    // คำนวณผลรวม wip_amount จาก Relation
    $totalWipAmount = $workprocess->wipBarcodes()->sum('wip_amount');

    // ดึงข้อมูล listngall ที่สถานะ `lng_status` = 1
    $listNgAll = Listngall::where('lng_status', 1)->get();

    // ✅ ดึงข้อมูล ProductTypeEmp ที่ pe_working_id ตรงกับ id ของ WorkProcessQC
    $productTypes = ProductTypeEmp::where('pe_working_id', $workprocess->id)->get();
    
    // ✅ ดึงค่า pe_type_name และ pe_type_code
    $peTypeName = $productTypes->isNotEmpty() ? $productTypes->first()->pe_type_name : null;
    $peTypeCode = $productTypes->isNotEmpty() ? $productTypes->first()->pe_type_code : null;

    // ดึงผลรวม amg_amount จาก AmountNg
    $totalNgAmount = AmountNg::whereIn('amg_wip_id', $wipBarcodes->pluck('wip_id'))->sum('amg_amount');

    // ดึงข้อมูลแบรนด์จากตาราง brandlist
    $brandLists = BrandList::select('bl_id', 'bl_name')->get();

    // ดึงชื่อ SKU ที่เกี่ยวข้องจาก wip_barcode
    $wipSkuNames = Wipbarcode::where('wip_working_id', $id)->pluck('wip_sku_name');

    // ดึง lot ที่เกี่ยวข้องกับ brands ทั้งหมด
    $brandsLots = Brand::where('brd_working_id', $id)
                        ->select('brd_id', 'brd_lot', 'brd_amount', 'brd_outfg_date', 'brd_brandlist_id') // ✅ เพิ่ม `brd_brandlist_id`
                        ->get();

    // ✅ ตรวจสอบว่ามี `$brd_id` หรือไม่
    $lot = $brd_id 
        ? Brand::where('brd_id', $brd_id)->select('brd_id', 'brd_lot', 'brd_amount', 'brd_outfg_date', 'brd_brandlist_id')->first()
        : $brandsLots->first();

    // **เพิ่มเงื่อนไขป้องกัน ERROR ถ้า `$lot` เป็น `null`**
    $brd_lot = $lot ? $lot->brd_lot : null;
    $brd_brandlist_id = $lot ? $lot->brd_brandlist_id : null; // ✅ เพิ่มตัวแปร brd_brandlist_id

    // ✅ ดึงข้อมูล `bl_code` ตาม `brd_id` ที่ถูกเลือก
    $brand = $lot 
        ? Brand::where('brd_id', $lot->brd_id)->first()
        : Brand::where('brd_working_id', $id)->first();

    $brandList = $brand 
        ? BrandList::where('bl_id', $brand->brd_brandlist_id)->first()
        : null;

    $brdAmount = $brand ? $brand->brd_amount : null;

    // ดึงผลรวมของ `brd_amount` ที่ `brd_working_id` ตรงกับ `$id`
    $totalBrdAmount = Brand::where('brd_working_id', $id)->sum('brd_amount');

    // ✅ ส่งข้อมูลไปยัง View
    return view('datawip', [
        'workprocess'       => $workprocess,
        'line'              => $line,
        'empGroups'         => $empGroups,
        'work_id'           => $id,
        'wipBarcodes'       => $wipBarcodes,
        'totalWipAmount'    => $totalWipAmount,
        'listNgAll'         => $listNgAll,
        'productTypes'      => $productTypes, // ✅ เพิ่ม `$productTypes`
        'peTypeCode'        => $peTypeCode,   // ✅ แก้ไขตัวแปร `$peTypeCode`
        'peTypeName'        => $peTypeName,   // ✅ เพิ่ม `$peTypeName`
        'totalNgAmount'     => $totalNgAmount,
        'brandLists'        => $brandLists,
        'wipSkuNames'       => $wipSkuNames,
        'lotgenerator'      => $lotgenerator,
        'brandsLots'        => $brandsLots,
        'workdetail'        => $workdetail,
        'brandList'         => $brandList,
        'brdAmount'         => $totalBrdAmount, // ✅ ใช้ค่าผลรวมแทน
        'lot'               => $lot,
        'brd_lot'           => $brd_lot,
        'brd_brandlist_id'  => $brd_brandlist_id,
    ]);
}












    
   
    
    
    }
    
    
    
   

  

    


    


