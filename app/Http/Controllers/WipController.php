<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Wipbarcode, WipProductDate, EmpInOut, ProductTypeEmp, WipWorktime, WorkProcessQC, GroupEmp, Skumaster, AmountNg, Brand,ProductionColor, BrandList,WipColordate,WipWorking};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WipController extends Controller
{
    public function insertWip(Request $request, $line, $work_id)
{
    try {
        // ตรวจสอบว่า $line และ $work_id ถูกต้องหรือไม่
        if (!is_numeric($work_id) || !is_numeric($line)) {
            return response()->json([
                'status' => 'error',
                'title' => 'ข้อมูลไม่ถูกต้อง',
                'message' => 'Line หรือ Work ID ไม่ถูกต้อง'
            ], 400);
        }

        // Debug ข้อมูล
        Log::info('Request Data:', $request->all());
        Log::info('Line:', ['line' => $line]);
        Log::info('Work ID:', ['work_id' => $work_id]);

        // เช็ค WorkProcess
        $workProcess = WorkProcessQC::find($work_id);
        if (!$workProcess) {
            return response()->json([
                'status' => 'error',
                'title' => 'ไม่พบข้อมูล',
                'message' => 'ไม่พบข้อมูลกระบวนการทำงานสำหรับ work_id นี้'
            ], 400);
        }

        // Validate ข้อมูล
        $request->validate([
            'wip_barcode' => 'required|string|min:24',
            'wip_empgroup_id' => 'required|integer|min:1',
            'wp_working_id' => 'required|integer',
        ]);

        // ดึงข้อมูลจาก Request
        $input = $request->all();
        DB::beginTransaction();

        // ตัดบาร์โค้ด 11 ตัวแรกเพื่อค้นหา SKU_NAME
        $barcode11 = substr($input['wip_barcode'], 0, 11);

        // ดึง SKU_NAME จาก Skumaster
        $skuNameFull = Skumaster::where('SKU_CODE', $barcode11)->value('SKU_NAME');
        if (!$skuNameFull) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'title' => 'ไม่พบข้อมูล SKU',
                'message' => 'ไม่พบข้อมูลใน SKUMASTER ที่ตรงกับบาร์โค้ดนี้'
            ], 400);
        }

        // ตัดคำว่า "แผ่นรอคัด Line X" ออก
        $skuNameClean = preg_replace('/^แผ่นรอคัด\s*line\s*\d+\s*/iu', '', $skuNameFull);
        $skuName = mb_substr($skuNameClean, 0, 35);

        // ตัด 5 ตัวแรกออกจาก 11 ตัวแรก (ให้เหลือ 6 ตัวท้าย)
        $typeCode = substr($barcode11, 5);

        // คำนวณ pe_index ต่อจากเดิม
        $peIndex = ProductTypeEmp::max('pe_index') + 1;

        // ตรวจสอบว่าบาร์โค้ดซ้ำหรือไม่
        $existingWip = Wipbarcode::where('wip_barcode', $input['wip_barcode'])->first();
        if ($existingWip) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'title' => 'บาร์โค้ดซ้ำ',
                'message' => 'บาร์โค้ดนี้ถูกบันทึกแล้ว'
            ], 400);
        }

        // บันทึกข้อมูลลง Wipbarcode
        $wipAmount = (int) ltrim(substr($input['wip_barcode'], -3), '0');
        $insertwip = Wipbarcode::create([
            'wip_barcode'    => $input['wip_barcode'],
            'wip_amount'     => $wipAmount,
            'wip_working_id' => $input['wp_working_id'],
            'wip_empgroup_id'=> $input['wip_empgroup_id'],
            'wip_sku_name'   => $skuName,
            'wip_index'      => $peIndex,
        ]);

        // คำนวณ indexcount
        $index = WipProductDate::where('wp_working_id', $input['wp_working_id'])
            ->where('wp_empgroup_id', $input['wip_empgroup_id'])
            ->max('wp_empdate_index_id');
        $indexcount = $index + 1;

        // บันทึกข้อมูลลง WipProductDate
        $dmy = now(); // ใช้วันที่ปัจจุบัน
        $dateproduct = new WipProductDate;
        $dateproduct->wp_working_id = $input['wp_working_id'];
        $dateproduct->wp_wip_id = $insertwip->wip_id;
        $dateproduct->wp_empdate_index_id = $indexcount;
        $dateproduct->wp_empgroup_id = $input['wip_empgroup_id'];
        $dateproduct->wp_date_product = Carbon::parse($dmy)->toDateTimeString();
        $dateproduct->save();

        // บันทึกข้อมูลลง ProductTypeEmp
        ProductTypeEmp::create([
            'pe_working_id' => $work_id,
            'pe_type_code'  => $typeCode,
            'pe_type_name'  => $skuName,
            'pe_index'      => $peIndex,
        ]);

        // บันทึกข้อมูลลง EmpInOut
        EmpInOut::create([
            'eio_emp_group'    => $input['wip_empgroup_id'], // ค่าเดียวกับ wip_empgroup_id
            'eio_working_id'   => $input['wp_working_id'],  // ค่าเดียวกับ wp_working_id
            'eio_input_amount' => $wipAmount,              // ค่าเดียวกับ wip_amount
            'eio_line'         => $line,                  // แปลง L2 เป็น 2 หรือ L1 เป็น 1
            'eio_division'     => 'QC',                   // กำหนดเป็น QC
        ]);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'title' => 'บันทึกเรียบร้อย',
            'message' => 'ข้อมูลถูกบันทึกสำเร็จ'
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());

        return response()->json([
            'status' => 'error',
            'title' => 'เกิดข้อผิดพลาด',
            'message' => $e->getMessage()
        ], 500);
    }
}

    
    
    
    
    
  
    
    



