<?php


namespace App\Services;


use App\Http\Utils\Resource;
use App\Models\MerchantPayment;

class SummaryService
{
    public static function handleSummary(array $requestData)
    {
        $_data = [];
        foreach (config('env.supported_currencies') as $cur) {
            $data = [];
            $data['sum_cur'] = $cur;
            $data['sum_d_c'] = MerchantPayment::altSummary(['type' => 1, 'date' => $requestData['date'] ?? null, 'cur' => $cur])->sum('charges');
            $data['sum_w'] = MerchantPayment::altSummary(['type' => 2, 'date' => $requestData['date'] ?? null, 'cur' => $cur])->sum('amount');
            $data['sum_w_c'] = MerchantPayment::altSummary(['type' => 2, 'date' => $requestData['date'] ?? null, 'cur' => $cur])->sum('charges');
            array_push($_data, $data);
        }
        return response()->json($_data);
    }
}
