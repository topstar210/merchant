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
