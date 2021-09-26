<?php

function format_exception(\Exception $e)
{
    return [
        'message' => $e->getMessage(),
        'file' => basename($e->getFile()),
        'line' => $e->getLine(),
        'type' => class_basename($e)
    ];
}

function user()
{
    return auth()->user();
}

function readJson($path)
{
    if (file_exists($path)) {
        $jsonContent = file_get_contents($path);
        return json_decode($jsonContent, true);
    } else {
        return [];
    }
}

const DEPOSITS = 1;
const WITHDRAWALS = 2;

function statusList()
{
    return ['Success', 'Failed', 'Pending', 'Blocked', 'Refund'];
}

function formatDate($date)
{
    if (\Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::parse($date)) > 1) {
        return \Carbon\Carbon::parse($date)->format('d F Y h:i A');
    } else {
        return \Carbon\Carbon::parse($date)->diffForHumans();
    }
}

function formatDateOnly($date)
{
        return \Carbon\Carbon::parse($date)->format('d F Y');
}

function switchRouteName($route)
{
    switch ($route) {
        case 'Orchard':
            return ['Mobile Money | Card', 'cardmomo.png'];
            break;
        case 'Payswitch':
            return ['Visa | MasterCard | Mobile Money', 'cardmomo.png'];
            break;
        case 'Paystack':
            return ['Visa', 'card.png'];
            break;
        case 'Flutterwave':
            return ['MasterCard | Visa', 'card.png'];
            break;

        default :
            return [$route, 'card.png'];
    }


}

function switchTransStatus($status)
{
    switch ($status) {
        case 1:
            return 'Success';
            break;
        case 2:
            return 'Failed';
            break;
        case 3:
            return 'Pending';
            break;
        case 4:
            return 'Refund';
            break;

        case 5:
            return 'Blocked';
            break;

        default :
            return 'Failed';
    }
}

function switchSubTransStatus($status)
{
    switch ($status) {
        case 1:
            return 'Success';
            break;
        case 2 || 5:
            return 'Blocked';
            break;
        case 3:
            return 'Pending';
            break;
        case 4:
            return 'Refund';
            break;

        default :
            return 'Failed';
    }
}

function switchProducts($product)
{
    return serviceProductList()[$product] ?? $product;
}

function serviceProductList()
{
    return [
        'WF' => 'Wallet Funding',
        'SW' => 'Send to Wallet',
        'SA' => 'Send to Account',
        'SB' => 'Send to Bank',
        'CW' => 'Commission to Wallet',
    ];
}


function switchSendUGECountry($currency_code, $country_code = null)
{
    switch ($currency_code) {
        case 'USD':
            return 'US';
        case 'GBP':
            return 'UK';
        case 'EUR':
            return $country_code;
        default:
            return $currency_code;
    }
}
