<?php

return [
    'min_send'=> 1,

    'exchange_url' => env('EXCHANGE_URL'),
    'exchange_key' => env('EXCHANGE_KEY'),

    'ps_mid' => env('PS_MID'),
    'ps_username' => env('PS_USERNAME'),
    'ps_api_key' => env('PS_API_KEY'),
    'ps_verify_url' => env('PS_VERIFY_URL'),

    'fw_pub_key' => env('FW_PUB_KEY'),
    'fw_sec_key' => env('FW_SEC_KEY'),
    'fw_encryption' => env('FW_ENCRYPTION'),
    'fw_verify_url' => env('FW_VERIFY_URL'),
    'fw_bank_url' => env('FW_BANKS_URL'),
    'fw_send_url' => env('FW_SEND_URL'),
    'fw_validate_url' => env('FW_VALIDATE_URL'),
    'fw_requery_url' => env('FW_REQUERY_URL'),

    'orc_payout_url' => env('ORC_PAYOUT_URL'),
    'orc_requery_url' => env('ORC_REQUERY_URL'),
    'orc_payment_url' => env('ORC_PAYMENT_URL'),
    'orc_validate_url' => env('ORC_VALIDATE_URL'),
    'orc_service_id' => env('ORC_SERVICE_ID'),
    'orc_client_key' => env('ORC_CLIENT_KEY'),
    'orc_secret_key' => env('ORC_SECRET_KEY'),

    'cp_bank_url' => env('CP_BANKS_URL'),
    'cp_send_url' => env('CP_SEND_URL'),
    'cp_validate_url' => env('CP_VALIDATE_URL'),
    'cp_business_code' => env('CP_BUSINESS_CODE'),
    'cp_integration_key' => env('CP_INTEGRATION_KEY'),
    'cp_wallet_code' => env('CP_WALLET_CODE'),

    'supported_currencies' => ['GHS', 'NGN', 'ZAR', 'XOF', 'XAF', 'UGX', 'KES', 'ZMW', 'RWF', 'TZS', 'USD', 'EUR', 'GBP']


];
