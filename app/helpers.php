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
    return ['Blocked', 'Failed', 'Pending', 'Refund', 'Success'];
}

function formatDate($date)
{
    if (\Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::parse($date)) > 12) {
        return \Carbon\Carbon::parse($date)->format('d F Y h:i A');
    } else {
        return \Carbon\Carbon::parse($date)->diffForHumans();
    }
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
