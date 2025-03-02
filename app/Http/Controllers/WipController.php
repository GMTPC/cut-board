<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Wipbarcode, WipProductDate, EmpInOut, ProductTypeEmp, WipWorktime, WorkProcessQC, GroupEmp, Skumaster, AmountNg, Brand,ProductionColor, BrandList,WipColordate,WipWorking,WipSummary,WipHolding,WipWasteDetail,WipZiptape,EndCsvDetail};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\CheckCsvWh;
use App\Models\CheckCsvWhIndex;
use App\Models\WarehouseReturnToQc;
use App\Models\WorkprocessTemp;

class WipController extends Controller
{
    public function insertWip(Request $request, $line, $work_id)
    {
        try {
            // ✅ ตรวจสอบว่า $line และ $work_id ถูกต้องหรือไม่
            if (!is_numeric($work_id) || !is_numeric($line)) {
                return response()->json([
                    'status' => 'error',
                    'title' => 'ข้อมูลไม่ถูกต้อง',
                    'message' => 'Line หรือ Work ID ไม่ถูกต้อง'
                ], 400);
            }
    
            // ✅ Debug ข้อมูล
            Log::info('Request Data:', $request->all());
            Log::info('Line:', ['line' => $line]);
            Log::info('Work ID:', ['work_id' => $work_id]);
    
            // ✅ เช็ค WorkProcess
            $workProcess = WorkProcessQC::find($work_id);
            if (!$workProcess) {
                return response()->json([
                    'status' => 'error',
                    'title' => 'ไม่พบข้อมูล',
                    'message' => 'ไม่พบข้อมูลกระบวนการทำงานสำหรับ work_id นี้'
                ], 400);
            }
    
            // ✅ Validate ข้อมูล
            $request->validate([
                'wip_barcode' => 'required|string|min:24',
                'wip_empgroup_id' => 'required|integer|min:1',
                'wp_working_id' => 'required|integer',
            ]);
    
            // ✅ ตรวจสอบว่าบาร์โค้ดซ้ำหรือไม่
            if (Wipbarcode::where('wip_barcode', $request->wip_barcode)->exists()) {
                return response()->json([
                    'status' => 'duplicate', // ✅ ส่งสถานะเป็น duplicate
                    'title' => 'บาร์โค้ดซ้ำ',
                    'message' => 'บาร์โค้ดนี้ถูกบันทึกแล้ว กรุณาลองใหม่'
                ], 200);
            }
    
            // ✅ ดำเนินการบันทึกข้อมูล
            DB::beginTransaction();
    
            // ✅ ตัดบาร์โค้ด 11 ตัวแรกเพื่อค้นหา SKU_NAME
            $barcode11 = substr($request->wip_barcode, 0, 11);
    
            // ✅ ดึง SKU_NAME จาก Skumaster
            $skuNameFull = Skumaster::where('SKU_CODE', $barcode11)->value('SKU_NAME');
            if (!$skuNameFull) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'title' => 'ไม่พบข้อมูล SKU',
                    'message' => 'ไม่พบข้อมูลใน SKUMASTER ที่ตรงกับบาร์โค้ดนี้'
                ], 400);
            }
    
            // ✅ ตัดคำว่า "แผ่นรอคัด Line X" ออก
            $skuNameClean = preg_replace('/^แผ่นรอคัด\s*line\s*\d+\s*/iu', '', $skuNameFull);
            $skuName = mb_substr($skuNameClean, 0, 35);
    
            // ✅ ตัด 5 ตัวแรกออกจาก 11 ตัวแรก (ให้เหลือ 6 ตัวท้าย)
            $typeCode = substr($barcode11, 5);
    
            // ✅ คำนวณ pe_index ต่อจากเดิม
            $peIndex = ProductTypeEmp::max('pe_index') + 1;
    
            // ✅ บันทึกข้อมูลลง Wipbarcode
            $wipAmount = (int) ltrim(substr($request->wip_barcode, -3), '0');
            $insertwip = Wipbarcode::create([
                'wip_barcode'    => $request->wip_barcode,
                'wip_amount'     => $wipAmount,
                'wip_working_id' => $request->wp_working_id,
                'wip_empgroup_id'=> $request->wip_empgroup_id,
                'wip_sku_name'   => $skuName,
                'wip_index'      => $peIndex,
            ]);
    
            // ✅ บันทึกข้อมูลลง ProductTypeEmp
            ProductTypeEmp::create([
                'pe_working_id' => $work_id,
                'pe_type_code'  => $typeCode,
                'pe_type_name'  => $skuName,
                'pe_index'      => $peIndex,
            ]);
    
            // ✅ บันทึกข้อมูลลง EmpInOut
            EmpInOut::create([
                'eio_emp_group'    => $request->wip_empgroup_id, 
                'eio_working_id'   => $request->wp_working_id,  
                'eio_input_amount' => $wipAmount,              
                'eio_line'         => $line,                  
                'eio_division'     => 'QC',                   
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
                'message' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่'
            ], 500);
        }
    }
    public function checkDuplicateBarcode($barcode)
    {
        // ✅ ตรวจสอบว่าบาร์โค้ดซ้ำหรือไม่
        $existingWip = Wipbarcode::where('wip_barcode', $barcode)->exists();
        
        if ($existingWip) {
            return response()->json([
                'status' => 'duplicate',
                'title' => 'บาร์โค้ดซ้ำ',
                'message' => 'บาร์โค้ดนี้มีอยู่ในระบบแล้ว กรุณาลองใหม่'
            ], 200);
        }
    
        return response()->json(['status' => 'not_duplicate'], 200);
    }
     