public function updateEmpGroup(Request $request, $id)
{
    try {
        // ตรวจสอบข้อมูลที่รับมา
        $request->validate([
            'wip_empgroup_id_1' => 'required|integer',
        ]);

        // ค้นหาข้อมูลโดยใช้ wip_working_id
        $wipBarcode = Wipbarcode::where('wip_working_id', $id)->first();

        if (!$wipBarcode) {
            return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูล WIP Barcode']);
        }

        // อัปเดตข้อมูล
        $wipBarcode->update(['wip_empgroup_id' => $request->wip_empgroup_id_1]);

        return response()->json(['status' => 'success', 'message' => 'อัปเดตข้อมูลสำเร็จ']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
    public function addng(Request $request)
    {
        try {
            // ✅ ตรวจสอบ Validation
            $validatedData = $request->validate([
                'amg_wip_id'    => 'required|array|min:1',
                'amg_ng_id'     => 'required|array|min:1',
                'amg_amount'    => 'required|array|min:1',
            ], [
                'amg_wip_id.required' => 'กรุณาระบุ WIP ID',
                'amg_ng_id.required'  => 'กรุณาเลือกของเสีย',
                'amg_amount.required' => 'กรุณากรอกจำนวนของเสีย',
                'amg_wip_id.array'    => 'ข้อมูล WIP ID ต้องอยู่ในรูปแบบ array',
                'amg_ng_id.array'     => 'ข้อมูลของเสียต้องอยู่ในรูปแบบ array',
                'amg_amount.array'    => 'จำนวนต้องอยู่ในรูปแบบ array',
                'amg_wip_id.min'      => 'กรุณาระบุอย่างน้อย 1 รายการ',
                'amg_ng_id.min'       => 'กรุณาเลือกของเสียอย่างน้อย 1 รายการ',
                'amg_amount.min'      => 'กรุณากรอกจำนวนของเสียอย่างน้อย 1 รายการ',
            ]);
    
            // ✅ ตรวจสอบค่าทั้งหมดและบันทึก
            foreach ($request->amg_wip_id as $key => $wipId) {
                $ngId = $request->amg_ng_id[$key] ?? null;
                $amount = $request->amg_amount[$key] ?? null;
    
                // ตรวจสอบว่าข้อมูลครบถ้วน
                if (empty($wipId)) {
                    throw new \Exception('ไม่พบ WIP ID ในรายการที่ ' . ($key + 1));
                }
    
                if (empty($ngId)) {
                    throw new \Exception('ไม่พบข้อมูล NG ID ในรายการที่ ' . ($key + 1));
                }
    
                if (empty($amount)) {
                    throw new \Exception('จำนวนของเสียว่างในรายการที่ ' . ($key + 1));
                }
    
                // ✅ บันทึกข้อมูล
                AmountNg::create([
                    'amg_wip_id' => $wipId,
                    'amg_ng_id'  => $ngId,
                    'amg_amount' => $amount,
                ]);
            }
    
            // ✅ ส่ง Response สำเร็จ
            return response()->json(['status' => 'success', 'message' => 'บันทึกข้อมูลสำเร็จ']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // กรณี Validation ผิดพลาด
            return response()->json(['status' => 'error', 'message' => 'Validation Error: ' . implode(', ', $e->errors())], 422);
        } catch (\Exception $e) {
            // กรณีข้อผิดพลาดทั่วไป
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
        }
    }
    
    

    
    
    


    
    
    public function editwipamg(Request $request, $id)
{
    $edit = Wipbarcode::find($id);

    if ($edit) {
        // ✅ รับค่าจำนวนที่ต้องการแก้ไข
        $wipAmount = $request->input('wip_amount');

        // ✅ จัดรูปแบบให้ wip_amount เป็นเลข 3 หลัก (เช่น 76 => 076)
        $formattedAmount = str_pad($wipAmount, 3, '0', STR_PAD_LEFT);

        // ✅ แก้ไขสามตัวท้ายของ wip_barcode ให้ตรงกับ wip_amount
        $wipBarcode = substr($edit->wip_barcode, 0, -3) . $formattedAmount;

        // ✅ อัปเดตข้อมูล
        $edit->wip_amount = $wipAmount;
        $edit->wip_barcode = $wipBarcode;
        $edit->save();

        return response()->json(['success' => true, 'message' => 'บันทึกข้อมูลเรียบร้อยแล้ว']);
    } else {
        return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลที่ต้องการอัปเดต'], 404);
    }
}

public function deleteWipLine1($work_id, $id)
{
    
    try {
        $checkWip = Wipbarcode::where('wip_id', $id)->first();

        if (!$checkWip) {
            return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลบาร์โค้ด'], 404);
        }

        $empGroup = $checkWip->wip_empgroup_id;
        $amount = $checkWip->wip_amount;

        // ✅ กำหนดค่าเริ่มต้นให้ $eioOutput = 0
        $eioOutput = 0;

        // ค้นหาข้อมูลเข้า-ออกของพนักงาน
        $eio = EmpInOut::where('eio_working_id', $work_id)
                       ->where('eio_emp_group', $empGroup)
                       ->first();

        if ($eio) {
            $eioId = $eio->id;
            $eioInput = $eio->eio_input_amount;
            $eioOutput = $eio->eio_output_amount;
        }

        // ลบข้อมูลบาร์โค้ด
        $checkWip->delete();

        // เช็คข้อมูลในกลุ่มพนักงาน
        $checkEmpGroup = Wipbarcode::where('wip_working_id', $work_id)
                                   ->where('wip_empgroup_id', $empGroup)
                                   ->get();

        if ($checkEmpGroup->isEmpty() && $eioOutput <= 0) {
            // ถ้าไม่มีข้อมูลบาร์โค้ดในกลุ่มนี้ และไม่มีการเบิกจ่าย ให้ลบข้อมูลการเข้า-ออก
            if ($eio) {
                $eio->delete();
            }
        } else {
            // ถ้ามีข้อมูล ให้ลดจำนวนสินค้าใน eio_input_amount
            if ($eio) {
                $eio->update(['eio_input_amount' => $eioInput - $amount]);
            }
        }

        return response()->json(['success' => true, 'message' => 'ลบข้อมูลสำเร็จ'], 200);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
    }
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

    // ดึงข้อมูล ProductTypeEmp ที่ pe_working_id ตรงกับ $id
    $productTypes = ProductTypeEmp::where('pe_working_id', $id)->first();
    $peTypeCode = $productTypes ? $productTypes->pe_type_code : null;

    // ดึงผลรวม amg_amount จาก AmountNg
    $totalNgAmount = AmountNg::whereIn('amg_wip_id', $wipBarcodes->pluck('wip_id'))->sum('amg_amount');

    // ดึงข้อมูลแบรนด์จากตาราง brandlist
    $brandLists = BrandList::select('bl_id', 'bl_name')->get();

    // ดึงชื่อ SKU ที่เกี่ยวข้องจาก wip_barcode
    $wipSkuNames = Wipbarcode::where('wip_working_id', $id)->pluck('wip_sku_name');

    // ดึง lot ที่เกี่ยวข้องกับ brands ทั้งหมด
    $brandsLots = Brand::where('brd_working_id', $id)
                        ->select('brd_id', 'brd_lot', 'brd_amount', 'brd_outfg_date')
                        ->get();

    // ✅ ตรวจสอบว่ามี `$brd_id` หรือไม่
    $lot = $brd_id 
        ? Brand::where('brd_id', $brd_id)->select('brd_id', 'brd_lot', 'brd_amount', 'brd_outfg_date')->first()
        : $brandsLots->first();

    // **เพิ่มเงื่อนไขป้องกัน ERROR ถ้า `$lot` เป็น `null`**
    $brd_lot = $lot ? $lot->brd_lot : null;

    // ✅ ดึงข้อมูล `bl_code` ตาม `brd_id` ที่ถูกเลือก
    $brand = $lot 
        ? Brand::where('brd_id', $lot->brd_id)->first()
        : Brand::where('brd_working_id', $id)->first();

    $brandList = $brand 
        ? BrandList::where('bl_id', $brand->brd_brandlist_id)->first()
        : null;

    $brdAmount = $brand ? $brand->brd_amount : null;

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
        'totalNgAmount'     => $totalNgAmount,
        'brandLists'        => $brandLists,
        'wipSkuNames'       => $wipSkuNames,
        'lotgenerator'      => $lotgenerator,
        'brandsLots'        => $brandsLots,
        'workdetail'        => $workdetail,
        'brandList'         => $brandList,
        'peTypeCode'        => $peTypeCode,
        'brdAmount'         => $brdAmount,
        'lot'               => $lot,
        'brd_lot'           => $brd_lot, // ✅ ส่ง `brd_lot` ให้ View
    ]);
}


public function conditioncolor($work_id_con,$line_con){

    $colorpd = "";

    $pdtype = ProductTypeEmp::leftJoin('wip_working','ww_id','=','pe_working_id')
    ->leftJoin('brands','brd_working_id','=','ww_id')
    ->where('ww_id','=',$work_id_con);

    $pdcode = $pdtype->value('pe_type_code');
    $remark = $pdtype->value('brd_remark');

    if (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '10' && substr($pdcode,4,2) == '18'){
        $colorpd = "#FFFFFF";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '09') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '09') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '4' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '09') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'B' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '09') {
        $colorpd = "#00b0f0";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '09') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' &&  substr($pdcode,1,1) == '01' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '09') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'B' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '09') {
        $colorpd = "#ff99cc";
    }
    elseif (substr($pdcode,0,1) == 'B' && substr($pdcode,1,1) == '01' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '09') {
        $colorpd = "#ff99cc";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '07' && substr($pdcode,4,2) == '12') {
        $colorpd = "#a9d08e";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '12') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '12') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'B' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '12') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'B' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '12') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        else {
            $colorpd = "#00b0f0";
        }
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '08' && substr($pdcode,4,2) == '12') {
        $colorpd = "#ccccff";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '11' && substr($pdcode,4,2) == '12') {
        if ($line_con == 'L1') {
            $colorpd = "#92d050";
        }
        elseif ($line_con == 'L2') {
            $colorpd = "#ffff00";
        }
        elseif ($line_con == 'L3') {
            $colorpd = "#00b0f0";
        }
        else {
            $colorpd = "#FFFFFF";
        }
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '12' && substr($pdcode,4,2) == '12') {
        $colorpd = "#00b0f0";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '13') {
        $colorpd = "#a9d08e";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '08' && substr($pdcode,4,2) == '13') {
        $colorpd = "#a9d08e";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '13') {
        $colorpd = "#a9d08e";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '12' && $line_con == 'L2') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '12' && $line_con == 'L2') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '12') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '02' && substr($pdcode,4,2) == '12') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '1' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '15' && $line_con == 'L3') {
        $colorpd = "#ff9966";
    }
    elseif (substr($pdcode,0,1) == 'A' && substr($pdcode,1,1) == '3' && substr($pdcode,2,2) == '01' && substr($pdcode,4,2) == '15' && $line_con == 'L3') {
        $colorpd = "#ff9966";
    }
    else {
        $colorpd = "#FFFFFF";
    }

    return $colorpd;

    #92d050 //green line 1
    #ffff00 //yellow
    #00b0f0 //sky
    #ff9966 //orange
    #ff99cc //pink
    #ccccff //pureple
    #a9d08e //green
    #FFFFFF //white
}
public function tagwipqc($line, $work_id, $brd_id)
{
    // แปลง Line เช่น L2 -> 2
    $line = preg_match('/^L(\d+)$/i', $line, $matches) ? $matches[1] : $line;

    $id = $brd_id;

    // ดึงข้อมูลพนักงานจาก GroupEmp
    $groupEmp = GroupEmp::where('line', $line)->first();
    if (!$groupEmp) {
        return response()->json([
            'status' => 'error',
            'message' => 'ไม่พบข้อมูลพนักงานสำหรับ Line นี้'
        ], 404);
    }

    // Query ข้อมูล Tag
    $tag = DB::table('group_emp as g')
        ->select(
            'g.emp1 as name1',
            'g.emp2 as name2',
            'g.id as eg_id',
            'brands.brd_lot',
            'brandlist.bl_code',
            'product_type_emps.pe_type_code', // ชื่อตารางที่ปรับปรุง
            'wip_working.ww_line',
            'brands.brd_amount',
            'brandlist.bl_name',
            'wip_working.ww_lot_date',
            'wip_working.ww_group',
            'product_type_emps.pe_type_name',
            'brands.brd_checker',
            'brands.brd_color'
        )
        ->leftJoin('brands', 'brands.brd_eg_id', '=', 'g.id')
        ->leftJoin('wip_working', 'wip_working.ww_id', '=', 'brands.brd_working_id')
        ->leftJoin('product_type_emps', 'product_type_emps.pe_working_id', '=', 'wip_working.ww_id') // แก้ไขชื่อให้ตรงกับโมเดล
        ->leftJoin('brandlist', 'brandlist.bl_id', '=', 'brands.brd_brandlist_id')
        ->where('brands.brd_id', '=', $brd_id)
        ->get();

    // Query สำหรับ WipProductDate
    $checkdatpd = WipProductDate::select('brands.brd_eg_id', 'wip_product_dates.wp_date_product')
        ->leftJoin('brands', function ($query) {
            $query->on('brands.brd_empdate_index_key', '=', 'wip_product_dates.wp_empdate_index_id')
                  ->on(DB::raw('CAST(wip_product_dates.wp_empgroup_id AS NVARCHAR)'), '=', 'brands.brd_eg_id');
        })
        ->leftJoin('wipbarcodes', 'wipbarcodes.wip_empgroup_id', '=', 'brands.brd_eg_id');

    $getegid = $checkdatpd->where('brands.brd_id', '=', $brd_id)->value('brands.brd_eg_id');

    $dateproduct = $checkdatpd->where('brands.brd_id', '=', $brd_id)
        ->where('wip_product_dates.wp_empgroup_id', '=', $getegid)
        ->where('wip_product_dates.wp_working_id', '=', $work_id)
        ->value('wip_product_dates.wp_date_product');

    // ดึงข้อมูล ProductColors

    // ดึงข้อมูลสีของแบรนด์ตาม ID
    $colorbyid = Brand::select('brd_color')->where('brd_id', '=', $brd_id)->value('brd_color');

    // เรียกใช้งาน Helper หรือฟังก์ชันใน Controller
    $thmonth = $this->thaimonth();
    $typearr = $this->typeofproduct();
    $sizearr = $this->productsize();
    $thicknessarr = $this->thickness();
    $boarderarr = $this->boarder();
    $colordate = $this->wipcolordatecon($dateproduct);

    // Render View พร้อมส่งข้อมูล
    $view = view('template.tagwipqc', [
        'tag'               => $tag,
        'id'                => $id,
        'typearr'           => $typearr,
        'colorbyid'         => $colorbyid,
        'sizearr'           => $sizearr,
        'thicknessarr'      => $thicknessarr,
        'pcs'               => $pcs,
        'boarderarr'        => $boarderarr,
        'thmonth'           => $thmonth,
        'colordate'         => $colordate,
        'dateproduct'       => $dateproduct,
    ]);

    return $view;
}


