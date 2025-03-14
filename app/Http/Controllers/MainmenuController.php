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
use App\Models\WorkprocessTemp;
use Carbon\Carbon;
use Illuminate\Support\Facades\App; // ✅ เพิ่มบรรทัดนี้เพื่อแก้ปัญหา
use Illuminate\Support\Facades\Schema; // เพิ่มการ import ที่นี่



class MainmenuController extends Controller
{
    public function mainmenu()
    {
        if (!auth()->check()) {
            return redirect('/'); 
        }

        return view('mainmenu');
    }
    
    public function manufacture($line = null)
    {

        if (!auth()->check()) {
            return redirect('/'); 
        }
        // ✅ กำหนดค่าเริ่มต้นให้ `$line`
        $line = $line ?? 1; // ถ้าไม่มีค่า Default เป็น 1
    
        // ✅ ดึงข้อมูลจากฐานข้อมูล
        $employees = Employee::when($line, fn($query) => $query->where('line', $line))->get();
        $groups = GroupQC::when($line, fn($query) => $query->where('line', $line))->pluck('group');
        $groupemps = GroupEmp::when($line, fn($query) => $query->where('line', $line))->get();
        
        $lineheader = $line ? 'Line ' . $line : 'All Lines';
        $prefixedLine = 'L' . $line;
    
        // ✅ เช็คว่าตาราง `wip_worktimes` มีอยู่ก่อน Query
        $worked = Schema::hasTable('wip_worktimes') 
            ? WipWorktime::select('wwt_id', 'wwt_status', 'wwt_date')
                ->where('wwt_line', $line)
                ->get()
            : collect();
    
        // ✅ เช็คตาราง `wip_working` และ `wipbarcodes` ก่อน Query
        $workProcessIDs = (Schema::hasTable('wip_working') && Schema::hasTable('wipbarcodes'))
            ? WorkProcessQC::join('wipbarcodes', 'workprocess_qc.id', '=', 'wipbarcodes.wip_working_id')
                ->join('wip_working', 'wip_working.ww_id', '=', 'wipbarcodes.wip_working_id')
                ->where('workprocess_qc.line', $line)
                ->where('wip_working.ww_line', $prefixedLine)
                ->distinct()
                ->pluck('workprocess_qc.id')
            : collect();
    
        // ✅ เช็คว่ามีค่าใน `$workProcessIDs` ก่อน Query
        $totalWipAmount = $workProcessIDs->isNotEmpty()
            ? Wipbarcode::whereIn('wip_working_id', $workProcessIDs)->sum('wip_amount')
            : 0;
    
        // ✅ ดึงข้อมูล WorkProcessQC
        $excludedWwIds = WorkprocessTemp::pluck('workprocess_id')->toArray();

        // ✅ ดึงข้อมูล WorkProcessQC (ไม่รวม `ww_id` ที่อยู่ใน `workprocess_temps`)
        $workProcessQC = Schema::hasTable('wip_working')
        ? WipWorking::where('ww_line', $line)
            ->whereNotIn('ww_id', $excludedWwIds)
            ->leftJoin('product_type_emps', 'wip_working.ww_id', '=', 'product_type_emps.pe_working_id')
            ->select(
                'wip_working.ww_id AS id',  
                'wip_working.ww_group AS group',  
                'wip_working.ww_line AS line',  
                'wip_working.ww_status AS status',
                'wip_working.ww_start_date AS start_date', // ✅ ดึง `ww_start_date`
                'wip_working.ww_end_date AS end_date', // ✅ ดึง `ww_end_date`
                \DB::raw("MAX(product_type_emps.pe_type_name) AS pe_type_name"),
                \DB::raw("CASE 
                    WHEN wip_working.ww_status = 'W' THEN 'กำลังคัด'
                    WHEN wip_working.ww_status = 'E' THEN 'จบการทำงาน'
                    ELSE 'ไม่ทราบสถานะ'
                END AS status_display")
            )
            ->groupBy(
                'wip_working.ww_id',
                'wip_working.ww_group',
                'wip_working.ww_line',
                'wip_working.ww_status',
                'wip_working.ww_start_date', // ✅ เพิ่ม `ww_start_date`
                'wip_working.ww_end_date' // ✅ เพิ่ม `ww_end_date`
            )
            ->get()
        : collect();
    
    
        // ✅ คำนวณยอดรวม
        $totalWip = $totalFg = $totalNg = $totalHd = 0;

        foreach ($workProcessQC as $wpqc) {
            // ✅ ใช้ ww_id ในการคำนวณแทน workprocess_qc.id
            $wpqc->sumwipendtime = Schema::hasTable('wip_working') && Schema::hasTable('wipbarcodes')
                ? Wipbarcode::where('wip_working_id', $wpqc->id)->sum('wip_amount')
                : 0;
    
            $wpqc->sumfgendtime = Schema::hasTable('brands')
                ? Brand::where('brd_working_id', $wpqc->id)->sum('brd_amount')
                : 0;
    
            $wpqc->sumngendtime = Schema::hasTable('amount_ngs')
                ? AmountNg::join('wipbarcodes', 'wipbarcodes.wip_id', '=', 'amount_ngs.amg_wip_id')
                    ->where('wipbarcodes.wip_working_id', $wpqc->id)
                    ->sum('amg_amount')
                : 0;
    
            $wpqc->sumhdendtime = $wpqc->sumwipendtime - $wpqc->sumfgendtime - $wpqc->sumngendtime;
    
            // ✅ รวมค่าจากแต่ละ record
            $totalWip += $wpqc->sumwipendtime;
            $totalFg += $wpqc->sumfgendtime;
            $totalNg += $wpqc->sumngendtime;
            $totalHd += $wpqc->sumhdendtime;
        }
        $wwIds = WipWorking::where('ww_line', $line)->pluck('ww_id')->toArray();

        // ✅ ถ้ามี `ww_id` นำไปดึง `wip_amount` รวมตามวันที่ (`created_at`)
        $groupedData = [];
        if (!empty($wwIds)) {
            $groupedData = Wipbarcode::whereIn('wip_working_id', $wwIds)
                ->selectRaw('CONVERT(DATE, created_at) as date, SUM(wip_amount) as total_wip_amount')
                ->groupByRaw('CONVERT(DATE, created_at)')
                ->orderByRaw('CONVERT(DATE, created_at) asc')
                ->get();
        }
        
    
    
        // ✅ **สร้าง View `model` ล่วงหน้า**
        $modelView = view('model', compact(
            'groups',
            'groupemps',
            'lineheader',
            'employees',
            'line',
            'workProcessQC',
            'totalWip',
            'totalFg',
            'totalNg',
            'totalHd',
            'worked',
            'groupedData' // ✅ ส่งข้อมูลไปที่ Blade

        ))->render();
    
        // ✅ ส่ง `modelView` ไปที่ View `manufacture`
        return view('manufacture', compact(
            'groups',
            'groupemps',
            'lineheader',
            'employees',
            'line',
            'workProcessQC',
            'totalWip',
            'totalFg',
            'totalNg',
            'totalHd',
            'worked',
            'modelView',
            'groupedData' // ✅ ส่งข้อมูลไปที่ Blade
            // ✅ ส่งตัวแปรนี้ไปที่ View
        ));
    }
    
    public function workgroup(Request $request)
    {
        try {
            // ✅ ตั้งค่าภาษาไทยให้ Carbon
            App::setLocale('th');
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
            if ($group === 'B' && ($currentDate->hour < 20 || ($currentDate->hour == 20 && $currentDate->minute == 0))) {
                $currentDate->subDay(); // ลบ 1 วัน ถ้า Group เป็น B และเวลายังไม่ถึง 20:00 น.
            }
    
            // ✅ ใช้วันที่และเวลาปัจจุบัน หรือปรับตามเงื่อนไข
            $dateForWork = $currentDate->format('Y-m-d H:i:s'); // วันที่พร้อมเวลา
            $lotDate = $currentDate->format('Y-m-d') . ' 00:00:00'; // วันที่เริ่มต้น 00:00:00
    
            // ✅ แปลงวันที่เป็น "1 พฤศจิกายน 2568 14:30 น."
            $thaiDate = $currentDate->translatedFormat('j F Y H:i') . ' น.';
    
            // ✅ ใช้ค่า line และ group
            $line = $request->input('ww_line');
            $wwGroupFormatted = $line . $group;
    
            // ✅ บันทึกข้อมูลลงใน wip_working
          // ✅ ดึงค่า ww_id ล่าสุด ถ้ามีให้ +1 ถ้าไม่มีให้ใช้ค่า 1
$lastId = DB::table('wip_working')->max('ww_id');
$newId = $lastId ? $lastId + 1 : 1; // ถ้าไม่มีค่า ให้ใช้ 1 เป็นค่าแรก

// ✅ บันทึกข้อมูลลงใน wip_working โดยกำหนด ww_id เอง
DB::table('wip_working')->insert([
    'ww_id' => $newId, // ✅ กำหนดค่า ID ใหม่เอง
    'ww_line' => $line,
    'ww_group' => $wwGroupFormatted,
    'ww_division' => 'QC',
    'ww_start_date' => $dateForWork,
    'ww_lot_date' => $lotDate,
    'ww_status' => 'W',
    'ww_wwt_index' => 0,
    'created_at' => now(),
    'updated_at' => now(),
]);

$id = $newId; // ✅ กำหนดค่าให้ตัวแปร $id เพื่อใช้ใน redirect_url

            // ✅ ตรวจสอบว่าการบันทึกสำเร็จหรือไม่
            if (!$id) {
                Log::error("Error: Failed to insert into wip_working. ID is null.", [
                    'line' => $line,
                    'group' => $group,
                ]);
    
                // ✅ ลองดึงข้อมูลล่าสุด
                $lastInserted = DB::table('wip_working')
                    ->where('ww_line', $line)
                    ->where('ww_group', $wwGroupFormatted)
                    ->orderBy('ww_id', 'desc')
                    ->first();
    
                if ($lastInserted) {
                    $id = $lastInserted->ww_id;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error: Could not retrieve ID from wip_working.'
                    ], 500);
                }
            }
    
            // ✅ อัปเดตค่า ww_wwt_index เป็น 0 สำหรับ ww_id ที่เพิ่งสร้าง
            DB::table('wip_working')
                ->where('ww_id', $id)
                ->update(['ww_wwt_index' => 0]);
    
            // ✅ เพิ่ม Log
            Log::info("WIP Working data for line $line and group $group has been saved successfully.", [
                'ww_id' => $id
            ]);
    
            // ✅ ส่ง JSON Response กลับไปให้ AJAX พร้อม URL สำหรับ redirect
            return response()->json([
                'success' => true,
                'date' => $thaiDate,
                'redirect_url' => route('datawip', ['line' => $line, 'id' => $id])
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    

    
    
    


    public function datawip($line, $id, $brd_id = null)
    {
        if (!auth()->check()) {
            return redirect('/'); 
        }
        // ✅ ตรวจสอบว่า $line มี 'L' นำหน้าหรือไม่ ถ้ามีให้ตัดออก
        if (str_starts_with($line, 'L')) {
            $line = substr($line, 1);
        }
    
        // ✅ ค้นหา WipWorking ตาม ww_id และ ww_line
        $workprocess = WipWorking::where('ww_id', $id)
                                ->where('ww_line', $line)
                                ->firstOrFail();
    
        // ✅ ดึงข้อมูล wip_working ที่เกี่ยวข้อง
        $workdetail = $workprocess;
        $workpgroup = $workdetail->ww_group;
        $workstatus = $workdetail->ww_status;
        $workdate = $workdetail->ww_lot_date;
        $workline = $workdetail->ww_line;
    
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
        $lothdgenerator = date('ymd', strtotime($workdate)) . $workpgroup . str_pad($lotcheck + 1, 2, '0', STR_PAD_LEFT);
    
        // ✅ ดึงค่า holding ที่เกี่ยวข้อง
        $holding = WipHolding::where('wh_working_id', $id)->sum('wh_index'); 
    
        // ✅ สร้าง Barcode ตามเงื่อนไข
       // ✅ ใช้ค่า $line ทั้งหมด ไม่ต้องดึงเฉพาะตัวที่ 2
if ($holding < 100 && $holding > 10) {
    $hdbarcode = 'B'.$line.'99-'.$lothdgenerator.'0'.$holding;
} elseif ($holding < 10) {
    $hdbarcode = 'B'.$line.'99-'.$lothdgenerator.'00'.$holding;
} else {
    $hdbarcode = 'B'.$line.'99-'.$lothdgenerator.$holding;
}


    
        // ✅ ดึงข้อมูลพนักงานในกลุ่มที่ line และ status = 1
        $empGroups = GroupEmp::where('line', $line)
                             ->where('status', 1)
                             ->get();
    
        // ✅ ดึงข้อมูลบาร์โค้ดที่เกี่ยวข้องกับ WipWorking
        $wipBarcodes = Wipbarcode::where('wip_working_id', $id)->with('groupEmp')->get();
        $totalWipAmount = Wipbarcode::where('wip_working_id', $id)->sum('wip_amount');
    
        // ✅ ดึงข้อมูล listngall ที่ lng_status = 1
        $listNgAll = Listngall::where('lng_status', 1)->get();
    
        // ✅ ดึงข้อมูล ProductTypeEmp ตาม pe_working_id
        $productTypes = ProductTypeEmp::where('pe_working_id', $workprocess->ww_id)->get();
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
    
        // ✅ ค้นหาข้อมูลจาก WipSummary โดยใช้ ws_working_id เท่ากับ ww_id
        $wipSummary = WipSummary::where('ws_working_id', $workprocess->ww_id)
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
            'wipSummary'        => $wipSummary, 
            'wwEndDate'         => $wwEndDate,
        ]);
    }
    
    public function deleteWorkProcess(Request $request, $id)
    {
        try {
            DB::beginTransaction();
    
            // ✅ 1. ตรวจสอบว่ามีข้อมูลใน `wip_working` หรือไม่
            $workProcess = WipWorking::find($id);
            if (!$workProcess) {
                return response()->json(['message' => 'ไม่พบข้อมูล WipWorking'], 404, [], JSON_UNESCAPED_UNICODE);
            }
    
            // ✅ 2. ลบข้อมูลใน `emp_in_outs` ที่อ้างอิง `ww_id`
            DB::table('emp_in_outs')->where('eio_working_id', $id)->delete();
    
            // ✅ 3. ลบข้อมูลที่เกี่ยวข้องในตารางอื่น ๆ
            Wipbarcode::where('wip_working_id', $id)->delete(); // ลบ `wipbarcodes`
            AmountNg::where('amg_wip_id', $id)->delete(); // ลบ `amount_ngs`
            Brand::where('brd_working_id', $id)->delete(); // ลบ `brands`
            
            // ✅ 4. ลบ `wip_working`
            $workProcess->delete();
    
            DB::commit();
            return response()->json(['message' => 'ลบข้อมูลสำเร็จ'], 200, [], JSON_UNESCAPED_UNICODE);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
    
    
    
public function getLine(Request $request)
{
    // ✅ ดึงค่า line จาก session หรือจาก request
    $line = session('line'); // หรือ $request->input('line');

    if (!$line) {
        return response()->json(['error' => 'Line not found'], 400);
    }

    return response()->json(['line' => $line]);
}
public function warehousenk() {
    return view('warehouseNK');
}
public function getWipData($line)
{
    // ✅ ดึง `ww_id` ทั้งหมดที่ `ww_line` ตรงกับค่า `$line`
    $wwIds = WipWorking::where('ww_line', $line)->pluck('ww_id')->toArray();

    // ✅ ถ้ามี `ww_id` นำไปหา `wip_working_id` ใน `Wipbarcode`
    if (!empty($wwIds)) {
        $wipAmountsByDate = Wipbarcode::whereIn('wip_working_id', $wwIds)
            ->selectRaw('DATE(created_at) as date, SUM(wip_amount) as total_wip_amount')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($wipAmountsByDate);
    }

    return response()->json([]);
}
    }
    
    
    
   

  

    


    


