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
use Illuminate\Support\Facades\Http; // ✅ เพิ่มบรรทัดนี้
use Illuminate\Support\Facades\Response; // ✅ เพิ่มบรรทัดนี้
use App\Models\Listngall;



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
    
            // ✅ เช็ค WipWorking
            $wipWorking = WipWorking::find($work_id);
            if (!$wipWorking) {
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
                    'status' => 'duplicate',
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
    
            // ✅ ดึงค่า 3 ตัวท้ายของบาร์โค้ด และตัด 0 ข้างหน้า
            $wipAmount = (int) ltrim(substr($request->wip_barcode, -3), '0');
    
            // ✅ เช็คว่าค่าเกิน 100 หรือไม่
            if ($wipAmount > 100) {
                return response()->json([
                    'status' => 'error',
                    'title' => 'จำนวนเกินกำหนด',
                    'message' => 'จำนวนที่ได้จากบาร์โค้ดเกิน 100 ไม่สามารถบันทึกได้'
                ], 400);
            }
    
            // ✅ บันทึกข้อมูลลง Wipbarcode
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
                'pe_working_id' => $wipWorking->ww_id,
                'pe_type_code'  => $typeCode,
                'pe_type_name'  => $skuName,
                'pe_index'      => $peIndex,
            ]);
    
            // ✅ บันทึกข้อมูลลง EmpInOut
            EmpInOut::create([
                'eio_emp_group'    => $request->wip_empgroup_id, 
                'eio_working_id'   => $wipWorking->ww_id,  
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
                'message' => $e->getMessage(), // แสดงข้อความ Error จริง
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
        // ✅ ตรวจสอบว่ามี Wipbarcode หรือไม่
        $checkWip = Wipbarcode::where('wip_id', $id)->first();

        if (!$checkWip) {
            return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลบาร์โค้ด'], 404);
        }

        $empGroup = $checkWip->wip_empgroup_id;
        $amount = $checkWip->wip_amount;
        $wipIndex = $checkWip->wip_index; // ✅ ดึงค่า wip_index 

        // ✅ กำหนดค่าเริ่มต้นให้ $eioOutput = 0
        $eioOutput = 0;

        // ✅ ค้นหาข้อมูลเข้า-ออกของพนักงาน
        $eio = EmpInOut::where('eio_working_id', $work_id)
                       ->where('eio_emp_group', $empGroup)
                       ->first();

        if ($eio) {
            $eioId = $eio->id;
            $eioInput = $eio->eio_input_amount;
            $eioOutput = $eio->eio_output_amount;
        }

        DB::beginTransaction(); // ✅ ใช้ Transaction ป้องกันข้อมูลไม่สมบูรณ์

        // ✅ ลบข้อมูลบาร์โค้ด
        $checkWip->delete();
        Log::info('✅ ลบ Wipbarcode สำเร็จ', ['wip_id' => $id, 'wip_index' => $wipIndex]);

        // ✅ ลบข้อมูล product_type_emps ที่มี pe_index ตรงกับ wip_index
        $deletedProductTypeEmp = ProductTypeEmp::where('pe_index', $wipIndex)->delete();
        Log::info('✅ ลบข้อมูล ProductTypeEmp สำเร็จ', ['pe_index' => $wipIndex, 'deleted_rows' => $deletedProductTypeEmp]);

        // ✅ เช็คข้อมูลในกลุ่มพนักงาน
        $checkEmpGroup = Wipbarcode::where('wip_working_id', $work_id)
                                   ->where('wip_empgroup_id', $empGroup)
                                   ->exists(); // ✅ ใช้ exists() เพื่อลดการโหลดข้อมูล

        if (!$checkEmpGroup && $eioOutput <= 0) {
            // ถ้าไม่มีข้อมูลบาร์โค้ดในกลุ่มนี้ และไม่มีการเบิกจ่าย ให้ลบข้อมูลการเข้า-ออก
            if ($eio) {
                $eio->delete();
                Log::info('✅ ลบข้อมูล EmpInOut สำเร็จ', ['eio_id' => $eioId]);
            }
        } else {
            // ถ้ามีข้อมูล ให้ลดจำนวนสินค้าใน eio_input_amount
            if ($eio) {
                $eio->update(['eio_input_amount' => $eioInput - $amount]);
                Log::info('✅ อัปเดต eio_input_amount สำเร็จ', ['eio_id' => $eioId, 'new_amount' => $eioInput - $amount]);
            }
        }

        DB::commit(); // ✅ ยืนยันการลบ

        return response()->json(['success' => true, 'message' => 'ลบข้อมูลสำเร็จ'], 200);

    } catch (\Exception $e) {
        DB::rollBack(); // ❌ ย้อนค่าหากเกิดปัญหา
        Log::error('❌ เกิดข้อผิดพลาดในการลบข้อมูล', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
    }
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
        return DB::transaction(function () use ($request, $line, $work_id) {
            Log::info("🔍 ค้นหา WipWorking สำหรับ work_id: " . $work_id);
            $wipWorking = WipWorking::where('ww_id', $work_id)->first();

            if (!$wipWorking) {
                Log::error("❌ ไม่พบ WipWorking สำหรับ work_id: " . $work_id);
                return response()->json(['error' => 'ไม่พบข้อมูล WipWorking'], 404);
            }

            Log::info("✅ พบ WipWorking: ", $wipWorking->toArray());

            $enddate = Carbon::now('Asia/Bangkok');

            // ✅ อัปเดตสถานะ WipWorking เป็น 'E'
            $updated = $wipWorking->update([
                'ww_end_date' => $enddate->format('Y-m-d H:i:s'),
                'ww_status' => 'E'
            ]);

            if (!$updated) {
                Log::error("❌ อัปเดต ww_status เป็น 'E' ไม่สำเร็จสำหรับ work_id: " . $work_id);
                return response()->json(['error' => 'อัปเดต ww_status ไม่สำเร็จ'], 500);
            } else {
                Log::info("✅ อัปเดต ww_status เป็น 'E' สำเร็จสำหรับ work_id: " . $work_id);
            }

            // ✅ คำนวณค่า `ws_index` ใหม่
            $wsIndex = WipSummary::max('ws_index') + 1 ?? 1;

            // ✅ บันทึกข้อมูลลง `wip_summary`
            $wipSummary = WipSummary::create([
                'ws_output_amount' => $request->input('ws_output_amount'),
                'ws_input_amount' => $request->input('ws_input_amount'),
                'ws_working_id' => $work_id,
                'ws_holding_amount' => $request->input('ws_holding_amount'),
                'ws_ng_amount' => $request->input('ws_ng_amount'),
                'ws_index' => $wsIndex
            ]);

            if (!$wipSummary) {
                Log::error("❌ บันทึก WipSummary ไม่สำเร็จสำหรับ work_id: " . $work_id);
                return response()->json(['error' => 'ไม่สามารถบันทึกข้อมูล WipSummary'], 500);
            } else {
                Log::info("✅ บันทึก WipSummary สำเร็จสำหรับ work_id: " . $work_id, $wipSummary->toArray());
            }

            // ✅ คำนวณค่า `wh_index` ใหม่ (เริ่มที่ 1 ถ้ายังไม่มีข้อมูล)
            $whIndex = WipHolding::where('wh_working_id', $work_id)->max('wh_index') + 1 ?? 1;

            // ✅ บันทึกข้อมูลลง `wip_holding`
            $wipHolding = WipHolding::create([
                'wh_working_id' => $work_id,
                'wh_barcode' => $request->input('wh_barcode'),
                'wh_lot' => $request->input('wh_lot'),
                'wh_index' => $whIndex
            ]);

            if (!$wipHolding) {
                Log::error("❌ บันทึก WipHolding ไม่สำเร็จสำหรับ work_id: " . $work_id);
                return response()->json(['error' => 'ไม่สามารถบันทึกข้อมูล WipHolding'], 500);
            } else {
                Log::info("✅ บันทึก WipHolding สำเร็จสำหรับ work_id: " . $work_id, $wipHolding->toArray());
            }

            Log::info("💾 กำลัง commit transaction...");
            DB::commit();
            Log::info("✅ Transaction commit สำเร็จ!");

            return response()->json([
                'message' => 'กระบวนการผลิตเสร็จสิ้นและอัปเดตสถานะเรียบร้อย',
                'redirect_url' => route('taghd', ['line' => 'L' . $line, 'work_id' => $work_id])
            ]);
        });

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("❌ เกิดข้อผิดพลาดใน endprocess: " . $e->getMessage());

        return response()->json([
            'error' => 'เกิดข้อผิดพลาดในเซิร์ฟเวอร์',
            'message' => $e->getMessage()
        ], 500);
    }
}