public function tagwipnn($line, $work_id, $brd_id)
{
    // แปลง Line เช่น L2 -> 2
    $line = preg_match('/^L(\d+)$/i', $line, $matches) ? $matches[1] : $line;

    $id = $brd_id;

    // ดึงข้อมูลพนักงานจาก GroupEmp
    $groupEmp = GroupEmp::where('line', $line)->first();
    if (!$groupEmp) {
        return response()->json([
            'status' => 'error',
            'message' => 'ไม่พบข้อมูลพนักงานสำหรับ Line นี้'
        ], 404);
    }

    $tag = DB::table('brands')
        ->select(
            DB::raw("'{$groupEmp->emp1}' as name1"),
            DB::raw("'{$groupEmp->emp2}' as name2"),
            'brands.brd_lot',
            'brandlist.bl_code',
            'producttype_emp.pe_type_code',
            'wip_working.ww_line',
            'brands.brd_amount',
            'brandlist.bl_name',
            'wip_working.ww_lot_date',
            'wip_working.ww_group',
            'producttype_emp.pe_type_name',
            'brands.brd_checker',
            'brands.brd_color'
        )
        ->leftJoin('wip_working', 'wip_working.ww_id', '=', 'brands.brd_working_id')
        ->leftJoin('producttype_emp', 'producttype_emp.pe_working_id', '=', 'wip_working.ww_id')
        ->leftJoin('brandlist', 'brandlist.bl_id', '=', 'brands.brd_brandlist_id')
        ->where('brands.brd_id', '=', $brd_id)
        ->get();

    $view = view('template.tagwipnn', [
        'tag' => $tag,
        'id' => $id,
    ]);
    return $view;
}
public function tagfn($line, $work_id, $brd_id)
{
    // แปลง Line เช่น L2 -> 2
    $line = preg_match('/^L(\d+)$/i', $line, $matches) ? $matches[1] : $line;

    $id = $brd_id;

    // ดึงข้อมูลพนักงานจาก GroupEmp
    $groupEmp = GroupEmp::where('line', $line)->first();
    if (!$groupEmp) {
        return response()->json([
            'status' => 'error',
            'message' => 'ไม่พบข้อมูลพนักงานสำหรับ Line นี้'
        ], 404);
    }

    $tag = DB::table('brands')
        ->select(
            DB::raw("'{$groupEmp->emp1}' as name1"),
            DB::raw("'{$groupEmp->emp2}' as name2"),
            'brands.brd_lot',
            'brandlist.bl_code',
            'producttype_emp.pe_type_code',
            'wip_working.ww_line',
            'brands.brd_amount',
            'brandlist.bl_name',
            'wip_working.ww_lot_date',
            'wip_working.ww_group',
            'producttype_emp.pe_type_name',
            'brands.brd_checker',
            'brands.brd_color'
        )
        ->leftJoin('wip_working', 'wip_working.ww_id', '=', 'brands.brd_working_id')
        ->leftJoin('producttype_emp', 'producttype_emp.pe_working_id', '=', 'wip_working.ww_id')
        ->leftJoin('brandlist', 'brandlist.bl_id', '=', 'brands.brd_brandlist_id')
        ->where('brands.brd_id', '=', $brd_id)
        ->get();

    $view = view('template.tagfn', [
        'tag' => $tag,
        'id' => $id,
    ]);
    return $view;
}
public function tagfg($line, $work_id, $brd_id)
{
    // ค้นหา `Brand` โดยใช้ `brd_working_id` และ `brd_id`
    $brand = Brand::where('brd_working_id', $work_id)
                  ->where('brd_id', $brd_id)
                  ->first();

    // ตรวจสอบว่าเจอ `Brand` หรือไม่
    if (!$brand) {
        return response()->json(['error' => 'ไม่พบข้อมูล Brand'], 404);
    }

    // ค้นหา `BrandList` โดยใช้ `brd_brandlist_id`
    $brandList = BrandList::where('bl_id', $brand->brd_brandlist_id)->first();

    // ตรวจสอบว่าเจอ `BrandList` หรือไม่
    if (!$brandList) {
        return response()->json(['error' => 'ไม่พบข้อมูล BrandList'], 404);
    }

    // ดึงค่า `bl_id` พร้อมเติม `0` ถ้าหลักเดียว
    $bl_id_formatted = isset($brandList->bl_id) ? (strlen($brandList->bl_id) == 1 ? '0' . $brandList->bl_id : $brandList->bl_id) : 'N/A';

    // ดึงค่า `bl_name` จาก `BrandList`
    $bl_name = $brandList->bl_name ?? 'N/A';

    // ค้นหา `pe_type_code` จาก `product_type_emps` โดยใช้ `pe_working_id`
    $peTypeCode = ProductTypeEmp::where('pe_working_id', $work_id)->value('pe_type_code') ?? 'N/A';

    // ค้นหา `wip_sku_name` จาก `wipbarcodes` โดยใช้ `wip_working_id`
    $wip_sku_name = Wipbarcode::where('wip_working_id', $work_id)->value('wip_sku_name') ?? 'N/A';

    // ดึงค่า `brd_amount` จาก `Brand`
    $brd_amount = $brand->brd_amount ?? 'N/A';

    // ค้นหา `ww_line` จาก `wip_working` โดยใช้ `ww_id`
    $ww_line = WipWorking::where('ww_id', $work_id)->value('ww_line') ?? 'N/A';

    // ค้นหา `ww_group` จาก `wip_working` โดยใช้ `ww_id`
    $ww_group = WipWorking::where('ww_id', $work_id)->value('ww_group') ?? 'N/A';

    // ค้นหา `eio_emp_group` จาก `emp_in_outs` โดยใช้ `eio_working_id`
    $eio_emp_group = EmpInOut::where('eio_working_id', $work_id)->value('eio_emp_group');

    // ตรวจสอบว่ามี `eio_emp_group` หรือไม่
    if ($eio_emp_group) {
        // ค้นหา `emp1` และ `emp2` จาก `group_emp` โดยใช้ `id` ที่ตรงกับ `eio_emp_group`
        $groupEmp = GroupEmp::where('id', $eio_emp_group)->first();
        $emp1 = $groupEmp->emp1 ?? 'N/A';
        $emp2 = $groupEmp->emp2 ?? 'N/A';
    } else {
        $emp1 = 'N/A';
        $emp2 = 'N/A';
    }

    // ดึงค่า `brd_checker` จาก `Brand`
    $brd_checker = $brand->brd_checker ?? 'N/A';

    // ส่งข้อมูลไปยัง View
    return view('template.tagfg', compact(
        'brandList', 'brand', 'work_id', 'line', 'bl_id_formatted', 
        'peTypeCode', 'bl_name', 'wip_sku_name', 'brd_amount', 
        'ww_line', 'ww_group', 'emp1', 'emp2', 'brd_checker'
    ));
}




