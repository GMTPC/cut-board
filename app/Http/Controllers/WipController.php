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
            // ตรวจสอบข้อมูล
            $request->validate([
                'emp1_old' => 'required|string',
                'emp2_old' => 'required|string',
            ]);
    
            // ดึงชื่อผู้คัดใหม่จากฟอร์ม
            $emp1_new = $request->input('emp1_old');
            $emp2_new = $request->input('emp2_old');
    
            // ค้นหา ID จาก group_emp
            $newGroupEmp = GroupEmp::where('emp1', $emp1_new)
                                   ->where('emp2', $emp2_new)
                                   ->first();
    
            // ✅ ค้นหา WIP Barcode ด้วย wip_id
            $wipBarcode = Wipbarcode::where('wip_working_id', $id)->first();
    
            if (!$wipBarcode) {
                return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูล WIP Barcode']);
            }
    
            if ($newGroupEmp) {
                $wipBarcode->update([
                    'wip_empgroup_id' => $newGroupEmp->id
                ]);
    
                return response()->json(['status' => 'success', 'message' => 'อัปเดตข้อมูลสำเร็จ']);
            }
    
            return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูลของผู้คัด']);
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    public function addng(Request $request)
    {
        $request->validate([
            'amg_wip_id'    => 'required|array',
            'amg_ng_id'     => 'required|array',
            'amg_amount'    => 'required|array',
        ]);
    
        $input = $request->all();
    
        foreach ($input['amg_wip_id'] as $key => $wipId) {
            AmountNg::create([
                'amg_wip_id' => $wipId,
                'amg_ng_id'  => $input['amg_ng_id'][$key],
                'amg_amount' => $input['amg_amount'][$key],
            ]);
        }
    
        return response()->json([
            'status'  => 'success',
            'message' => 'บันทึกข้อมูลสำเร็จ'
        ]);
    }
}
