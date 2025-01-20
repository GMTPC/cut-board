<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Wipbarcode, WipProductDate, EmpInOut, ProductTypeEmp, WipWorktime, WorkProcessQC,GroupEmp,Skumaster,AmountNg};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WipController extends Controller
{
    public function insertWip(Request $request, $line, $work_id)
    {
        try {
            // ✅ 1. Debug เช็คข้อมูล
            Log::info('Request Data:', $request->all());
            Log::info('Line:', ['line' => $line]);
            Log::info('Work ID:', ['work_id' => $work_id]);
    
            // ✅ 2. เช็ค WorkProcess ว่ามีหรือไม่
            $workProcess = WorkProcessQC::find($work_id);
            if (!$workProcess) {
                return response()->json([
                    'status' => 'error',
                    'title' => 'ไม่พบข้อมูล',
                    'message' => 'ไม่พบข้อมูลกระบวนการทำงานสำหรับ work_id นี้'
                ], 400);
            }
    
            // ✅ 3. เช็คว่า Line ตรงกับฐานข้อมูลหรือไม่
            if ($workProcess->line != $line) {
                return response()->json([
                    'status' => 'error',
                    'title' => 'ไลน์ไม่ตรงกัน',
                    'message' => 'Line ไม่ตรงกับ work_id',
                    'line_from_url' => $line,
                    'line_from_db' => $workProcess->line,
                ], 400);
            }
    
            // ✅ 4. Validate ข้อมูล
            $request->validate([
                'wip_barcode' => 'required|min:24',
                'wip_empgroup_id' => 'required|integer',
                'wp_working_id' => 'required|integer',
            ]);
    
            $input = $request->all();
    
            DB::beginTransaction();
    
            // ✅ 5. ตัดบาร์โค้ด 11 ตัวแรก เพื่อค้นหา SKU_NAME
            $barcode11 = substr($input['wip_barcode'], 0, 11);
    
            // ✅ 6. ดึง SKU_NAME จาก Skumaster
            $skuNameFull = Skumaster::where('SKU_CODE', $barcode11)->value('SKU_NAME');
    
            if (!$skuNameFull) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'title' => 'ไม่พบข้อมูล SKU',
                    'message' => 'ไม่พบข้อมูลใน SKUMASTER ที่ตรงกับบาร์โค้ดนี้'
                ], 400);
            }
    
            // ✅ 7. ตัดคำว่า "แผ่นรอคัด Line X" ออก (X = 1, 2, 3, ...)
            $skuNameClean = preg_replace('/^แผ่นรอคัด\s*line\s*\d+\s*/iu', '', $skuNameFull);
            $skuName = mb_substr($skuNameClean, 0, 35);  // ตัดชื่อให้เหลือ 35 ตัวอักษร
    
            // ✅ 8. คำนวณ pe_index ต่อจากเดิม
            $peIndex = ProductTypeEmp::max('pe_index') + 1;
    
            // ✅ 9. บันทึกข้อมูลลง ProductTypeEmp
            $productTypeEmp = ProductTypeEmp::create([
                'pe_working_id' => $input['wp_working_id'],  // ส่ง wip_working_id ไปที่ pe_working_id
                'pe_type_code'  => substr($barcode11, -6),   // บาร์โค้ด 6 ตัวท้าย
                'pe_type_name'  => $skuName,                // SKU_NAME ที่ถูกตัดคำ
                'pe_index'      => $peIndex,               // index ต่อจากเดิม
            ]);
    
            // ✅ 10. ตัดเลข 3 ตัวท้ายของบาร์โค้ดเป็นจำนวนสินค้า
            $lastThreeDigits = substr($input['wip_barcode'], -3);
            $input['wip_amount'] = (int) ltrim($lastThreeDigits, '0');
    
            // ✅ 11. ตรวจสอบว่าบาร์โค้ดซ้ำหรือไม่
            $existingWip = Wipbarcode::where('wip_barcode', $input['wip_barcode'])->first();
            if ($existingWip) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'title' => 'บาร์โค้ดซ้ำ',
                    'message' => 'บาร์โค้ดนี้ถูกบันทึกแล้ว'
                ], 400);
            }
    
            // ✅ 12. บันทึกข้อมูลลง Wipbarcode
            Wipbarcode::create([
                'wip_barcode'    => $input['wip_barcode'],
                'wip_amount'     => $input['wip_amount'],
                'wip_working_id' => $input['wp_working_id'],
                'wip_empgroup_id'=> $input['wip_empgroup_id'],
                'wip_sku_name'   => $skuName,   // บันทึกชื่อสินค้า (ตัดคำแล้ว)
                'wip_index'      => $peIndex,   // บันทึก index
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
        $request->validate([
            'wip_empgroup_id' => 'required|integer',
        ]);

        $wipBarcode = Wipbarcode::find($id);
        if (!$wipBarcode) {
            return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูล WIP Barcode']);
        }

        $wipBarcode->update(['wip_empgroup_id' => $request->wip_empgroup_id]);

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


}


