<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WeightBabyController extends Controller
{
    public function sendWeightBabyData(Request $request)
    {
        // รับค่าจากฟอร์ม
        $data = [
            "atwb_lot" => $request->input('atwb_lot'),
            "atwb_weight_baby" => $request->input('atwb_weight_baby'),
            "atwb_sequence" => $request->input('atwb_sequence'),
            "atwb_weight_all" => $request->input('atwb_weight_all'),
            "atwb_weight_10" => $request->input('atwb_weight_10') ?? null,
        ];

        // URL API ที่ต้องส่งข้อมูลไป
        $apiUrl = 'https://103.40.144.248:8081/myapp/api/weightbaby';

        try {
            $client = new Client();
            $response = $client->post($apiUrl, [
                'json' => $data,
                'verify' => false // ปิด SSL Verification ถ้าจำเป็น
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data sent successfully!',
                'response' => json_decode($response->getBody(), true),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send data!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