public function checkSku($skuCode)
{
    // ค้นหา SKU_CODE ในตาราง SKUMASTER
    $sku = Skumaster::where('SKU_CODE', $skuCode)->first();

    if (!$sku) {
        return response()->json([
            'status' => 'not_found',
            'message' => 'ไม่พบชนิดสินค้า'
        ], 404);
    }

    return response()->json([
        'status' => 'found',
        'message' => 'พบชนิดสินค้า'
    ], 200);
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

    $lineColor = $this->colorline($line);

    // ✅ ค้นหา WorkProcess ตาม id และ line
    $workprocess = WorkProcessQC::where('id', $id)
                                ->where('line', $line)
                                ->firstOrFail();

    // ✅ ดึงข้อมูล `wip_working`
    $workdetail = WipWorking::findOrFail($id);
    $workpgroup = $workdetail->ww_group;
    $workstatus = $workdetail->ww_status;
    $workdate = $workdetail->ww_lot_date;
    $workline = $workdetail->ww_line;

    // ✅ ดึงข้อมูลบาร์โค้ด
    $wipBarcodes = Wipbarcode::where('wip_working_id', $id)->get();
    $wipIds = $wipBarcodes->pluck('wip_id')->toArray();

    // ✅ ดึงข้อมูล NG
    $ngData = AmountNg::whereIn('amg_wip_id', $wipIds)
                        ->pluck('amg_amount', 'amg_wip_id')
                        ->toArray();

    // ✅ ดึงข้อมูลอื่น ๆ
    $totalWipAmount = $wipBarcodes->sum('wip_amount');
    $listNgAll = Listngall::where('lng_status', 1)->get();
    $productTypes = ProductTypeEmp::where('pe_working_id', $id)->first();
    $peTypeCode = $productTypes ? $productTypes->pe_type_code : null;
    $brandLists = BrandList::select('bl_id', 'bl_name')->get();
    $wipSkuNames = Wipbarcode::where('wip_working_id', $id)->pluck('wip_sku_name');

    // ✅ ดึงข้อมูล `brands`
    $brandsLots = Brand::where('brd_working_id', $id)
    ->select('brd_id', 'brd_lot', 'brd_amount', 'brd_outfg_date', 'brd_status') // ✅ ดึง brd_status
    ->get();



    // ✅ ตรวจสอบว่ามี `$brd_id` หรือไม่
    $lot = $brd_id 
    ? Brand::where('brd_id', $brd_id)
            ->select('brd_id', 'brd_lot', 'brd_amount', 'brd_outfg_date', 'brd_status')
            ->first()
    : $brandsLots->where('brd_id', $brd_id)->first();


// ดึงค่า brd_status ที่ตรงกับ brd_lot โดยใช้ first()
$brd_lot = $lot ? $lot->brd_lot : null;
$brd_status = $lot ? Brand::where('brd_lot', $lot->brd_lot)->value('brd_status') : null;


    // ✅ ดึง `bl_code` ตาม `brd_id`
    $brand = $lot 
        ? Brand::where('brd_id', $lot->brd_id)->first()
        : Brand::where('brd_working_id', $id)->first();

    $brandList = $brand 
        ? BrandList::where('bl_id', $brand->brd_brandlist_id)->first()
        : null;

    $brdAmount = $brand ? $brand->brd_amount : null;

    // ✅ ค้นหา Wipbarcode ที่มี wip_working_id ตรงกับ $id
    $wipBarcodesByWorkingId = Wipbarcode::where('wip_working_id', $id)->pluck('wip_id')->toArray();

    // ✅ ดึง Wipbarcode ตาม wip_id ที่ได้มา
    $wipBarcodesFiltered = Wipbarcode::whereIn('wip_id', $wipBarcodesByWorkingId)->get();
    dd($brandsLots);

    return view('datawip', [
        'workprocess'       => $workprocess,
        'line'              => $line,
        'work_id'           => $id,
        'wipBarcodes'       => $wipBarcodes,
        'totalWipAmount'    => $totalWipAmount,
        'listNgAll'         => $listNgAll,
        'productTypes'      => $productTypes,
        'totalNgAmounts'    => $ngData,
        'brandLists'        => $brandLists,
        'wipSkuNames'       => $wipSkuNames,
        'brandsLots'        => $brandsLots,
        'workdetail'        => $workdetail,
        'brandList'         => $brandList,
        'peTypeCode'        => $peTypeCode,
        'brdAmount'         => $brdAmount,
        'lot'               => $lot,
        'brd_lot'           => $brd_lot,
        'brd_status'        => $brd_status, // ✅ ส่งค่า brd_status ไปยัง Blade
        'wipBarcodesFiltered' => $wipBarcodesFiltered,
        'lineColor' => $lineColor // ✅ ส่งค่าสีไปยัง View

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
    // ✅ ค้นหา `Brand` โดยใช้ `brd_id`
    $brand = Brand::where('brd_id', $brd_id)->first();

    if (!$brand) {
        return response()->json(['error' => 'ไม่พบข้อมูล Brand'], 404);
    }

    // ✅ ค้นหา `BrandList` โดยใช้ `brd_brandlist_id` ที่ได้จาก `Brand`
    $brandList = BrandList::where('bl_id', $brand->brd_brandlist_id)->first();

    if (!$brandList) {
        return response()->json(['error' => 'ไม่พบข้อมูล BrandList'], 404);
    }

    // ✅ ดึงค่า `bl_code` จาก `BrandList`
    $bl_code = $brandList->bl_code ?? 'N/A';
    $bl_name = $brandList->bl_name ?? 'N/A';

    // ✅ ดึงค่า `pe_type_code`
    $peTypeCode = ProductTypeEmp::where('pe_working_id', $work_id)->value('pe_type_code') ?? 'N/A';

    // ✅ ดึงค่า `brd_lot`, `brd_amount`, `brd_checker` โดยใช้ `brd_id`
    $brandData = Brand::where('brd_id', $brd_id)->first(['brd_lot', 'brd_amount', 'brd_checker']);

    $brd_lot = $brandData->brd_lot ?? 'N/A';
    $brd_amount = $brandData->brd_amount ?? 'N/A';
    $brd_checker = $brandData->brd_checker ?? 'N/A';

    // ✅ ดึงค่า `ww_line` และตัดตัวอักษร `L` ออก
    $ww_line = WipWorking::where('ww_id', $work_id)->value('ww_line') ?? 'N/A';
    $ww_line = preg_replace('/^L/', '', $ww_line); // ตัด `L` ถ้ามี

    // ✅ ดึงค่า `wip_sku_name` จาก `wipbarcodes` โดยใช้ `wip_working_id`
    $wip_sku_name = Wipbarcode::where('wip_working_id', $work_id)->value('wip_sku_name') ?? 'N/A';

    // ✅ ค้นหา `eio_emp_group` จาก `EmpInOut` ที่มี `eio_working_id` ตรงกับ `work_id`
    $eio_emp_group = EmpInOut::where('eio_working_id', $work_id)->value('eio_emp_group') ?? 'N/A';

    // ✅ ค้นหา `emp1` และ `emp2` จาก `GroupEmp` โดยใช้ `eio_emp_group` เป็น `id`
    $groupEmp = GroupEmp::where('id', $eio_emp_group)->first(['emp1', 'emp2']);

    $emp1 = $groupEmp->emp1 ?? 'N/A';
    $emp2 = $groupEmp->emp2 ?? 'N/A';

    // ✅ สร้าง QR Code ตามรูปแบบ
    if ($brd_amount < 10) {
        $qrcode = "B" . $ww_line . $bl_code . "-" . $peTypeCode . $brd_lot . '00' . $brd_amount;
    } elseif ($brd_amount < 100) {
        $qrcode = "B" . $ww_line . $bl_code . "-" . $peTypeCode . $brd_lot . '0' . $brd_amount;
    } else {
        $qrcode = "B" . $ww_line . $bl_code . "-" . $peTypeCode . $brd_lot . $brd_amount;
    }

    // ✅ ส่งข้อมูลไปยัง View
    return view('template.tagfg', compact(
        'brandList', 'brand', 'work_id', 'line', 'bl_code', 
        'peTypeCode', 'bl_name', 'brd_lot', 'brd_amount', 
        'ww_line', 'qrcode', 'wip_sku_name', 'brd_checker', 
        'emp1', 'emp2'
    ));
}





public function taghd($line, $work_id)
{
    // ✅ ตัดอักษร 'L' ออกจาก `$line` (เช่น 'L1' -> '1')
    $line_con = str_replace('L', '', $line);
    $sizearr = $this->productsize();

    // ✅ กำหนดสีของไลน์
    $lineColor = $this->colorline($line_con);

    // ✅ ดึงข้อมูลจาก WipHolding ตาม work_id
    $wipHoldings = WipHolding::where('wh_working_id', $work_id)
                              ->select('wh_barcode', 'wh_lot')
                              ->get();

    // ✅ ดึงค่า `pe_type_code` และ `pe_type_name` จาก `product_type_emps`
    $productType = ProductTypeEmp::where('pe_working_id', $work_id)
                                  ->select('pe_type_code', 'pe_type_name')
                                  ->first();

    $peTypeCode = $productType ? $productType->pe_type_code : null;
    $peTypeName = $productType ? $productType->pe_type_name : null;

    // ✅ ดึงค่า `ww_group` จาก `wip_working`
    $wipWorking = WipWorking::where('ww_id', $work_id)
                            ->select('ww_group')
                            ->first();

    $wwGroup = $wipWorking ? $wipWorking->ww_group : null;

    // ✅ ดึงค่า `wh_lot` จาก `wip_holding`
    $wipLot = WipHolding::where('wh_working_id', $work_id)
                        ->select('wh_lot')
                        ->first();

    $whLot = $wipLot ? $wipLot->wh_lot : null;

    // ✅ ดึงค่าผลรวม `wip_amount`
    $totalWipAmount = Wipbarcode::where('wip_working_id', $work_id)
                                ->sum('wip_amount');

    // ✅ ค้นหา `eio_emp_group` จาก `emp_in_outs`
    $empInOut = EmpInOut::where('eio_working_id', $work_id)
                         ->select('eio_emp_group')
                         ->first();

    $eioEmpGroup = $empInOut ? $empInOut->eio_emp_group : null;

    // ✅ ค้นหา `emp1` และ `emp2` จาก `group_emp`
    $groupEmp = GroupEmp::where('id', $eioEmpGroup)
                        ->select('emp1', 'emp2')
                        ->first();

    $emp1 = $groupEmp ? $groupEmp->emp1 : null;
    $emp2 = $groupEmp ? $groupEmp->emp2 : null;

    // ✅ ค้นหา `brd_checker` จาก `brands`
    $brand = Brand::where('brd_working_id', $work_id)
                  ->select('brd_checker')
                  ->first();

    $brdChecker = $brand ? $brand->brd_checker : null;

    // ✅ ค้นหาข้อมูลจาก `wip_summary` โดยใช้ `ws_working_id`
    $wipSummary = WipSummary::where('ws_working_id', $work_id)
                            ->select('ws_holding_amount', 'ws_ng_amount')
                            ->first();

    $wsHoldingAmount = $wipSummary ? $wipSummary->ws_holding_amount : 0;
    $wsNgAmount = $wipSummary ? $wipSummary->ws_ng_amount : 0;

    // ✅ ส่งข้อมูลไปยัง Blade Template
    return view('template.taghd', [
        'colorline'        => $lineColor,  // ✅ ใช้สีจาก `$lineColor`
        'wipHoldings'      => $wipHoldings, 
        'work_id'          => $work_id,
        'line'             => $line,
        'peTypeCode'       => $peTypeCode, 
        'peTypeName'       => $peTypeName, 
        'wwGroup'          => $wwGroup,    
        'whLot'            => $whLot,      
        'totalWipAmount'   => $totalWipAmount,
        'emp1'             => $emp1, 
        'emp2'             => $emp2,
        'brdChecker'       => $brdChecker,
        'wsHoldingAmount'  => $wsHoldingAmount, 
        'wsNgAmount'       => $wsNgAmount,
        'peTypeCode' => $peTypeCode, // ✅ ส่ง `$peTypeCode` ไปด้วย
        'sizearr' => $sizearr, // ✅ ส่ง `$sizearr` ไปที่ Blade

    ]);
}



public function colorline($line_con)
{
    // ✅ แปลงค่าให้เป็นตัวพิมพ์ใหญ่ และลบ 'L' ถ้ามี
    $cleanLine = strtoupper(str_replace('L', '', $line_con));

    switch ($cleanLine) {
        case '1':
            return '#92d050'; // สีเขียวอ่อน
        case '2':
            return '#ffff00'; // สีเหลือง
        case '3':
            return '#00b0f0'; // สีฟ้า
        default:
            return ''; // ไม่มีสี
    }
}



public function endprocess(Request $request, $line, $work_id)
{
    try {
        // ✅ เริ่มต้น Transaction
        return DB::transaction(function () use ($request, $line, $work_id) {
            // ตรวจสอบว่า work_id มีอยู่ในตาราง WorkProcessQC หรือไม่
            $workProcess = WorkProcessQC::where('id', $work_id)->first();
            if (!$workProcess) {
                return response()->json(['error' => 'ไม่พบข้อมูล WorkProcessQC'], 404);
            }

            // ✅ ตรวจสอบค่าที่ได้รับจาก request
            $validatedData = $request->validate([
                'ws_output_amount' => 'required|numeric|min:1',
                'ws_input_amount' => 'required|numeric|min:1',
                'ws_holding_amount' => 'required|numeric|min:0',
                'ws_ng_amount' => 'required|numeric|min:0',
                'ws_working_id' => 'required|numeric',
                'wh_working_id' => 'required|numeric',
                'wh_lot' => 'required|string',
            ], [
                'required' => 'ข้อมูลนี้จำเป็นต้องกรอก',
                'numeric' => 'ค่าต้องเป็นตัวเลข',
                'min' => 'ค่าต้องมากกว่า 0'
            ]);

            // กำหนดวันที่ปัจจุบัน
            $enddate = Carbon::now('Asia/Bangkok');

            // คำนวณ ws_index และ wh_index
            $wsIndex = WipSummary::max('ws_index') + 1 ?? 1;
            $whIndex = WipHolding::max('wh_index') + 1 ?? 1;

            // ✅ คำนวณ `lothdgenerator`
            $workdate = $workProcess->date;
            $workpgroup = $workProcess->group;
            $lothdcheck = WipHolding::where('wh_lot', $request->input('wh_lot'))->count();

            $lothdgenerator = date('ymd', strtotime($workdate)) .
                            substr($workpgroup,1,1) .
                            substr($workpgroup,1,1) .
                            str_pad($lothdcheck + 1, 2, '0', STR_PAD_LEFT);

            // ✅ ตรวจสอบว่า line มี 'L' นำหน้าหรือไม่
            $formattedLine = str_starts_with($line, 'L') ? substr($line, 1, 1) : $line;

            // ✅ คำนวณ `hdbarcode`
            $holding = $request->input('ws_holding_amount');
            $typecode = $workProcess->type_code;

            if ($holding !== null && is_numeric($holding)) {
                if ($holding < 100 && $holding > 10) {
                    $hdbarcode = 'B' . $formattedLine . '99-' . $typecode . $lothdgenerator . '0' . $holding;
                } elseif ($holding < 10) {
                    $hdbarcode = 'B' . $formattedLine . '99-' . $typecode . $lothdgenerator . '00' . $holding;
                } else {
                    $hdbarcode = 'B' . $formattedLine . '99-' . $typecode . $lothdgenerator . $holding;
                }
            } else {
                return response()->json(['error' => 'ค่าคงค้าง (HD) ไม่ถูกต้อง'], 422);
            }

            // ✅ บันทึกข้อมูลใน WipSummary
            $sum = new WipSummary();
            $sum->ws_output_amount = $validatedData['ws_output_amount'];
            $sum->ws_input_amount = $validatedData['ws_input_amount'];
            $sum->ws_working_id = $validatedData['ws_working_id'];
            $sum->ws_holding_amount = $validatedData['ws_holding_amount'];
            $sum->ws_ng_amount = $validatedData['ws_ng_amount'];
            $sum->ws_index = $wsIndex;
            $sum->save();

            // ✅ บันทึกข้อมูลใน WipHolding
            $holdingEntry = new WipHolding();
            $holdingEntry->wh_working_id = $validatedData['wh_working_id'];
            $holdingEntry->wh_barcode = $hdbarcode;
            $holdingEntry->wh_lot = $lothdgenerator;
            $holdingEntry->wh_index = $whIndex;
            $holdingEntry->save();

            // ✅ อัปเดตสถานะของ WorkProcessQC เป็น "จบการทำงาน"
            $workProcess->update([
                'status' => 'จบการทำงาน',
                'date' => $enddate
            ]);

            // ✅ อัปเดตค่า ww_end_date ใน WipWorking เป็นเวลาปัจจุบันของประเทศไทย
            $wipWorking = WipWorking::find($work_id);
            if ($wipWorking) {
                $wipWorking->update([
                    'ww_end_date' => $enddate->format('Y-m-d H:i:s')
                ]);
            }

            // ✅ Transaction จะถูก Commit โดยอัตโนมัติที่นี่
            return response()->json([
                'wh_id' => $holdingEntry->wh_id,
                'ws_holding_amount' => $sum->ws_holding_amount,
                'hd_barcode' => $hdbarcode,
                'message' => 'กระบวนการผลิตเสร็จสิ้นและอัปเดตสถานะเรียบร้อย',
                'redirect_url' => route('taghd', ['line' => 'L' . $formattedLine, 'work_id' => $work_id])
            ]);
        });

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'ข้อมูลไม่ครบถ้วน',
            'details' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'เกิดข้อผิดพลาดในเซิร์ฟเวอร์',
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
}



public function thaimonth(){
    return [
        "01" => "มกราคม",
        "02" => "กุมภาพันธ์",
        "03" => "มีนาคม",
        "04" => "เมษายน",
        "05" => "พฤษภาคม",
        "06" => "มิถุนายน",
        "07" => "กรกฎาคม",
        "08" => "สิงหาคม",
        "09" => "กันยายน",
        "10" => "ตุลาคม",
        "11" => "พฤศจิกายน",
        "12" => "ธันวาคม"
    ];
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
public function qrcodeinterface($qrcode)
{
    $qr = $qrcode;

    // แยกค่าจาก QR Code
    $subbrand = substr($qrcode, 2, 2);    // ดึง 2 ตัวอักษรที่ 3-4 (รหัสแบรนด์)
    $subproduct = substr($qrcode, 0, 11); // ดึง 11 ตัวอักษรแรก (รหัสสินค้า)

    // ค้นหาข้อมูลแบรนด์โดยใช้ Eloquent
    $brand = BrandList::where('bl_code', $subbrand)->first();

    // ค้นหาข้อมูลสินค้าโดยใช้ Eloquent
    $product = Skumaster::where('SKU_CODE', $subproduct)->first();

    return view('qrcodeinterface', [
        'qr'        => $qr,
        'subbrand'  => $subbrand,
        'brand'     => $brand,
        'product'   => $product,
    ]);
}







    public function insertcheckcsvqrcode(Request $request)
    {
        // รับค่าบาร์โค้ดจากฟอร์ม
        $barcode = $request->input('ccw_barcode');

        // ตรวจสอบว่าได้ค่าหรือไม่
        if (!$barcode) {
            Log::warning("Barcode is missing in request.");
            return back()->with('error', 'ไม่พบข้อมูลบาร์โค้ด'); // ✅ กลับไปหน้าเดิม
        }

        Log::info("Received Barcode: " . $barcode);

        // ตรวจสอบว่าบาร์โค้ดมีอยู่ในระบบหรือไม่
        $checkExist = CheckCsvWh::where('ccw_barcode', $barcode)->exists();
        $CsvLine = substr($barcode, 1, 1); // ดึงค่าตัวอักษรที่ 2 เพื่อใช้ตรวจสอบประเภท

        try {
            DB::beginTransaction(); // ✅ เริ่ม Transaction

            if (substr($barcode, 0, 2) == 'BX') {
                $index = CheckCsvWhIndex::count();

                // สร้างข้อมูลใหม่ถ้าไม่มีข้อมูลซ้ำ
                $csv = CheckCsvWh::firstOrCreate(
                    ['ccw_barcode' => $barcode],
                    [
                        'ccw_lot' => substr($barcode, 11, 10),
                        'ccw_amount' => substr($barcode, 21, 3),
                        'ccw_index' => $index,
                    ]
                );

                DB::commit(); // ✅ บันทึก Transaction
                return back()->with('success', $csv->ccw_lot . " เข้าสู่คลังแล้ว"); // ✅ กลับไปหน้าเดิม
            }

            // ถ้าไม่ใช่ BX และยังไม่มีอยู่ในระบบ
            if (!$checkExist) {
                $index = CheckCsvWhIndex::count();

                // บันทึกข้อมูลใหม่
                $csv = CheckCsvWh::create([
                    'ccw_barcode' => $barcode,
                    'ccw_lot' => substr($barcode, 11, 10),
                    'ccw_amount' => substr($barcode, 21, 3),
                    'ccw_index' => $index,
                ]);

                // แปลงค่า CsvLine
                switch ($CsvLine) {
                    case '1':
                        $CsvLine = 'L1';
                        break;
                    case '2':
                        $CsvLine = 'L2';
                        break;
                    default:
                        $CsvLine = 'L3';
                }

                // ✅ ใช้ JOIN แทน whereHas เพื่อป้องกัน ORDER BY Error
                $updatestatusfg = Brand::join('wip_working', 'brands.brd_working_id', '=', 'wip_working.ww_id')
                    ->where('brands.brd_lot', $csv->ccw_lot)
                    ->where('wip_working.ww_line', $CsvLine)
                    ->select('brands.*')
                    ->first();

                // ตรวจสอบว่าพบข้อมูลก่อนอัปเดต
                if ($updatestatusfg) {
                    $updatestatusfg->update(['brd_status' => '2']);
                } else {
                    Log::warning("No matching Brand found for brd_lot: " . $csv->ccw_lot);
                }

                DB::commit(); // ✅ บันทึก Transaction
                return back()->with('success', $csv->ccw_lot . " เข้าสู่คลังแล้ว"); // ✅ กลับไปหน้าเดิม
            }

            DB::rollBack(); // ❌ หากเกิดปัญหา ให้ย้อนกลับการเปลี่ยนแปลง
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error inserting barcode: " . $e->getMessage());

            return back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'); // ✅ กลับไปหน้าเดิม
        }
    }
    public function insertcheckcsvqrcodewithdefect(Request $request)
    {
        // รับค่าบาร์โค้ดจากฟอร์ม
        $barcode = $request->input('ccw_barcode');
    
        // ตรวจสอบว่าได้ค่าหรือไม่
        if (!$barcode) {
            Log::warning("Barcode is missing in request.");
            return back(); // ✅ กลับไปหน้าเดิม
        }
    
        Log::info("Received Barcode: " . $barcode);
    
        // ตรวจสอบว่าบาร์โค้ดมีอยู่ในระบบหรือไม่
        $checkExist = CheckCsvWh::where('ccw_barcode', $barcode)->exists();
        $CsvLine = substr($barcode, 1, 1); // ดึงค่าตัวอักษรที่ 2 เพื่อใช้ตรวจสอบประเภท
    
        try {
            DB::beginTransaction(); // ✅ เริ่ม Transaction
    
            if (substr($barcode, 0, 2) == 'BX') {
                $index = CheckCsvWhIndex::count();
    
                // สร้างข้อมูลใหม่ถ้าไม่มีข้อมูลซ้ำ
                $csv = CheckCsvWh::firstOrCreate(
                    ['ccw_barcode' => $barcode],
                    [
                        'ccw_lot' => substr($barcode, 11, 10),
                        'ccw_amount' => substr($barcode, 21, 3),
                        'ccw_index' => $index,
                    ]
                );
    
                // เพิ่มข้อมูล defect ลงใน warehouse_return_to_qc
                WarehouseReturnToQc::create([
                    'wrtc_barcode' => $barcode,
                    'wrtc_description' => $request->input('wrtc_description'),
                    'wrtc_remark' => $request->input('wrtc_remark'),
                    'wrtc_date' => now(),
                ]);
    
                DB::commit(); // ✅ บันทึก Transaction
                return back(); // ✅ กลับไปหน้าเดิม
            }
    
            // ถ้าไม่ใช่ BX และยังไม่มีอยู่ในระบบ
            if (!$checkExist) {
                $index = CheckCsvWhIndex::count();
    
                // บันทึกข้อมูลใหม่
                $csv = CheckCsvWh::create([
                    'ccw_barcode' => $barcode,
                    'ccw_lot' => substr($barcode, 11, 10),
                    'ccw_amount' => substr($barcode, 21, 3),
                    'ccw_index' => $index,
                ]);
    
                // เพิ่มข้อมูล defect ลงใน warehouse_return_to_qc
                WarehouseReturnToQc::create([
                    'wrtc_barcode' => $barcode,
                    'wrtc_description' => $request->input('wrtc_description'),
                    'wrtc_remark' => $request->input('wrtc_remark'),
                    'wrtc_date' => now(),
                ]);
    
                // แปลงค่า CsvLine
                switch ($CsvLine) {
                    case '1':
                        $CsvLine = 'L1';
                        break;
                    case '2':
                        $CsvLine = 'L2';
                        break;
                    default:
                        $CsvLine = 'L3';
                }
    
                // ✅ ใช้ JOIN แทน whereHas เพื่อป้องกัน ORDER BY Error
                $updatestatusfg = Brand::join('wip_working', 'brands.brd_working_id', '=', 'wip_working.ww_id')
                    ->where('brands.brd_lot', $csv->ccw_lot)
                    ->where('wip_working.ww_line', $CsvLine)
                    ->select('brands.*')
                    ->first();
    
                // ตรวจสอบว่าพบข้อมูลก่อนอัปเดต
                if ($updatestatusfg) {
                    $updatestatusfg->update(['brd_status' => '2']);
                } else {
                    Log::warning("No matching Brand found for brd_lot: " . $csv->ccw_lot);
                }
    
                DB::commit(); // ✅ บันทึก Transaction
                return back(); // ✅ กลับไปหน้าเดิม
            }
    
            DB::rollBack(); // ❌ หากเกิดปัญหา ให้ย้อนกลับการเปลี่ยนแปลง
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error inserting barcode: " . $e->getMessage());
    
            return back(); // ✅ กลับไปหน้าเดิม
        }
    }
   


    public function endtimeinterface(Request $request, $line, $index, $workprocess)
    {
        // ✅ ตรวจสอบว่ามีค่า $workprocess หรือไม่
        if (empty($workprocess)) {
            return back()->with('error', 'ไม่พบค่า workprocess');
        }
    
        // ✅ แปลงค่าที่ส่งมาให้เป็น Array ถ้ามีหลายค่า
        $workprocessIds = explode(',', $workprocess);
    
        // ✅ ตรวจสอบว่า $workprocessIds แปลงถูกต้องหรือไม่
        if (empty($workprocessIds) || count($workprocessIds) == 0) {
            return back()->with('error', 'ไม่พบ workprocess ที่ถูกต้อง');
        }
    
        // ✅ ค้นหา WorkprocessTemp ตาม line และ workprocess_id ที่ตรงกัน
        $wwt_ids = WorkprocessTemp::where('line', $line)
            ->whereIn('workprocess_id', $workprocessIds)
            ->pluck('wwt_id')
            ->unique()
            ->values(); 
    
        // ✅ ตรวจสอบว่า $wwt_ids มีค่าหรือไม่
        if ($wwt_ids->isEmpty()) {
            return back()->with('error', 'ไม่พบ WWT ID ที่ตรงกับเงื่อนไข');
        }
    
        return view('endtimeinterface', [
            'line'        => $line,
            'index'       => $index,
            'workprocess' => $workprocessIds,
            'wwt_ids'     => $wwt_ids
        ]);
    }
    
    
    
    
    public function csvendtime($line, $index, $workprocess)
    {
        // ✅ ตัด 'L' ออกจาก $line ถ้ามี
        $cleanLine = str_starts_with($line, 'L') ? substr($line, 1) : $line;
    
        // ✅ แปลง workprocess เป็น Array
        $workprocessIds = array_filter(explode(',', $workprocess), 'is_numeric'); // 🔥 ทำให้แน่ใจว่าเป็นตัวเลข
    
        if (empty($workprocessIds)) {
            return response()->json(['error' => 'ไม่พบข้อมูล workprocess'], 400);
        }
    
        // ✅ ดึงข้อมูลกลุ่ม (Group) และกำหนดชื่อไฟล์
        $workpgrouplot = WipWorking::whereIn('ww_id', $workprocessIds)
            ->where('ww_line', 'LIKE', 'L' . $cleanLine) // ✅ เช็คว่า ww_line มี L นำหน้า
            ->value('ww_group') ?? 'UNKNOWN';
    
        // ✅ ดึงวันที่จาก Ziptape เพื่อใช้เป็นชื่อไฟล์
        $newcsvtime = now()->format('dmYHi');
    
        $filename = "PQC_{$newcsvtime}_{$workpgrouplot}.csv";
    
        // ✅ ดึงข้อมูลจาก wipbarcodes ตาม workprocess ID
        $wipData = Wipbarcode::whereIn('wip_working_id', $workprocessIds)
            ->get(['wip_barcode', 'wip_amount', 'wip_working_id'])
            ->map(function ($wip) {
                return [
                    'wip_barcode' => substr($wip->wip_barcode, 0, 11), // ตัดเหลือ 11 ตัวแรก
                    'wip_amount' => $wip->wip_amount,
                    'wip_working_id' => $wip->wip_working_id
                ];
            });
    
        // ✅ ดึง ww_division จาก wip_working
        $wwData = WipWorking::whereIn('ww_id', $workprocessIds)
            ->pluck('ww_division', 'ww_id');
    
        // ✅ ดึง brd_lot จาก brands
        $brdData = Brand::whereIn('brd_working_id', $workprocessIds)
            ->pluck('brd_lot', 'brd_working_id');
    
        // ✅ รวมข้อมูล
        $result = $wipData->map(function ($wip) use ($wwData, $brdData) {
            return [
                $wip['wip_barcode'],
                $brdData[$wip['wip_working_id']] ?? 'N/A',
                $wip['wip_amount'],
                $wwData[$wip['wip_working_id']] ?? 'N/A'
            ];
        });
    
        // ✅ สร้างไฟล์ CSV (ไม่มี Header)
        return response()->streamDownload(function () use ($result) {
            $file = fopen('php://output', 'w');
            foreach ($result as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=windows-874'
        ]);
    }
    
    
    
    public function dowloadcsvendtime($line, $wwt_id)
{
    // ✅ ตัด 'L' ถ้ามีใน $line
    $cleanLine = str_starts_with($line, 'L') ? substr($line, 1) : $line;

    // ✅ ดึง workprocess_id จาก WorkprocessTemp
    $workprocessIds = WorkprocessTemp::where('wwt_id', $wwt_id)
        ->where('line', $cleanLine)
        ->pluck('workprocess_id')
        ->toArray();

    if (empty($workprocessIds)) {
        return response()->json(['error' => 'ไม่พบข้อมูล workprocess'], 400);
    }

    // ✅ ค้นหา WipWorking โดยใช้ workprocess_id และตรวจสอบ ww_line ให้ตรงกับ $line
    $workpgrouplot = WipWorking::whereIn('ww_id', $workprocessIds)
        ->where('ww_line', $line) // ✅ ตรวจสอบว่า ww_line ตรงกัน
        ->value('ww_group') ?? 'UNKNOWN';

    // ✅ กำหนดชื่อไฟล์ CSV
    $newcsvtime = now()->format('dmYHi');
    $filename = "PQC_{$newcsvtime}_{$workpgrouplot}.csv";

    // ✅ ดึงข้อมูลจาก wipbarcodes ตาม workprocess ID
    $wipData = Wipbarcode::whereIn('wip_working_id', $workprocessIds)
        ->get(['wip_barcode', 'wip_amount', 'wip_working_id'])
        ->map(function ($wip) {
            return [
                'wip_barcode' => substr($wip->wip_barcode, 0, 11), // ตัดเหลือ 11 ตัวแรก
                'wip_amount' => $wip->wip_amount,
                'wip_working_id' => $wip->wip_working_id
            ];
        });

    // ✅ ดึง ww_division จาก wip_working
    $wwData = WipWorking::whereIn('ww_id', $workprocessIds)
        ->pluck('ww_division', 'ww_id');

    // ✅ ดึง brd_lot จาก brands
    $brdData = Brand::whereIn('brd_working_id', $workprocessIds)
        ->pluck('brd_lot', 'brd_working_id');

    // ✅ รวมข้อมูล
    $result = $wipData->map(function ($wip) use ($wwData, $brdData) {
        return [
            $wip['wip_barcode'],
            $brdData[$wip['wip_working_id']] ?? 'N/A',
            $wip['wip_amount'],
            $wwData[$wip['wip_working_id']] ?? 'N/A'
        ];
    });

    // ✅ สร้างไฟล์ CSV (ไม่มี Header)
    return response()->streamDownload(function () use ($result) {
        $file = fopen('php://output', 'w');
        foreach ($result as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }, $filename, [
        'Content-Type' => 'text/csv; charset=windows-874'
    ]);
}

    
    

    public function endworktime(Request $request, $line)
    {
        try {
            \Log::info('Received Data:', $request->all());
    
            // ✅ Validate ข้อมูลที่ส่งมา
            $request->validate([
                'wwt_status' => 'required|numeric',
                'wz_amount'  => 'required|numeric|min:0',
                'wwd_amount' => 'required|numeric|min:0',
            ]);
    
            DB::beginTransaction();
    
            $lineFormatted = $line;
    
            // ✅ ตรวจสอบว่า WorkProcessQC มีข้อมูลหรือไม่
            $workprocess = WorkProcessQC::where('line', $lineFormatted)
                ->where('status_qc', '<>', 'จบกะทำงาน')
                ->get();
    
            \Log::info('Workprocess Data:', $workprocess->isNotEmpty() ? $workprocess->toArray() : ['message' => 'Not Found']);
    
            if ($workprocess->isEmpty()) {
                return response()->json(['error' => 'ไม่มีข้อมูล workprocess ที่ต้องอัปเดต'], 400);
            }
    
            // ✅ บันทึก WipWorktime
            $checktimeindex = WipWorktime::where('wwt_line', $lineFormatted)->count();
    
            $end = new WipWorktime();
            $end->wwt_index = $checktimeindex;
            $end->wwt_status = $request->wwt_status;
            $end->wwt_line = $lineFormatted;
            $end->wwt_date = Carbon::now();
            $end->save();
    
            $wwt_id = $end->wwt_id;
            \Log::info('WipWorktime Saved:', ['wwt_id' => $wwt_id, 'wwt_index' => $end->wwt_index]);
    
            // ✅ บันทึกข้อมูลลง workprocess_temp
            foreach ($workprocess as $wpqc) {
                WorkprocessTemp::create([
                    'workprocess_id' => $wpqc->id,
                    'line' => $lineFormatted,
                    'wwt_id' => $wwt_id,
                ]);
            
                WipWorking::where('ww_id', $wpqc->id)
                    ->update(['ww_wwt_index' => $end->wwt_index]);
            }
    
            // ✅ อัปเดต status_qc เป็น "จบกะทำงาน"
            WorkProcessQC::where('line', $lineFormatted)
                ->whereIn('id', $workprocess->pluck('id'))
                ->update(['status_qc' => 'จบกะทำงาน']);
    
            // ✅ บันทึกข้อมูลลง wip_ziptapes
            $ziptape = new WipZiptape();
            $ziptape->wz_line = $lineFormatted;
            $ziptape->wz_worktime_id = $wwt_id;
            $ziptape->wz_amount = $request->wz_amount - 0.015;
            $ziptape->save();
            \Log::info('WipZiptape Saved:', $ziptape->toArray());
    
            // ✅ คำนวณ lotc
            $lotccheck = WipWasteDetail::where('wwd_line', $lineFormatted)
                            ->where('wwd_index', $checktimeindex)
                            ->count();
            $lotc = date('ymd') . 'CC' . str_pad($lotccheck + 1, 2, '0', STR_PAD_LEFT);
    
          // ✅ ดึง workprocessIds จาก WorkprocessTemp
$workprocessIds = WorkprocessTemp::where('wwt_id', $wwt_id)
->where('line', $lineFormatted)
->pluck('workprocess_id')
->toArray();

if (empty($workprocessIds)) {
\Log::warning("⚠️ ไม่พบ workprocessIds สำหรับ wwt_id: {$wwt_id}");
return response()->json(['error' => 'Workprocess IDs not found'], 404);
}

// ✅ ค้นหา ProductTypeEmp โดยใช้ workprocessIds
$getproduct = ProductTypeEmp::whereIn('pe_working_id', $workprocessIds)->first();

if (!$getproduct) {
\Log::warning("⚠️ ไม่พบ ProductTypeEmp สำหรับ workprocessIds: " . implode(',', $workprocessIds));
return response()->json(['error' => 'Product type not found'], 404);
}

// ✅ บันทึก Log เพื่อตรวจสอบข้อมูลที่พบ
\Log::info("✅ พบ ProductTypeEmp:", $getproduct->toArray());

    
            // ✅ บันทึกข้อมูลลง wip_waste_details
            $tagc = new WipWasteDetail();
            $tagc->wwd_line = $lineFormatted;
            $tagc->wwd_index = $checktimeindex;
            $tagc->wwt_id = $wwt_id;
            $wwd_amount = $request->wwd_amount;
    
            // ✅ ตรวจสอบค่า wwd_amount ก่อนสร้าง barcode
            $amountFormatted = str_pad($wwd_amount, 3, '0', STR_PAD_LEFT);
            $tagc->wwd_barcode = 'B' . $line . '09-' . $getproduct->pe_type_code . $lotc . $amountFormatted;
    
            $tagc->wwd_lot = $lotc;
            $tagc->wwd_amount = $wwd_amount;
            $tagc->wwd_date = Carbon::now();
            $tagc->save();
    
            \Log::info('WipWasteDetail Saved:', $tagc->toArray());
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'บันทึกข้อมูลจบกะเรียบร้อย',
                'wwt_id' => $wwt_id,
                'wwt_index' => $end->wwt_index,
                'workprocess_ids' => $workprocess->pluck('id')->toArray()
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in endworktime:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }
    





    public function workedprevious($line, $wwt_id)
    {
        $cleanLine = str_starts_with($line, 'L') ? substr($line, 1) : $line;

        // ✅ ตรวจสอบและตัดตัวอักษร 'L' ออกจาก $line (ถ้ามี)
        $cleanLine = str_starts_with($line, 'L') ? substr($line, 1) : $line;
    
        // ✅ ดึง workprocess_id จาก WorkprocessTemp ที่ wwt_id ตรงกัน
        $workprocessIds = WorkprocessTemp::where('wwt_id', $wwt_id)
            ->where('line', $cleanLine)
            ->pluck('workprocess_id')
            ->toArray();
    
        if (!empty($workprocessIds)) {
            $workProcessQC = WorkProcessQC::whereIn('id', $workprocessIds)
                ->where('line', $cleanLine) 
                ->leftJoin('product_type_emps', 'workprocess_qc.id', '=', 'product_type_emps.pe_working_id')
                ->select(
                    'workprocess_qc.id',
                    'workprocess_qc.line',
                    'workprocess_qc.group',
                    'workprocess_qc.status',
                    'workprocess_qc.date',
                    'product_type_emps.pe_type_name'
                )
                ->get()
                ->unique('id'); // ✅ ลบค่าที่ซ้ำกันโดยใช้ `id`
        } else {
            $workProcessQC = collect();
        }
    
        return view('workedprevious', compact('line', 'wwt_id', 'workProcessQC','cleanLine'));
    }
    public function getWipId(Request $request)
    {
        $barcode = $request->input('barcode');
    
        if (!$barcode) {
            return response()->json(['error' => 'Barcode is required'], 400);
        }
    
        $wip = Wipbarcode::where('wip_barcode', $barcode)->first();
    
        if (!$wip) {
            return response()->json(['error' => 'WIP ID not found'], 404);
        }
    
        return response()->json([
            'wip_id' => $wip->wip_id,
            'wip_barcode' => $wip->wip_barcode,
            'wip_amount' => $wip->wip_amount
        ]);
    }
    
    public function tagc(Request $request, $line, $wwt_id)
    {
        $thmonth = $this->thaimonth();
        $typearr = $this->typeofproduct();
        $sizearr = $this->productsize();
        $thicknessarr = $this->thickness();
        $colorline = $this->colorline($line);
    
        // ✅ แปลงค่า line -> ถอดตัวอักษร 'L' ออก
        $cleanLine = strtoupper($line);
        if (str_starts_with($cleanLine, 'L')) {
            $cleanLine = substr($cleanLine, 1);
        }
    
        // ✅ ค้นหา workprocess_id ที่ตรงกับ line และ wwt_id
        $workprocessIds = WorkprocessTemp::where('line', $cleanLine)
            ->where('wwt_id', $wwt_id)
            ->pluck('workprocess_id')
            ->toArray();
    
        // ✅ ค้นหา wwt_index ที่ตรงกับ wwt_id และ wwt_line (ไม่มี L)
        $wwtIndex = WipWorktime::where('wwt_id', $wwt_id)
            ->where('wwt_line', $cleanLine)
            ->value('wwt_index'); // ดึงค่าเดียว
    
        // ✅ ดึงข้อมูลจาก WorkProcessQC
        $tagc = WipWasteDetail::where('wwt_id', $wwt_id)
            ->where('wwd_line', $cleanLine)
            ->first();
    
        if (!empty($workprocessIds)) {
            $workProcessQC = WorkProcessQC::whereIn('id', $workprocessIds)
                ->where('line', $cleanLine)
                ->leftJoin('product_type_emps', 'workprocess_qc.id', '=', 'product_type_emps.pe_working_id')
                ->select(
                    'workprocess_qc.id',
                    'workprocess_qc.line',
                    'workprocess_qc.group',
                    'workprocess_qc.status',
                    'workprocess_qc.date',
                    'product_type_emps.pe_type_name'
                )
                ->get()
                ->unique('id');
        } else {
            $workProcessQC = collect();
        }
    
        // ✅ ตรวจสอบค่าที่ได้
        if (!$wwtIndex) {
            return back()->with('error', 'ไม่พบ wwt_index ที่ตรงกัน');
        }
    
        if (empty($workprocessIds)) {
            return back()->with('error', 'ไม่พบ workprocess_id ที่ตรงกัน');
        }
    
        // ✅ เก็บค่าก่อนหน้า (line, index, workprocess) ลงใน Session (ถ้ามี)
        session([
            'prev_line' => $cleanLine,
            'prev_index' => $wwtIndex, // ใช้ค่าที่หาได้จาก WipWorktime
            'prev_workprocess' => implode(',', $workprocessIds) // แปลงเป็น String
        ]);
    
        return view('template.tagc', [
            'thmonth'       => $thmonth,
            'typearr'       => $typearr,
            'sizearr'       => $sizearr,
            'thicknessarr'  => $thicknessarr,
            'colorline'     => $colorline,
            'line'          => $line,
            'wwt_id'        => $wwt_id,
            'workProcessQC' => $workProcessQC,
            'tagc'          => $tagc
        ]);
    }
    
    
    
    
    





    
    

}








