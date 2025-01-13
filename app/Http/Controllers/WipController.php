<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Wipbarcode, WipProductDate, EmpInOut, ProductTypeEmp, WipWorktime, WorkProcessQC,GroupEmp};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WipController extends Controller
{
    public function insertWip(Request $request, $line, $work_id)
    {
        // Fetch work process by ID
        $workProcess = WorkProcessQC::find($work_id);
    
        if (!$workProcess) {
            return response()->json([
                'status' => 'error',
                'title' => 'ไม่พบข้อมูล',
                'message' => 'ไม่พบข้อมูลกระบวนการทำงานสำหรับ work_id นี้'
            ], 400);
        }
    
        if ($workProcess->line != $line) {
            return response()->json([
                'status' => 'error',
                'title' => 'ไลน์ไม่ตรงกัน',
                'message' => 'Line ไม่ตรงกับ work_id',
                'line_from_url' => $line,
                'line_from_db' => $workProcess->line,
            ], 400);
        }
    
        $request->validate([
            'wip_barcode' => 'required|min:24',
            'wip_empgroup_id' => 'required|integer',
            'pe_type_code' => 'required|string',
            'wp_working_id' => 'required|integer',
        ]);
    
        $input = $request->all();
    
        // ดึงค่า wip_amount จาก 3 ตัวท้ายของ wip_barcode
        $lastThreeDigits = substr($input['wip_barcode'], -3);
        $input['wip_amount'] = (int) ltrim($lastThreeDigits, '0'); // ลบ '0' ด้านหน้า ถ้าเป็น 076 จะได้ 76
    
        DB::beginTransaction();
        try {
            $checktimeindex = WipWorktime::where('wwt_line', $line)->count();
    
            $barcodeDetails = $this->parseBarcode($input['wip_barcode']);
            $empgroup = $input['wip_empgroup_id'];
            $pdcode = $input['pe_type_code'];
            $connected = $this->isInternetConnected();
    
            $queryResult = $this->fetchSkuDetails($connected, $pdcode, $input['wip_barcode']);
            $skuName = $queryResult['sku_name'] ?? null;
    
            $empInOut = EmpInOut::where('eio_working_id', $work_id)
                ->where('eio_emp_group', $input['wip_empgroup_id'])
                ->first();
    
            if ($this->isValidBarcode($barcodeDetails, $line, $workProcess->line, $empgroup)) {
                if ($barcodeDetails['type'] === 'BX') {
                    $this->saveWipBarcodeAndRelatedData(
                        $input,
                        $checktimeindex,
                        $barcodeDetails['date'],
                        $skuName,
                        $empInOut
                    );
                } elseif (!$this->isWipBarcodeExists($input['wip_barcode'])) {
                    $this->saveWipBarcodeAndRelatedData(
                        $input,
                        $checktimeindex,
                        $barcodeDetails['date'],
                        $skuName,
                        $empInOut
                    );
                } else {
                    return response()->json([
                        'status' => 'error',
                        'title' => 'บันทึกข้อมูลไม่สำเร็จ',
                        'message' => 'บาร์โค้ดซ้ำในระบบ กรุณาตรวจสอบอีกครั้ง'
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'title' => 'บันทึกข้อมูลไม่สำเร็จ',
                    'message' => 'สาเหตุอาจจะมาจากชนิดที่ไม่เหมือนกัน บาร์โค้ดซ้ำ ยังไม่เลือกผู้คัด หรือ รูปแบบไม่ถูกต้อง'
                ], 400);
            }
    
            DB::commit();
            return response()->json([
                'status' => 'success',
                'title' => 'บันทึกเรียบร้อย',
                'message' => 'ถ้าไม่มีการเปลี่ยนแปลงโปรดรีเฟรชหน้าใหม่อีกครั้ง'
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'title' => 'เกิดข้อผิดพลาด',
                'message' => 'มีบางอย่างผิดพลาด กรุณาลองใหม่อีกครั้ง'
            ], 500);
        }
    }
    
    

    // Function to parse barcode into components
    private function parseBarcode($barcode)
    {
        return [
            'type' => substr($barcode, 0, 2),
            'line' => substr($barcode, 1, 1),
            'new_line' => substr($barcode, 12, 1),
            'date' => Carbon::create(
                '20' . substr($barcode, 11, 2),
                substr($barcode, 13, 2),
                substr($barcode, 15, 2)
            ),
        ];
    }

    // Function to check internet connection
    private function isInternetConnected()
    {
        return @fsockopen("www.google.com", 80) ? true : false;
    }

    // Function to fetch SKU details
    private function fetchSkuDetails($connected, $pdcode, $barcode)
    {
        $queryConnection = $connected ? 'sqlsrv_bplus' : 'sqlsrv';

        $queryPdName = DB::connection($queryConnection)
            ->table(DB::raw("(
                SELECT SUBSTRING(SKUMASTER.SKU_CODE, 6, 6) TYPECODE,
                       FIRST_VALUE(SUBSTRING(SKU_NAME, CHARINDEX('ขอ', SKU_NAME), 99)) OVER (
                           PARTITION BY SUBSTRING(SKUMASTER.SKU_CODE, 6, 6)
                           ORDER BY SKU_CODE DESC
                       ) AS TYPENAME
                FROM SKUMASTER
                WHERE SUBSTRING(SKU_CODE, 1, 2) = 'BX' OR (
                    (LEFT(SKU_CODE, 1) = 'B' OR LEFT(SKU_CODE, 1) = 'W') AND
                    (SUBSTRING(SKU_CODE, 2, 1) IN ('1', '2', '3'))
                )
            ) T"))
            ->select('TYPENAME')
            ->where('TYPECODE', $pdcode)
            ->value('TYPENAME');

        $skuName = DB::connection($queryConnection)
            ->table('SKUMASTER')
            ->selectRaw("LEFT(SKU_NAME, 35) AS SKU_NAME")
            ->where('sku_code', substr($barcode, 0, 11))
            ->value('SKU_NAME');

        return ['sku_name' => $skuName, 'query_pd_name' => $queryPdName];
    }

    // Function to validate barcode
    private function isValidBarcode($barcodeDetails, $line, $conditionLine, $empgroup)
    {
        return $barcodeDetails['line'] === $conditionLine ||
            ($barcodeDetails['line'] === $barcodeDetails['new_line'] && $empgroup !== 0);
    }

    // Function to save WIP barcode and related data
    private function saveWipBarcodeAndRelatedData($input, $checktimeindex, $barcodeDate, $skuName, $empInOut)
    {
        // บันทึกข้อมูลใน Wipbarcode
        $wipBarcode = Wipbarcode::create([
            'wip_barcode' => $input['wip_barcode'],
            'wip_amount' => $input['wip_amount'],
            'wip_working_id' => $input['wp_working_id'],
            'wip_empgroup_id' => $input['wip_empgroup_id'],
            'wip_sku_name' => $skuName,
            'wip_index' => $checktimeindex,
        ]);
    
        // ตรวจสอบว่าการสร้างสำเร็จหรือไม่
        if (!$wipBarcode || !$wipBarcode->wip_id) {
            return response()->json(['message' => 'Failed to create WIP Barcode or WIP ID is missing'], 400);
        }
    
        // บันทึกข้อมูลใน WipProductDate
        WipProductDate::create([
            'wp_working_id' => $input['wp_working_id'],
            'wp_wip_id' => $wipBarcode->wip_id, // ใช้ wip_id จาก Wipbarcode
            'wp_empdate_index_id' => $checktimeindex + 1,
            'wp_date_product' => $barcodeDate->toDateTimeString(),
            'wp_empgroup_id' => $input['wip_empgroup_id'],
        ]);
    
        // อัปเดต EmpInOut
        if (!$empInOut) {
            EmpInOut::create([
                'eio_emp_group' => $input['wip_empgroup_id'],
                'eio_working_id' => $input['wp_working_id'],
                'eio_input_amount' => $input['wip_amount'],
                'eio_line' => substr($input['wip_barcode'], 1, 1),
                'eio_division' => 'QC',
            ]);
        } else {
            $empInOut->update([
                'eio_input_amount' => $empInOut->eio_input_amount + $input['wip_amount'],
            ]);
        }
    }
    
    // Function to check if barcode already exists
    private function isWipBarcodeExists($barcode)
    {
        return Wipbarcode::where('wip_barcode', $barcode)->exists();
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
    
   
}
