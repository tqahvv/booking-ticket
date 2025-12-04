<?php

return [

    'vnp_TmnCode' => env('VNP_TMN_CODE', 'IKA28AEV'),

    'vnp_HashSecret' => env('VNP_HASH_SECRET', 'Q8RFKJU84UNHCBXR7W71XGIZ4E2O04FL'),

    'vnp_Url' => env('VNP_PAYMENT_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),

    'vnp_ReturnUrl' => env('VNP_RETURN_URL', 'http://127.0.0.1:8000/booking/vnpay-return'),

];
