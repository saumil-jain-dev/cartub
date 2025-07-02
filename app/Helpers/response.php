<?php

use Carbon\Carbon;

# Response
function res($data, $message, $code)
{
    $response = [
        'code' => $code,
        'message' => $message,
        'timestamp' => Carbon::now()->timestamp,
        'data' => $data
    ];
    return response()->json($response, $code);
}

# Success Response
function success($data = [], $message = 'Success', $code = 200)
{
    return res($data, $message, $code);
}

# Fail Response
function fail($data = [], $message = "Error", $code = 203)
{
    return res($data, $message, $code);
}

# Error Parse
function error_parse($message)
{
    foreach ($message->toArray() as $key => $value) {
        foreach ($value as $ekey => $evalue) {
            return $evalue;
        }
    }
}
