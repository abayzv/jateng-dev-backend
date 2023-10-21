<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

function generateUuid()
{
    $uuid = time() * 1000;
    return $uuid;
}

function uploadImage($image, $path)
{
    try {
        $imageName = time() . '.' . $image->extension();
        $url = Storage::putFileAs('public/images/' . $path, $image, $imageName);
        return $url;
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
    }
}

function deleteImage($pathName)
{
    try {
        Storage::delete($pathName);
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
    }
}

function generateRandomToken()
{
    return bin2hex(random_bytes(32));
}

function uniqCode($existingCodes)
{
    $code = rand(100000, 999999);
    // if code not in existingCodes, return code
    if (!in_array($code, $existingCodes)) {
        return $code;
    } else {
        uniqCode($existingCodes);
    }
}

function createValidator($schema)
{
    $rules = [];
    foreach ($schema as $field) {
        switch ($field['input']) {
            case 'text':
                if (!isset($field['nullable'])) {
                    $rules[$field['name']] = 'required';
                }
                if (isset($field['length'])) {
                    $rules[$field['name']] .= '|max:' . $field['length'];
                }
                $rules[$field['name']] .= '|string';
                break;
            case 'file':
                if (!isset($field['nullable'])) {
                    $rules[$field['name']] = 'required';
                }
                $rules[$field['name']] .= '|file';
                break;
        }
    }

    $fields = [];
    foreach ($rules as $key => $value) {
        $fields[] = $key;
    }
    $request = request()->only($fields);

    return Validator::make($request, $rules);
}
