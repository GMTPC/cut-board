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
use App\Models\WipHolding;
use App\Models\WipSummary;
use Carbon\Carbon;
use Illuminate\Support\Facades\App; // ✅ เพิ่มบรรทัดนี้เพื่อแก้ปัญหา



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
    
        // ✅ 1. ดึง id ทั้งหมดที่ line ตรงกันใน workprocess_qc
        $workProcessIDs = WorkProcessQC::where('line', $line)->pluck('id');
    
        // ✅ 2. ค้นหา wip_working_id ใน wipbarcodes โดยใช้ id ที่ได้จาก workprocess_qc
        $totalWipAmount = Wipbarcode::whereIn('wip_working_id', $workProcessIDs)->sum('wip_amount');
    
        // ✅ 3. ดึงข้อมูล WorkProcessQC พร้อมเชื่อม product_type_emps
        $workProcessQC = WorkProcessQC::where('line', $line)
            ->leftJoin('product_type_emps', 'workprocess_qc.id', '=', 'product_type_emps.pe_working_id')
            ->select(
                'workprocess_qc.id',
                'workprocess_qc.line',
                'workprocess_qc.group',
                'workprocess_qc.status',
                'workprocess_qc.date',
                'product_type_emps.pe_type_name',
                \DB::raw("$totalWipAmount as total_wip_amount") // ✅ รวมค่า wip_amount ทั้งหมด
            )
            ->groupBy(
                'workprocess_qc.id',
                'workprocess_qc.line',
                'workprocess_qc.group',
                'workprocess_qc.status',
                'workprocess_qc.date',
                'product_type_emps.pe_type_name'
            )
            ->get();
    
        // ✅ 4. ดึงข้อมูล WorkProcessQC และรวม total_wip_amount ตามวันที่
        $groupedData = WorkProcessQC::where('line', $line)
            ->leftJoin('wipbarcodes', 'workprocess_qc.id', '=', 'wipbarcodes.wip_working_id')
            ->select(
                'workprocess_qc.date',
                \DB::raw('SUM(wipbarcodes.wip_amount) as total_wip_amount') // ✅ รวมค่า wip_amount ตามวันที่
            )
            ->groupBy('workprocess_qc.date') // ✅ จัดกลุ่มตามวันที่
            ->orderBy('workprocess_qc.date', 'asc')
            ->get();
    
        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if ($groupemps->isEmpty() && $groups->isEmpty() && $employees->isEmpty() && $workProcessQC->isEmpty() && $groupedData->isEmpty()) {
            $message = 'ไม่พบข้อมูลสำหรับ ' . ($line ? 'Line ' . $line : 'ทุก Line');
        } else {
            $message = null;
        }
    
        // ส่งข้อมูลไปยัง view
        return view('manufacture', compact('groups', 'groupemps', 'lineheader', 'employees', 'line', 'message', 'workProcessQC', 'groupedData'))
            ->with('model', view('model', compact('groups', 'groupemps', 'lineheader', 'employees', 'line', 'message', 'workProcessQC', 'groupedData')));
    }
    
    public function workgroup(Request $request)
    {
        try {
            // ✅ ตั้งค่าภาษาไทยให้ Carbon
            App::setLocale('th'); // ✅ ตอนนี้ใช้งานได้แน่นอน
            Carbon::setLocale('th');
    
            // ตรวจสอบค่าจาก Request
            if (!$request->has('ww_line') || !$request->has('ww_group')) {
                return response()->json(['success' => false, 'message' => 'Line and Group are required.'], 400);
            }
    
            // ตรวจสอบว่าค่า ww_group มีหรือไม่
            $group = $request->input('ww_group');
            if (empty($group)) {
                return response()->json(['success' => false, 'message' => 'Group is required.'], 400);
            }
    
            // ✅ ตั้งค่าโซนเวลาเป็นประเทศไทย
            $currentDate = Carbon::now('Asia/Bangkok');
    
            // ✅ ตรวจสอบ Group และปรับวันที่ตามเงื่อนไข
            if ($group === 'B' && $currentDate->hour < 8) {
                $currentDate->subDay(); // ลบ 1 วัน ถ้า Group เป็น B และเวลายังไม่ถึง 08:00
            }
    
            // ✅ ใช้วันที่และเวลาปัจจุบัน หรือปรับตามเงื่อนไข
            $dateForWork = $currentDate->format('Y-m-d H:i:s'); // วันที่พร้อมเวลา
            $lotDate = $currentDate->format('Y-m-d') . ' 00:00:00'; // วันที่เริ่มต้น 00:00:00
    
            // ✅ แปลงวันที่เป็น "1 พฤศจิกายน 2568 14:30 น."
            $thaiDate = $currentDate->translatedFormat('j F Y H:i') . ' น.';
    
            // ✅ บันทึกข้อมูลลงใน work_process_qc
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
    
            // ✅ บันทึกข้อมูลลงใน wip_working
            DB::table('wip_working')->insert([
                'ww_id' => $id,
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
    
            // ✅ เพิ่ม Log
            Log::info("Data for line $line and group $group has been saved successfully.");
    
            // ✅ ส่ง JSON Response กลับไปให้ AJAX พร้อม URL สำหรับ redirect
            return response()->json([
                'success' => true,
                'date' => $thaiDate, // ส่งวันที่ไทยกลับไปแสดงใน SweetAlert
                'redirect_url' => route('datawip', ['line' => $line, 'id' => $id]) // ส่ง URL กลับไปให้ AJAX redirect
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    
    


public function datawip($line, $id, $brd_id = null)
{
    // ✅ ค้นหา WorkProcess ตาม id และ line
    $workprocess = WorkProcessQC::where('id', $id)
                                ->where('line', $line)
                                ->firstOrFail();

    // ✅ ดึงข้อมูล wip_working ที่เกี่ยวข้อง
    $workdetail = WipWorking::findOrFail($id);
    $workpgroup = $workdetail->ww_group;
    $workstatus = $workdetail->ww_status;
    $workdate = $workdetail->ww_lot_date;
    $workline = $workdetail->ww_line;

    // ✅ ดึงค่า ww_end_date จาก WipWorking โดยใช้ ww_id
    $wipWorking = WipWorking::where('ww_id', $id)->pluck('ww_end_date');
  // ✅ ดึงค่า ww_end_date ที่ใหม่ที่สุดของ ww_id ที่ตรงกับ $id
$wwEndDate = WipWorking::where('ww_id', $id)
->orderBy('ww_end_date', 'desc')
->value('ww_end_date');


    // ✅ ดึงข้อมูลจาก WipHolding โดยใช้ wh_working_id
    $wipHoldings = WipHolding::where('wh_working_id', $id)
                              ->select('wh_barcode', 'wh_lot')
                              ->get();

    // ✅ คำนวณจำนวน lot check
    $lotcheck = Brand::leftJoin('brandlist', 'brands.brd_brandlist_id', '=', 'brandlist.bl_id')
        ->leftJoin('wip_working', 'brands.brd_working_id', '=', 'wip_working.ww_id')
        ->where('wip_working.ww_group', $workpgroup)
        ->where('wip_working.ww_line', $workline)
        ->where('wip_working.ww_division', 'QC')
        ->whereDate('wip_working.ww_lot_date', $workdate)
        ->count();

    // ✅ สร้าง Lot Generator และ LotHD Generator
    $lotgenerator = date('ymd', strtotime($workdate)) . substr($workpgroup, 1, 1) . str_pad($lotcheck + 1, 3, '0', STR_PAD_LEFT);
    $lothdgenerator = date('ymd', strtotime($workdate)) . substr($workpgroup,1,1) . substr($workpgroup,1,1) . str_pad($lotcheck + 1, 2, '0', STR_PAD_LEFT);

    // ✅ ดึงค่า holding ที่เกี่ยวข้อง
    $holding = WipHolding::where('wh_working_id', $id)->sum('wh_index'); 

    // ✅ สร้าง Barcode ตามเงื่อนไข
    if ($holding < 100 && $holding > 10) {
        $hdbarcode = 'B'.substr($line,1,1).'99-'.$lothdgenerator.'0'.$holding;
    } elseif ($holding < 10) {
        $hdbarcode = 'B'.substr($line,1,1).'99-'.$lothdgenerator.'00'.$holding;
    } else {
        $hdbarcode = 'B'.substr($line,1,1).'99-'.$lothdgenerator.$holding;
    }

    // ✅ ดึงข้อมูลพนักงานในกลุ่มที่ line และ status = 1
    $empGroups = GroupEmp::where('line', $line)
                         ->where('status', 1)
                         ->get();

    // ✅ ดึงข้อมูลบาร์โค้ดที่เกี่ยวข้องกับ workprocess
    $wipBarcodes = $workprocess->wipBarcodes()->with('groupEmp')->get();
    $totalWipAmount = $workprocess->wipBarcodes()->sum('wip_amount');

    // ✅ ดึงข้อมูล listngall ที่ lng_status = 1
    $listNgAll = Listngall::where('lng_status', 1)->get();

    // ✅ ดึงข้อมูล ProductTypeEmp ตาม pe_working_id
    $productTypes = ProductTypeEmp::where('pe_working_id', $workprocess->id)->get();
    $peTypeName = $productTypes->isNotEmpty() ? $productTypes->first()->pe_type_name : null;
    $peTypeCode = $productTypes->isNotEmpty() ? $productTypes->first()->pe_type_code : null;

    // ✅ ดึงผลรวม amg_amount จาก AmountNg
    $totalNgAmount = AmountNg::whereIn('amg_wip_id', $wipBarcodes->pluck('wip_id'))->sum('amg_amount');

    // ✅ ดึงข้อมูลแบรนด์จาก brandlist
    $brandLists = BrandList::select('bl_id', 'bl_name')->get();

    // ✅ ดึงชื่อ SKU จาก wip_barcode
    $wipSkuNames = Wipbarcode::where('wip_working_id', $id)->pluck('wip_sku_name');

    // ✅ ดึง lot ที่เกี่ยวข้องกับ brands
    $brandsLots = Brand::where('brd_working_id', $id)
                        ->select('brd_id', 'brd_lot', 'brd_amount', 'brd_outfg_date', 'brd_brandlist_id')
                        ->get();

    // ✅ ตรวจสอบ $brd_id
    $lot = $brd_id 
        ? Brand::where('brd_id', $brd_id)->select('brd_id', 'brd_lot', 'brd_amount', 'brd_outfg_date', 'brd_brandlist_id')->first()
        : $brandsLots->first();

    $brd_lot = $lot ? $lot->brd_lot : null;
    $brd_brandlist_id = $lot ? $lot->brd_brandlist_id : null;

    // ✅ ดึงข้อมูล bl_code ตาม brd_id
    $brand = $lot 
        ? Brand::where('brd_id', $lot->brd_id)->first()
        : Brand::where('brd_working_id', $id)->first();

    $brandList = $brand 
        ? BrandList::where('bl_id', $brand->brd_brandlist_id)->first()
        : null;

    // ✅ ดึงผลรวมของ brd_amount
    $totalBrdAmount = Brand::where('brd_working_id', $id)->sum('brd_amount');

    // ✅ ค้นหาข้อมูลจาก WipSummary โดยใช้ ws_working_id เท่ากับ id ของ workprocess
    $wipSummary = WipSummary::where('ws_working_id', $workprocess->id)
                            ->select('ws_output_amount', 'ws_input_amount', 'ws_holding_amount', 'ws_ng_amount')
                            ->first();

    // ✅ ส่งข้อมูลไปยัง View
    return view('datawip', [
        'workprocess'       => $workprocess,
        'line'              => $line,
        'empGroups'         => $empGroups,
        'work_id'           => $id,
        'wipBarcodes'       => $wipBarcodes,
        'totalWipAmount'    => $totalWipAmount,
        'listNgAll'         => $listNgAll,
        'productTypes'      => $productTypes, 
        'peTypeCode'        => $peTypeCode,   
        'peTypeName'        => $peTypeName,  
        'totalNgAmount'     => $totalNgAmount,
        'brandLists'        => $brandLists,
        'wipSkuNames'       => $wipSkuNames,
        'lotgenerator'      => $lotgenerator,
        'lothdgenerator'    => $lothdgenerator,
        'hdbarcode'         => $hdbarcode,
        'brandsLots'        => $brandsLots,
        'workdetail'        => $workdetail,
        'brandList'         => $brandList,
        'brdAmount'         => $totalBrdAmount, 
        'lot'               => $lot,
        'brd_lot'           => $brd_lot,
        'brd_brandlist_id'  => $brd_brandlist_id,
        'wipHoldings'       => $wipHoldings,
        'wipSummary'        => $wipSummary, // ✅ เพิ่มข้อมูล WipSummary
        'wwEndDate'         => $wwEndDate,  // ✅ เพิ่มค่า ww_end_date ที่ดึงมา
    ]);
} 

public function deleteWorkProcess(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $workProcess = WorkProcessQC::find($id);
        if (!$workProcess) {
            return response()->json(['message' => 'ไม่พบข้อมูล WorkProcessQC'], 404, [], JSON_UNESCAPED_UNICODE);
        }

        Wipbarcode::where('wip_working_id', $id)->delete();
        $workProcess->delete();

        DB::commit();
        return response()->json(['message' => 'ลบข้อมูลสำเร็จ'], 200, [], JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500, [], JSON_UNESCAPED_UNICODE);
    }
}


    }
    
    
    
   

  

    


    