public function wipcolordatecon($dateproduct){
    $monthno = date('m',strtotime($dateproduct));
    $color = WipColordate::getcolor($monthno);
    return $color;
}
public function thaimonth(){

    $thmonth = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม");
    return $thmonth;
}
public function typeofproduct(){

    $typearr = [
    '1'         =>  '-',
    '2'         =>  'เกรด B',
    '3'         =>  'ทนชื้น',
    '4'         =>  'กันร้อน',
    'Z'         =>  'ตลาดล่าง',
    'A'         =>  'เบาเบา',
    '6'         =>  'ทนไฟ',
    '9'         =>  'งานแก้',
    'W'         =>  'เคลือบWAX',
    '7'         =>  'ทนชื้น+ฟลอยด์',
    'H'         =>  'ทนชื้น เคลือบ WAX เจาะรูคู่',
    'O'         =>  'ทนชื้น เคลือบ WAX เจาะรูเดี่ยว',
    '5'         =>  'แกร่งพิเศษ',
    'P'         =>  'ขาวผ่อง',
    'Y'         =>  'นาเดีย',
    'G'         =>  'อดามาส',
    '8'         =>  'ทนชื้นกันเชื้อรา',
    'C'         =>  'sblock รุ่น CR1',
    'E'         =>  'sblock รุ่น CR2',
    'R'         =>  'sblock รุ่น CR8',
    'S'         =>  'sblock รุ่น CR4',
    'N'         =>  '4 ด้าน(ซีเนีย)',
    'I'         =>  'ธรรมดาสูตรทนชื้น',
    'J'         =>  'ขาวผ่องสูตรทนชื้น',
    'L'         =>  'PVC Lemon',
    'K'         =>  'PVC Orange',
    'M'         =>  'PVC lemon+ฟอล์ย',
    'D'         =>  'ตัดโค้ง',
    'T'         =>  '4 ด้านทนชื้น',
    'V'         =>  'ทนชื้นติดเทปAUTO',
    'X'         =>  'กันร้อนติดเทปAUTO',
    'F'         =>  'ทนชื้นเคลือบ WAX เจาะรูคู่(ติดตาข่าย)',
    ];

    return $typearr;
}
public function productsize(){

    $sizearr = [
    '01'        =>  '1.2 x 2.4 m.',
    '02'        =>  '1.22 x 2.44 m.',
    '03'        =>  '1.21 x 2.42 cm',
    '04'        =>  '1.22 x 3.05 m.',
    '05'        =>  '1.2 x 3 m.',
    '06'        =>  '1.2 x 2.5 m.',
    '07'        =>  '1.2 x 2.3 m.',
    '08'        =>  '1.2 x 2.7 m.',
    '09'        =>  '1.2 x 2.2 m.',
    '10'        =>  '1.21 x 2.46 m.',
    '11'        =>  '1.22 x 1.83 m.',
    '12'        =>  '1.22 x 2.135 m.',
    '13'        =>  '0.9 x 2.4 m.',
    '14'        =>  '0.9 x 2.7 m.',
    '15'        =>  '1.2 x 2.8 m.',
    '16'        =>  '0.9 x 2.1 m.',
    '17'        =>  '1.2 x 3.01 m.',
    '18'        =>  '1.21 x 2.1 m',
    '19'        =>  '1.21 x 2.75 m',
    '20'        =>  '1.21 x 1.5 m',
    '21'        =>  '1.2 x 3.0 m',
    '22'        =>  '1.21 x 2.43 m.',
    '23'        =>  '1.22 x 2.745 m.',
    ];

    return $sizearr;
}
public function thickness(){

    $thicknessarr = [
    '09'        =>  '9 mm.',
    '10'        =>  '10 mm.',
    '12'        =>  '12 mm.',
    '13'        =>  '13 mm.',
    '15'        =>  '12.5 mm.',
    '16'        =>  '15 mm.',
    '17'        =>  '15.5 mm.',
    '18'        =>  '8 mm.',
    '19'        =>  '7 mm.',
    '20'        =>  '6.5 mm.',
    '21'        =>  '6 mm.',
    '22'        =>  '8.1 mm.',
    '85'        =>  '8.5 mm',
    '95'        =>  '9.5 mm',
    ];

    return $thicknessarr;
}
public function boarder(){

    $boarderarr = DB::connection('sqlsrv_bplus')->table(DB::raw("
    ICSIZE"))
        ->select((DB::raw("ICSIZE_NAME ,
        ICSIZE_CODE,SUBSTRING(ICSIZE_CODE,0,2) as edge,
        SUBSTRING(ICSIZE_CODE,2,1) as type,
        SUBSTRING(ICSIZE_CODE,3,2) as size, 
        SUBSTRING(ICSIZE_CODE,5,2) as thickness ")))
        ->get();

    foreach ($boarderarr as $boarderarr) {

    if ($boarderarr->edge == 'A'){
        'ขอบลาด';
    }
    elseif ($boarderarr->edge == 'B'){
        'ขอบเรียบ';
    }
    elseif ($boarderarr->edge == '0'){
        '-';
    }
    
    }
}
public function editbrand(Request $request, $brd_id)
{
    \Log::info('📌 Received brd_id:', ['brd_id' => $brd_id]);
    \Log::info('📌 Received bl_id:', ['bl_id' => $request->input('bl_id')]);

    if (!$request->has('bl_id') || !$request->input('bl_id')) {
        return response()->json(['error' => 'กรุณาเลือกแบรนด์'], 422);
    }

    // ✅ ค้นหาข้อมูลจาก `brd_id`
    $brand = Brand::where('brd_id', $brd_id)->first();

    if (!$brand) {
        return response()->json(['error' => 'ไม่พบข้อมูลแบรนด์'], 404);
    }

    // ✅ อัปเดตข้อมูลแบรนด์
    $brand->brd_brandlist_id = $request->input('bl_id');
    $brand->save();

    return response()->json(['success' => 'บันทึกข้อมูลสำเร็จ']);
}
public function deletebrand(Request $request, $brd_id)
{
    \Log::info('🗑️ Received brd_id for deletion:', ['brd_id' => $brd_id]);

    $brand = Brand::where('brd_id', $brd_id)->first();

    if (!$brand) {
        return response()->json(['error' => 'ไม่พบข้อมูลที่ต้องการลบ'], 404);
    }

    $brand->delete();

    return response()->json(['success' => 'ลบข้อมูลสำเร็จ']);
}
public function outfgcode(Request $request, $line, $work_id)
{
    // ✅ ตรวจสอบและแปลง `line` ให้มี "L" ข้างหน้า ถ้าเป็นตัวเลขล้วน
    $line = preg_match('/^\d+$/', $line) ? "L$line" : $line;

    // ดึงสีตามเงื่อนไข
    $colorpd = $this->conditioncolor($work_id, $line);

    // ตรวจสอบ lot โดยใช้ group_emp
    $checklot = Brand::leftJoin('group_emp', 'group_emp.id', '=', 'brands.brd_eg_id')
        ->where('group_emp.line', '=', $line)
        ->where('brands.brd_lot', '=', $request->input('brd_lot'));

    // ตรวจสอบ EmpInOut
    $eio = EmpInOut::where('eio_working_id', '=', $work_id)
        ->where('eio_emp_group', '=', $request->get('brd_eg_id'));

    $eioid = $eio->value('eio_id');
    $eiooutput = $eio->value('eio_output_amount') ?? 0;

    // ตรวจสอบลำดับข้อมูล
    $index = Brand::where('brd_working_id', '=', $work_id)
        ->where('brd_eg_id', '=', $request->input('brd_eg_id'))
        ->count();
    $countindex = $index + 1;

    // Validate Input
    $request->validate([
        'brd_lot' => 'required|string',
        'brd_eg_id' => 'required|numeric',
        'brd_brandlist_id' => 'required|numeric',
        'brd_amount' => 'required|numeric|min:1',
        'brd_checker' => 'required|string',
    ]);

    // กำหนดค่าคงที่สำหรับแบรนด์
    $white_brandlist = ["32", "33", "36", "37", "38", "49"];
    $white_manufacture = "44";
    $white_qc = "31";

    $brd_eg_id = $request->input('brd_eg_id');
    $brd_brandlist_id = $request->input('brd_brandlist_id');

    if ($brd_eg_id != "0" && $brd_brandlist_id != "0") {
        if (!$checklot->exists()) {
            $brands = new Brand();
            $brands->brd_working_id = $work_id;
            $brands->brd_brandlist_id = $brd_brandlist_id;
            $brands->brd_lot = $request->input('brd_lot');
            $brands->brd_eg_id = $brd_eg_id;
            $brands->brd_amount = $request->input('brd_amount');
            $brands->brd_outfg_date = now();
            $brands->brd_empdate_index_key = $countindex;
            $brands->brd_remark = $request->input('brd_remark');
            $brands->brd_backboard_no = $request->input('brd_backboard_no');
            $brands->brd_checker = $request->input('brd_checker');
            $brands->brd_color = $colorpd;

            // ตรวจสอบสถานะแบรนด์
            $brands->brd_status = in_array($brd_brandlist_id, $white_brandlist) || 
                                  $brd_brandlist_id == $white_manufacture || 
                                  $brd_brandlist_id == $white_qc ? '2' : '1';

            $brands->save();

            // อัปเดตข้อมูล EmpInOut
            if ($eioid) {
                $eioout = EmpInOut::find($eioid);
                $eioout->eio_output_amount = $eiooutput + $request->input('brd_amount');
                $eioout->update();
            }

            return response()->json([
                'message' => 'บันทึกข้อมูลสำเร็จ',
                'brd_id' => $brands->brd_id,
                'brd_brandlist_id' => $brands->brd_brandlist_id,
                'line' => $line,
                'work_id' => $work_id,
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json(['error' => 'Duplicate lot detected'], 400);
        }
    } else {
        return response()->json(['error' => 'Invalid input'], 400);
    }
}
}