public function updateWwStatus(Request $request)
{
    $work_id = $request->input('work_id');
    $status = $request->input('status');

    // ✅ ตรวจสอบว่า work_id มีอยู่ในฐานข้อมูล
    $wipWorking = WipWorking::where('ww_id', $work_id)->first();

    if (!$wipWorking) {
        return response()->json(['error' => 'ไม่พบข้อมูล WipWorking'], 404);
    }

    // ✅ อัปเดต ww_status เป็น "E"
    $updated = $wipWorking->update([
        'ww_status' => $status,
        'ww_end_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s')
    ]);

    if (!$updated) {
        return response()->json(['error' => 'อัปเดต ww_status ไม่สำเร็จ'], 500);
    }

    return response()->json(['message' => 'ww_status อัปเดตสำเร็จ!']);
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
    Log::info("📌 ข้อมูลที่ได้รับ:", $request->all()); // ✅ ตรวจสอบค่าทั้งหมดที่ได้รับ

    // ✅ ตรวจสอบค่าที่ต้องมี
    $requiredFields = ['brd_lot', 'brd_eg_id', 'brd_brandlist_id', 'brd_amount', 'brd_checker'];
    $missingFields = [];

    foreach ($requiredFields as $field) {
        if (!$request->filled($field)) {
            $missingFields[] = $field;
        }
    }

    // ❌ ถ้าค่าขาดให้แจ้งเตือนใน Log และ Response
    if (!empty($missingFields)) {
        Log::error("❌ ข้อมูลไม่ครบถ้วน ขาดค่าต่อไปนี้:", $missingFields);
        return response()->json([
            'error' => 'ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบ',
            'missing_fields' => $missingFields
        ], 400);
    }

    // ✅ ตรวจสอบ `line` ถ้าเป็นตัวเลขให้เติม "L" ข้างหน้า
    $line = preg_match('/^\d+$/', $line) ? "L$line" : $line;

    // ✅ ดึงสีตามเงื่อนไข
    $colorpd = $this->conditioncolor($work_id, $line);

    // ✅ ตรวจสอบว่า lot ซ้ำหรือไม่
    $checklot = Brand::leftJoin('group_emp', 'group_emp.id', '=', 'brands.brd_eg_id')
        ->where('group_emp.line', '=', $line)
        ->where('brands.brd_lot', '=', $request->input('brd_lot'));

    if ($checklot->exists()) {
        Log::warning("⚠️ พบ lot ซ้ำ: " . $request->input('brd_lot'));
        return response()->json(['error' => 'Duplicate lot detected'], 400);
    }

    // ✅ ตรวจสอบ EmpInOut
    $eio = EmpInOut::where('eio_working_id', '=', $work_id)
        ->where('eio_emp_group', '=', $request->get('brd_eg_id'));

    $eioid = $eio->value('eio_id');
    $eiooutput = $eio->value('eio_output_amount') ?? 0;

    // ✅ ตรวจสอบลำดับข้อมูล
    $index = Brand::where('brd_working_id', '=', $work_id)
        ->where('brd_eg_id', '=', $request->input('brd_eg_id'))
        ->count();
    $countindex = $index + 1;

    // ✅ บันทึกข้อมูล
    try {
        $brands = new Brand();
        $brands->brd_working_id = $work_id;
        $brands->brd_brandlist_id = $request->input('brd_brandlist_id');
        $brands->brd_lot = $request->input('brd_lot');
        $brands->brd_eg_id = $request->input('brd_eg_id');
        $brands->brd_amount = $request->input('brd_amount');
        $brands->brd_outfg_date = now();
        $brands->brd_empdate_index_key = $countindex;
        $brands->brd_remark = $request->input('brd_remark');
        $brands->brd_backboard_no = $request->input('brd_backboard_no');
        $brands->brd_checker = $request->input('brd_checker');
        $brands->brd_color = $colorpd;

        // ✅ ตรวจสอบสถานะแบรนด์
        $white_brandlist = ["32", "33", "36", "37", "38", "49"];
        $white_manufacture = "44";
        $white_qc = "31";

        $brands->brd_status = in_array($request->input('brd_brandlist_id'), $white_brandlist) || 
                              $request->input('brd_brandlist_id') == $white_manufacture || 
                              $request->input('brd_brandlist_id') == $white_qc ? '2' : '1';

        $brands->save();
        Log::info("✅ บันทึกข้อมูลสำเร็จ: " . $brands->brd_id);

        // ✅ อัปเดตข้อมูล EmpInOut
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

    } catch (\Exception $e) {
        Log::error("❌ Error บันทึกข้อมูล: " . $e->getMessage());
        return response()->json(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'exception' => $e->getMessage()], 500);
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
    $barcode = $request->input('ccw_barcode');

    if (!$barcode) {
        Log::warning("Barcode is missing in request.");
        return redirect()->route('qrcodeinterface', ['qrcode' => 'no-barcode'])
            ->with('error', 'ไม่พบข้อมูลบาร์โค้ด');
    }

    Log::info("Received Barcode: " . $barcode);

    $existingData = CheckCsvWh::where('ccw_barcode', $barcode)->first();
    if ($existingData) {
        Log::warning("Duplicate barcode detected: " . $barcode);
        return redirect()->route('qrcodeinterface', ['qrcode' => $barcode])
            ->with('error', 'ข้อมูลซ้ำ! บาร์โค้ดนี้ถูกบันทึกแล้ว');
    }

    // ดึงค่าของ lot จาก barcode
    $ccwLot = substr($barcode, 11, 10);

    // ตรวจสอบว่า ccw_lot มีอยู่ในตาราง brands หรือไม่
    $existingBrand = Brand::where('brd_lot', $ccwLot)->first();
    if (!$existingBrand) {
        Log::warning("brd_lot: $ccwLot ไม่พบในตาราง brands");
        return redirect()->route('qrcodeinterface', ['qrcode' => $barcode])
            ->with('error', "Lot นี้ไม่มีอยู่ในระบบ: $ccwLot");
    }

    try {
        DB::beginTransaction();

        // ถ้าพบ brd_lot ใน brands ให้ทำการอัปเดต brd_status เป็น 2
        $existingBrand->update(['brd_status' => '2']);
        Log::info("Updated brd_status to 2 for brd_lot: " . $ccwLot);

        $index = CheckCsvWhIndex::count();

        $csv = CheckCsvWh::create([
            'ccw_barcode' => $barcode,
            'ccw_lot' => $ccwLot,
            'ccw_amount' => substr($barcode, 21, 3),
            'ccw_index' => $index,
        ]);

        $CsvLine = substr($barcode, 1, 1);
        switch ($CsvLine) {
            case '1': $CsvLine = 'L1'; break;
            case '2': $CsvLine = 'L2'; break;
            default: $CsvLine = 'L3';
        }

        $updatestatusfg = Brand::join('wip_working', 'brands.brd_working_id', '=', 'wip_working.ww_id')
            ->where('brands.brd_lot', $csv->ccw_lot)
            ->where('wip_working.ww_line', $CsvLine)
            ->select('brands.*')
            ->first();

        if ($updatestatusfg) {
            $updatestatusfg->update(['brd_status' => '2']);
        } else {
            Log::warning("No matching Brand found for brd_lot: " . $csv->ccw_lot);
        }

        $dataToSend = [
            "atwb_lot" => (string) $csv->ccw_lot,
            "atwb_weight_baby" => (string) $csv->ccw_index,
            "atwb_sequence" => (string) $csv->ccw_barcode,
            "atwb_weight_all" => (string) $csv->ccw_amount,
            "atwb_weight_10" => null
        ];

        $apiUrl = 'https://103.40.144.248:8081/myapp/api/weightbaby';
        $token = "2|8ItmeTHdQkIHA5Hzy21ywNHRlwb8HSwCE82DLDbd";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($apiUrl, $dataToSend);

        if ($response->successful()) {
            DB::commit();
            Log::info("Data successfully sent to API: ", $dataToSend);
            return redirect()->route('qrcodeinterface', ['qrcode' => $barcode])->with('success', 'ส่งข้อมูลสำเร็จ!');
        } else {
            DB::rollBack();
            Log::error("Failed to send data to API. Response: " . $response->body());

            return redirect()->route('qrcodeinterface', ['qrcode' => $barcode])
                ->with('error', 'ไม่สามารถส่งข้อมูลไปยัง API: ' . $response->body());
        }

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error inserting barcode and sending to API: " . $e->getMessage());

        return redirect()->route('qrcodeinterface', ['qrcode' => $barcode])
            ->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
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

    // **ตัดค่าบาร์โค้ดช่วง 12-21 เพื่อใช้ค้นหา brd_lot**
    $ccw_lot = substr($barcode, 11, 10);

    // **ตัดค่า amount จากตำแหน่งที่ 22-24**
    $ccw_amount = substr($barcode, 21, 3); // ดึงค่าจำนวน 3 หลัก

    // ✅ ถ้า `ccw_amount` เป็นเลขสองหลัก ให้เติม 0 ข้างหน้า
    if ((int)$ccw_amount < 100) {
        $ccw_amount = str_pad($ccw_amount, 3, '0', STR_PAD_LEFT);
        // แก้ไขค่าบาร์โค้ดให้สอดคล้องกับ ccw_amount ใหม่
        $barcode = substr($barcode, 0, 21) . $ccw_amount;
    }

    try {
        DB::beginTransaction(); // ✅ เริ่ม Transaction

        if (substr($barcode, 0, 2) == 'BX') {
            $index = CheckCsvWhIndex::count();

            // สร้างข้อมูลใหม่ถ้าไม่มีข้อมูลซ้ำ
            $csv = CheckCsvWh::firstOrCreate(
                ['ccw_barcode' => $barcode],
                [
                    'ccw_lot' => $ccw_lot,
                    'ccw_amount' => $ccw_amount,
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

            // ✅ **อัปเดต brd_status เป็น 2 ถ้า `brd_lot` ตรงกับ `ccw_lot`**
            $brand = Brand::where('brd_lot', $ccw_lot)->first();
            if ($brand) {
                $brand->update(['brd_status' => '2']);
                Log::info("Updated brd_status to 2 for brd_lot: " . $ccw_lot);
            } else {
                Log::warning("No matching Brand found for brd_lot: " . $ccw_lot);
            }

            DB::commit(); // ✅ บันทึก Transaction
            return back(); // ✅ กลับไปหน้าเดิม
        }

        // ถ้าไม่ใช่ BX และยังไม่มีอยู่ในระบบ
        if (!$checkExist) {
            $index = CheckCsvWhIndex::count();

            // บันทึกข้อมูลใหม่
            $csv = CheckCsvWh::create([
                'ccw_barcode' => $barcode,
                'ccw_lot' => $ccw_lot,
                'ccw_amount' => $ccw_amount,
                'ccw_index' => $index,
            ]);

            // เพิ่มข้อมูล defect ลงใน warehouse_return_to_qc
            WarehouseReturnToQc::create([
                'wrtc_barcode' => $barcode,
                'wrtc_description' => $request->input('wrtc_description'),
                'wrtc_remark' => $request->input('wrtc_remark'),
                'wrtc_date' => now(),
            ]);

            // ✅ **อัปเดต brd_status เป็น 2 ถ้า `brd_lot` ตรงกับ `ccw_lot`**
            $brand = Brand::where('brd_lot', $ccw_lot)->first();
            if ($brand) {
                $brand->update(['brd_status' => '2']);
                Log::info("Updated brd_status to 2 for brd_lot: " . $ccw_lot);
            } else {
                Log::warning("No matching Brand found for brd_lot: " . $ccw_lot);
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
        // ✅ ตรวจสอบและปรับรูปแบบของ $line
        $cleanLine = str_starts_with($line, 'L') ? substr($line, 1) : $line;
    
        $workprocess = is_array($workprocess) ? $workprocess : explode(',', $workprocess);
    
        // ✅ ดึงค่า ww_group โดยใช้ $workprocess
        $workpgrouplot = WipWorking::whereIn('ww_id', $workprocess)
        ->where('ww_line', $line) // ✅ ใช้ $line ตรง ๆ
        ->value('ww_group') ?? 'UNKNOWN';
    
    
        // ✅ กำหนดชื่อไฟล์ CSV
        $newcsvtime = now()->format('dmYHi');
        $filename = "PQC_{$newcsvtime}_{$workpgrouplot}.csv";
    
        // ✅ ดึงข้อมูลจาก Wipbarcode
        $wipData = Wipbarcode::whereIn('wip_working_id', $workprocess)
        ->select(
            DB::raw('LEFT(wipbarcodes.wip_barcode, 11) as wip_barcode'),
            'wipbarcodes.wip_amount',
            'wipbarcodes.wip_working_id'
        )
        ->get(); // ❌ เอา `distinct()` ออก เพื่อให้แน่ใจว่าดึงครบทุกตัว

          
    
        // ✅ ดึงข้อมูลจาก Brands โดยตรงจาก $workprocess
        $brandData = Brand::whereIn('brd_working_id', $workprocess)
            ->leftJoin('brandlist', 'brands.brd_brandlist_id', '=', 'brandlist.bl_id')
            ->select('brands.brd_lot', 'brands.brd_brandlist_id', 'brands.brd_amount', 'brandlist.bl_code')
            ->distinct()
            ->get();
    
        // ✅ เงื่อนไขของรหัสพิเศษ
        $white_brandlist = ["32", "33", "36", "37", "38", "49"];
        $white_manufacture = "44";
        $white_qc = "31";
    
        // ✅ รวมข้อมูลทั้งหมด
        $result = collect([]);
    
        foreach ($brandData as $brand) {
            foreach ($wipData as $wip) {
                if (in_array($brand->brd_brandlist_id, $white_brandlist)) {
                    $category = '4';
                    $type = 'FN';
                } elseif ($brand->brd_brandlist_id == $white_manufacture) {
                    $category = '4';
                    $type = $brand->bl_code;
                } elseif ($brand->brd_brandlist_id == $white_qc) {
                    $category = '4';
                    $type = 'QC';
                } else {
                    $category = '3';
                    $type = 'QC';
                }
    
                $result->push([
                    iconv('utf-8', 'cp874//TRANSLIT', $wip->wip_barcode),
                    iconv('utf-8', 'cp874//TRANSLIT', $brand->brd_lot),
                    iconv('utf-8', 'cp874//TRANSLIT', $wip->wip_amount),
                    iconv('utf-8', 'cp874//TRANSLIT', $type),
                    iconv('utf-8', 'cp874//TRANSLIT', $category),
                ]);
            }
        }
    
        // ✅ ส่งออกไฟล์ CSV
        return response()->streamDownload(function () use ($result) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // รองรับภาษาไทย
            foreach ($result as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
    
    public function dowloadcsvendtime($line, $wwt_id)
    {
        // ✅ ตรวจสอบและปรับรูปแบบของ $line
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
            ->where('ww_line', $cleanLine)
            ->value('ww_group') ?? 'UNKNOWN';
           
        // ✅ กำหนดชื่อไฟล์ CSV
        $newcsvtime = now()->format('dmYHi');
        $filename = "PQC_{$newcsvtime}_{$workpgrouplot}.csv";
    
        // ✅ ดึงข้อมูลจาก Wipbarcode
       $wipData = Wipbarcode::whereIn('wip_working_id', $workprocessIds)
    ->select(
        DB::raw('LEFT(wipbarcodes.wip_barcode, 11) as wip_barcode'),
        'wipbarcodes.wip_amount',
        'wipbarcodes.wip_working_id'
    )
    ->get(); // ❌ เอา `distinct()` ออก เพื่อให้แน่ใจว่าดึงครบทุกตัว

    
    
    
        // ✅ ดึงข้อมูลจาก Brands โดยตรงจาก $workprocessIds
        $brandData = Brand::whereIn('brd_working_id', $workprocessIds)
            ->leftJoin('brandlist', 'brands.brd_brandlist_id', '=', 'brandlist.bl_id')
            ->select('brands.brd_lot', 'brands.brd_brandlist_id', 'brands.brd_amount', 'brandlist.bl_code')
            ->distinct()
            ->get();
    
        // ✅ เงื่อนไขของรหัสพิเศษ
        $white_brandlist = ["32", "33", "36", "37", "38", "49"];
        $white_manufacture = "44";
        $white_qc = "31";
    
        // ✅ รวมข้อมูลทั้งหมด
        $result = collect([]);
    
        foreach ($brandData as $brand) {
            foreach ($wipData as $wip) {
                if (in_array($brand->brd_brandlist_id, $white_brandlist)) {
                    $category = '4';
                    $type = 'FN';
                } elseif ($brand->brd_brandlist_id == $white_manufacture) {
                    $category = '4';
                    $type = $brand->bl_code;
                } elseif ($brand->brd_brandlist_id == $white_qc) {
                    $category = '4';
                    $type = 'QC';
                } else {
                    $category = '3';
                    $type = 'QC';
                }
    
                $result->push([
                    iconv('utf-8', 'cp874//TRANSLIT', $wip->wip_barcode),
                    iconv('utf-8', 'cp874//TRANSLIT', $brand->brd_lot),
                    iconv('utf-8', 'cp874//TRANSLIT', $wip->wip_amount),
                    iconv('utf-8', 'cp874//TRANSLIT', $type),
                    iconv('utf-8', 'cp874//TRANSLIT', $category),
                ]);
            }
        }
    
        // ✅ ส่งออกไฟล์ CSV
        return response()->streamDownload(function () use ($result) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // รองรับภาษาไทย
            foreach ($result as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
    

    
    
    public function endworktime(Request $request, $line)
    {
        try {
            \Log::info('Received Data:', $request->all());
    
            $request->validate([
                'wwt_status' => 'required|numeric',
                'wz_amount'  => 'required|numeric|min:0',
                'wwd_amount' => 'required|numeric|min:0',
            ]);
    
            DB::beginTransaction();
    
            $lineFormatted = $line;
            $ww_ids = $request->ww_ids ?? [];
    
            if (is_string($ww_ids)) {
                $decodedIds = json_decode($ww_ids, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('JSON decode error: ww_ids');
                }
                $ww_ids = $decodedIds;
            }
    
            \Log::info("✅ ได้รับ ww_ids จาก Frontend:", ['ww_ids' => $ww_ids]);
    
            $checktimeindex = WipWorktime::where('wwt_line', $lineFormatted)->count();
    
            // ✅ สร้าง Worktime ใหม่
            $end = new WipWorktime();
            $end->wwt_index = $checktimeindex;
            $end->wwt_status = $request->wwt_status;
            $end->wwt_line = $lineFormatted;
            $end->wwt_date = Carbon::now();
            $end->save();
    
            $wwt_id = $end->wwt_id;
    
            if (!$wwt_id) {
                throw new \Exception('เกิดข้อผิดพลาด ไม่สามารถสร้าง WipWorktime ได้');
            }
    
            // ✅ อัปเดตสถานะ WipWorking
            if (!empty($ww_ids) && is_array($ww_ids)) {
                WipWorking::whereIn('ww_id', $ww_ids)
                    ->where('ww_status', '!=', 'E')
                    ->update(['ww_status' => 'E']);
            } else {
                \Log::warning("⚠️ ไม่มี WW_IDs ที่ถูกส่งมาหรือรูปแบบไม่ถูกต้อง");
            }
    
            // ✅ เพิ่มข้อมูลลงใน WipZiptape
            $ziptape = new WipZiptape();
            $ziptape->wz_line = $lineFormatted;
            $ziptape->wz_worktime_id = $wwt_id;
            $ziptape->wz_amount = $request->input('wz_amount') + 0.0000 - 0.015;
            $ziptape->save();
    
            // ✅ สร้าง Barcode สำหรับ WipWasteDetail
            $lotc = date('ymd') . str_pad($checktimeindex + 1, 2, '0', STR_PAD_LEFT);
    
            $tagc = new WipWasteDetail();
            $tagc->wwd_line = $lineFormatted;
            $tagc->wwd_index = $checktimeindex;
            $tagc->wwt_id = $wwt_id; // เชื่อมกับ wwt_id ของ Worktime
            $tagc->wwd_lot = $lotc;
            $tagc->wwd_amount = $request->input('wwd_amount');
            $tagc->wwd_date = Carbon::now();
    
            // ✅ ตรวจสอบ Barcode
            if ($request->input('wwd_amount') < 10) {
                $tagc->wwd_barcode = 'B' . $lineFormatted . '09-' . $lotc . '00' . $request->input('wwd_amount');
            } elseif ($request->input('wwd_amount') < 100) {
                $tagc->wwd_barcode = 'B' . $lineFormatted . '09-' . $lotc . '0' . $request->input('wwd_amount');
            } else {
                $tagc->wwd_barcode = 'B' . $lineFormatted . '09-' . $lotc . $request->input('wwd_amount');
            }
    
            $tagc->save();
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'บันทึกข้อมูลจบกะเรียบร้อย',
                'wwt_id' => $wwt_id,
                'wwt_index' => $end->wwt_index,
                'ww_ids' => $ww_ids
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('❌ Error in endworktime:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'เกิดข้อผิดพลาด', 'message' => $e->getMessage()], 500);
        }
    }
    
    
    
    public function storeWorkprocessTemp(Request $request)
    {
        try {
            \Log::info('📥 Received Data in storeWorkprocessTemp:', $request->all());
    
            $ww_ids = $request->ww_ids ?? [];
    
            if (is_string($ww_ids)) {
                $decodedIds = json_decode($ww_ids, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('JSON decode error: ww_ids');
                }
                $ww_ids = $decodedIds;
            }
    
            if (!$request->wwt_id) {
                throw new \Exception("wwt_id ไม่ถูกต้อง");
            }
    
            $existingWwIds = WipWorking::whereIn('ww_id', $ww_ids)
                ->pluck('ww_id')
                ->toArray();
    
            \Log::info("✅ ww_id ที่มีอยู่ใน wip_working:", ['existingWwIds' => $existingWwIds]);
    
            if (empty($existingWwIds)) {
                \Log::error("❌ ไม่มี ww_id ที่ตรงกับฐานข้อมูล wip_working");
                return response()->json([
                    'status' => 'error',
                    'message' => 'ไม่มี ww_id ที่ถูกต้องในระบบ'
                ], 400);
            }
    
            $existingRecords = WorkprocessTemp::whereIn('workprocess_id', $existingWwIds)
                ->where('wwt_id', $request->wwt_id)
                ->pluck('workprocess_id')
                ->toArray();
    
            $newRecords = array_diff($existingWwIds, $existingRecords);
    
            if (!empty($newRecords)) {
                $workprocessTemps = [];
                foreach ($newRecords as $ww_id) {
                    $workprocessTemps[] = [
                        'workprocess_id' => $ww_id,
                        'line' => $request->line,
                        'wwt_id' => $request->wwt_id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
    
                WorkprocessTemp::insert($workprocessTemps);
                \Log::info('✅ บันทึก WorkprocessTemp สำเร็จ:', ['count' => count($workprocessTemps)]);
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'บันทึก WorkprocessTemp สำเร็จ'
            ]);
    
        } catch (\Exception $e) {
            \Log::error('❌ Error in storeWorkprocessTemp:', ['message' => $e->getMessage()]);
    
            return response()->json([
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
    
    

    
    





    public function workedprevious($line, $wwt_id)
{
    if (!auth()->check()) {
        return redirect('/'); 
    }

    // ✅ ตัดตัวอักษร 'L' ออกจาก `$line` ถ้ามี
    $cleanLine = str_starts_with($line, 'L') ? substr($line, 1) : $line;

    // ✅ ดึง `ww_id` จาก WorkprocessTemp ที่ wwt_id ตรงกัน
    $wipWorkingIds = WorkprocessTemp::where('wwt_id', $wwt_id)
        ->where('line', $cleanLine)
        ->distinct() // ✅ ป้องกันค่าซ้ำ
        ->pluck('workprocess_id')
        ->toArray();

    // ✅ กำหนดค่าเริ่มต้นให้ `$wipWorkingData`
    $wipWorkingData = collect([]);

    if (!empty($wipWorkingIds)) {
        // ✅ ดึงข้อมูลจาก `wip_working` ตาม `ww_id`
        $wipWorkingData = WipWorking::whereIn('wip_working.ww_id', $wipWorkingIds)
            ->leftJoin('product_type_emps', 'wip_working.ww_id', '=', 'product_type_emps.pe_working_id')
            ->select(
                'wip_working.ww_id',
                'wip_working.ww_line',
                'wip_working.ww_group',
                'wip_working.ww_status',
                'wip_working.ww_end_date',
                'product_type_emps.pe_type_name'
            )
            ->groupBy(
                'wip_working.ww_id',
                'wip_working.ww_line',
                'wip_working.ww_group',
                'wip_working.ww_status',
                'wip_working.ww_end_date',
                'product_type_emps.pe_type_name'
            ) // ✅ ใส่ทุกคอลัมน์ที่อยู่ใน `SELECT` ลงใน `GROUP BY`
            ->get();
    }

    return view('workedprevious', compact('line', 'wwt_id', 'wipWorkingData', 'cleanLine'));
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
    
   
    
    public function addbrandslist(){

        $count = 1;
        $brandslist = Brandlist::all();

        $view = view('mainside.qcfn.addbrandslist',[
        'count'             =>  $count,
        'brandslist'        =>  $brandslist,
        ]);
        return $view;
    }

    public function inputbrandslist(Request $request)
    {
        try {
            // ✅ Validate ข้อมูล
            $request->validate([
                'bl_name' => 'required|string|max:255',
                'bl_code' => 'required|string|max:50|unique:brandlist,bl_code', // ห้ามซ้ำ
            ]);
    
            // ✅ บันทึกข้อมูล
            $brand = BrandList::create([
                'bl_name'   => $request->bl_name,
                'bl_code'   => $request->bl_code,
                'bl_status' => 1,
            ]);
    
            return response()->json([
                'status'  => 'success',
                'message' => 'บันทึกข้อมูลสำเร็จ',
                'data'    => $brand
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function updateBrandStatus(Request $request)
    {
        try {
            // ✅ ตรวจสอบว่ามี ID ที่ส่งมาหรือไม่
            $brand = BrandList::findOrFail($request->bl_id);
    
            // ✅ อัปเดตสถานะ bl_status (เปิด = 1, ปิด = 0)
            $brand->bl_status = $request->bl_status;
            $brand->save();
    
            return response()->json([
                'status'    => 'success',
                'message'   => 'อัปเดตสถานะสำเร็จ',
                'bl_id'     => $brand->bl_id,
                'bl_code'   => $brand->bl_code,
                'bl_name'   => $brand->bl_name,
                'bl_status' => $brand->bl_status
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function checkcsvtobplus()
    {
        // ✅ ใช้ค่าล่าสุดจาก `check_csv_wh`
        $index = CheckCsvWh::orderBy('ccw_id', 'desc')->value('ccw_index') ?? 0;
    
        // ✅ ดึงรายการทั้งหมดจาก `check_csv_wh_index`
        $savedfiles = CheckCsvWhIndex::all();
    
        // ✅ ตรวจสอบว่า `ccw_index` มีอยู่ใน `check_csv_wh_index` หรือไม่
        $existsInIndex = CheckCsvWhIndex::where('cswi_index', '=', $index)->exists();
    
        // ✅ ถ้า `ccw_index` มีใน `check_csv_wh_index` → ไม่ต้องส่ง `$detail`
        $detail = $existsInIndex ? collect([]) : CheckCsvWh::where('ccw_index', '=', $index)->get();
    
        // ✅ ส่งค่าทั้งหมดไปที่ View `checkcsvtobplus`
        return view('checkcsvtobplus', [
            'detail'        => $detail, // ถ้าว่าง จะไม่มีข้อมูลไปแสดง
            'index'         => $index,
            'savedfiles'    => $savedfiles,
        ]);
    }
    

    
    public function outcheckcsvwh($indexno)
    {
        // ✅ ดึงข้อมูลจาก `check_csv_wh` ที่ `ccw_index` ตรงกับ `$indexno`
        $csv = CheckCsvWh::where('ccw_index', '=', $indexno)->get();
        $enddatefm = date('dmYHi');
        $filename = "PWH{$enddatefm}B.csv";

        // ✅ กำหนด Header สำหรับดาวน์โหลดไฟล์ CSV
        $headers = [
            "Content-Type" => "application/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0"
        ];

        // ✅ เปิดไฟล์ CSV ในหน่วยความจำ
        $handle = fopen('php://output', 'w');
        $cp = 'cp874//TRANSLIT';

        // ✅ เขียน Header ลง CSV
        fputcsv($handle, [
            iconv('utf-8', $cp, "PK01-000008"),
            iconv('utf-8', $cp, ""),
            iconv('utf-8', $cp, "0.01"),
            iconv('utf-8', $cp, "3"),
            iconv('utf-8', $cp, "QC")
        ]);

        // ✅ สร้างข้อมูล BX จากบาร์โค้ด
        $bx = 'BX';

        foreach ($csv as $item) {
            $barcodePrefix = substr($item->ccw_barcode, 0, 2);
            $lot = "";

            // ✅ ตรวจสอบ Barcode Prefix
            if ($barcodePrefix == "B1") {
                $lot = "L1";
            } elseif ($barcodePrefix == "B2") {
                $lot = "L2";
            } elseif ($barcodePrefix == "B3") {
                $lot = "L3";
            } else {
                $lot = $item->ccw_lot;
            }

            // ✅ เขียนข้อมูลลง CSV
            fputcsv($handle, [
                iconv('utf-8', $cp, $bx . substr($item->ccw_barcode, 2, 2) . substr($item->ccw_barcode, 4, 7)),
                iconv('utf-8', $cp, $lot),
                iconv('utf-8', $cp, $item->ccw_amount),
                iconv('utf-8', $cp, '4'),
                iconv('utf-8', $cp, '01-01นว')
            ]);
        }

        fclose($handle);

        return Response::make('', 200, $headers);
    }

    /**
     * 📌 ฟังก์ชันสำหรับเพิ่มดัชนีลงตาราง check_csv_wh_index
     */
    public function insertcheckcsvindex(Request $request)
    {
        date_default_timezone_set("Asia/Bangkok");
    
        if (!$request->filled('cswi_index')) {
            return response()->json([
                'status' => 'error',
                'message' => 'ไม่พบค่าของ index กรุณาระบุค่าก่อนทำการบันทึก'
            ], 400);
        }
    
        // ✅ ตรวจสอบว่ามีข้อมูลใน `check_csv_wh` หรือไม่
        $checkempty = CheckCsvWh::where('ccw_index', '=', $request->input('cswi_index'))->first();
        if (!$checkempty) {
            return response()->json([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลที่ตรงกับ index นี้'
            ], 400);
        }
    
        // ✅ บันทึกข้อมูลลง `check_csv_wh_index`
        $index = new CheckCsvWhIndex();
        $index->cswi_index = $request->input('cswi_index');
        $index->cswi_ziptape = 0.01;
        $index->save();
    
        return response()->json([
            'status' => 'success',
            'indexno' => $index->cswi_index,
            'message' => 'บันทึกข้อมูลสำเร็จ'
        ], 200);
    }
    public function csvwhsaved($indexno)
    {
        // ✅ กำหนดค่า `$indexno` เพื่อใช้ใน View
        $no = $indexno;
    
        // ✅ ดึงรายการไฟล์ที่เคยบันทึกทั้งหมดจาก `check_csv_wh_index`
        $savedfiles = CheckCsvWhIndex::all();
    
        // ✅ ดึงข้อมูลจาก `check_csv_wh` ที่มี `ccw_index` ตรงกับ `$indexno`
        $detailall = CheckCsvWh::where('ccw_index', $indexno)->get();
    
        // ✅ ตรวจสอบว่ามีข้อมูลหรือไม่
        if ($detailall->isEmpty()) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลสำหรับ Index นี้');
        }
    
        // ✅ ส่งข้อมูลไปยัง View `csvwhsaved.blade.php`
        return view('csvwhsaved', [
            'no'         => $no,          // ส่งค่า Index ไปที่ View
            'detailall'  => $detailall,   // ข้อมูลทั้งหมดจาก `check_csv_wh`
            'savedfiles' => $savedfiles,  // รายการไฟล์ทั้งหมดจาก `check_csv_wh_index`
        ]);
    }
    
    public function csvdetailrealtime()
    {
        // ดึงค่า index ล่าสุดที่ยังไม่มีใน `check_csv_wh_index`
        $lastIndex = CheckCsvWh::orderBy('ccw_id', 'desc')->value('ccw_index') ?? 0;
        $existsInIndex = CheckCsvWhIndex::where('cswi_index', '=', $lastIndex)->exists();
    
        if ($existsInIndex) {
            return "<h4 class='text-center'>ไม่มีข้อมูลใหม่</h4>";
        }
    
        // ดึงข้อมูลจาก `CheckCsvWh` ที่มี `ccw_index` เท่ากับค่าที่หาได้
        $detail = CheckCsvWh::where('ccw_index', '=', $lastIndex)->get();
    
        if ($detail->isEmpty()) {
            return "<h4 class='text-center'>ไม่มีข้อมูลใหม่</h4>";
        }
    
        // แสดงข้อมูลโดยใช้ echo
        foreach ($detail as $details) {
            echo "
            <div class='col-md-5 col-xs-5'>
                <h4 class='text-center'>$details->ccw_barcode</h4>
            </div>
            <div class='col-md-3 col-xs-3'>
                <h4 class='text-center'>$details->ccw_lot</h4>
            </div>
            <div class='col-md-2 col-xs-2'>
                <h4 class='text-center'>$details->ccw_amount</h4>
            </div>
            <div class='col-md-1 col-xs-1'>
                <h4 class='text-center'>
                    <a href='#' data-target='#deleteccwbarcode' data-toggle='modal' 
                       data-ccw_id='$details->ccw_id' data-ccw_barcode='$details->ccw_barcode' 
                       class='deleteccwbarcode'>
                        <i style='color:red;' class='fa fa-trash'></i>
                    </a>
                </h4>
            </div>";
        }
    
        // เพิ่ม JavaScript สำหรับการลบข้อมูล
        echo "<script>
        $('.deleteccwbarcode').on('click', function () {
            var ccw_id = $(this).data('ccw_id');
            var ccw_barcode = $(this).data('ccw_barcode');
            $('#ccwbarcodeheader').text(ccw_barcode);
            $('#ccw_id_hiden').val(ccw_id);
        });
        </script>";
    }
    
    public function insertcheckcsv(Request $request)
{
    // ✅ ตรวจสอบบาร์โค้ด
    if (substr($request->input('ccw_barcode'), 0, 2) == 'BX') {
        $this->validate($request, [
            'ccw_barcode' => 'required|min:24|max:24',
        ]);
    } else {
        $this->validate($request, [
            'ccw_barcode' => 'required|min:24|max:24|unique:check_csv_wh',
        ]);
    }

    // ✅ หาค่า `ccw_index` ล่าสุด
    $lastIndex = CheckCsvWh::max('ccw_index') ?? 0;

    // ✅ แก้ไขให้ใช้ชื่อคอลัมน์ที่ถูกต้อง (เช่น `cswi_index`)
    while (CheckCsvWhIndex::where('cswi_index', '=', $lastIndex)->exists()) {
        $lastIndex++;
    }

    $newIndex = $lastIndex;

    // ✅ แยกค่าบาร์โค้ดเพื่อหาไลน์การผลิต (B1, B2, B3)
    $CsvLine = substr($request->input('ccw_barcode'), 1, 1);

    // ✅ บันทึกข้อมูลลง `check_csv_wh`
    $csv = new CheckCsvWh();
    $csv->ccw_barcode = $request->input('ccw_barcode');
    $csv->ccw_lot = substr($request->input('ccw_barcode'), 11, 10);
    $csv->ccw_amount = substr($request->input('ccw_barcode'), 21, 3);
    $csv->ccw_index = $newIndex; // ✅ ใช้ค่า index ที่อัปเดตแล้ว
    $csv->save();

    return response()->json([
        'status' => 'success',
        'message' => 'บันทึกข้อมูลสำเร็จ',
        'ccw_index' => $newIndex,
    ], 200);
}

    
        
            public function deleteccw($ccw_id)
            {
                // ค้นหาข้อมูลที่ต้องการลบ
                $delete = CheckCsvWh::find($ccw_id);
        
                // ตรวจสอบว่าพบข้อมูลหรือไม่
                if (!$delete) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'ไม่พบข้อมูลที่ต้องการลบ'
                    ], 404);
                }
        
                // ทำการลบข้อมูล
                $delete->delete();
        
                return response()->json([
                    'status' => 'success',
                    'message' => 'ลบข้อมูลสำเร็จ'
                ], 200);
            }
            public function datawip($line, $id, $brd_id = null)
            {
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
                $lothdgenerator = date('ymd', strtotime($workdate)) . substr($workpgroup, 1, 1) . substr($workpgroup, 1, 1) . str_pad($lotcheck + 1, 2, '0', STR_PAD_LEFT);
            
                // ✅ ดึงค่า holding ที่เกี่ยวข้อง
                $holding = WipHolding::where('wh_working_id', $id)->sum('wh_index'); 
            
                // ✅ สร้าง Barcode ตามเงื่อนไข
                if ($holding < 100 && $holding > 10) {
                    $hdbarcode = 'B'.substr($line, 1, 1).'99-'.$lothdgenerator.'0'.$holding;
                } elseif ($holding < 10) {
                    $hdbarcode = 'B'.substr($line, 1, 1).'99-'.$lothdgenerator.'00'.$holding;
                } else {
                    $hdbarcode = 'B'.substr($line, 1, 1).'99-'.$lothdgenerator.$holding;
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
            public function getWipBarcode($wip_id)
            {
                Log::info("📌 กำลังดึงข้อมูล WIP Barcode สำหรับ WIP ID: " . $wip_id);
        
                // ✅ ดึงบาร์โค้ดจาก wip_id
                $barcode = Wipbarcode::where('wip_id', $wip_id)->pluck('wip_barcode')->first();
        
                if (!$barcode) {
                    Log::info("🚨 ไม่พบข้อมูล WIP Barcode สำหรับ WIP ID: " . $wip_id);
                    return response()->json(['error' => 'ไม่พบข้อมูลบาร์โค้ด'], 404);
                }
        
                Log::info("✅ พบ WIP Barcode: " . $barcode);
                return response()->json(['barcode' => $barcode]);
            }
            public function getAmountNg($wip_id)
    {
        Log::info("📌 กำลังดึงข้อมูล amg_amount สำหรับ WIP ID: " . $wip_id);

        // ✅ ดึงข้อมูลรวมจาก amg_amount โดยใช้ amg_wip_id
        $totalAmount = AmountNg::where('amg_wip_id', $wip_id)->sum('amg_amount');

        if ($totalAmount === 0) {
            Log::info("🚨 ไม่พบข้อมูล amg_amount สำหรับ WIP ID: " . $wip_id);
            return Response::json(['status' => 'error', 'error' => 'Not Found'], 404);
        }

        Log::info("✅ พบข้อมูล amg_amount รวม: " . $totalAmount);
        return Response::json(['status' => 'success', 'amg_amount' => $totalAmount]);
    }

    public function getBrandStatus($id)
    {
        $brand = BrandList::where('bl_id', $id)->first();

        return response()->json([
            'bl_status' => $brand ? $brand->bl_status : null
        ]);
    }
    public function getActiveBrands()
    {
        $brands = BrandList::where('bl_status', 1)
            ->select('bl_id', 'bl_name')
            ->get();

        return response()->json($brands);
    }
    public function addlistng()
{
    $count = 1;
    $nglist = Listngall::all(); // เปลี่ยนจาก ListNg เป็น Listngall

    return view('mainside.qcfn.addlistng', [
        'count'  => $count,
        'nglist' => $nglist,
    ]);
}
public function inputlistng(Request $request)
{
    $this->validate($request, [
        'lng_name' => 'required',
    ]);

    try {
        $add = new Listngall;
        $add->lng_name = $request->input('lng_name');
        $add->lng_status = 1;
        $add->save();

        \Log::info('บันทึกสำเร็จ', ['data' => $add]);

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        \Log::error('บันทึกไม่สำเร็จ', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'เกิดข้อผิดพลาด'], 500);
    }
}
public function lngstatus(Request $request)
{
    $status = Listngall::find($request->lng_id);

    if (!$status) {
        return response()->json(['error' => 'ไม่พบข้อมูล'], 404);
    }

    $status->lng_status = $request->lng_status;
    $status->save();

    return response()->json([
        'lng_status' => $status->lng_status,
        'lng_name'   => $status->lng_name
    ]);
}

public function getBrdStatus($brd_lot)
{
    // ดึงค่า brd_status ตาม brd_lot
    $status = Brand::where('brd_lot', $brd_lot)->value('brd_status');

    // ส่งค่า JSON Response
    return response()->json([
        'brd_lot' => $brd_lot,
        'brd_status' => $status
    ]);
}
   }
            

    
      





    
    









