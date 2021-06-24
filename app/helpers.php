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


