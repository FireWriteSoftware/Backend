<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StorageController extends Controller
{
    private $imageValidator = 'required|image|mimes:jpeg,png,jpg,gif,svg,ico|max:5120';

    public function upload(Request $request) {
        $validator = Validator::make($request->all(), [
            'image' => $this->imageValidator
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $result = $request->file('image')->store('public');
        $storage_url = Storage::url($result);

        return $this->sendResponse(
            [
                'url' => config('app.url') . $storage_url
            ],
            'Image has been successfully uploaded');
    }

    public function uploadEditor(Request $request) {
        $validator = Validator::make($request->all(), [
            'upload' => $this->imageValidator
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $result = $request->file('upload')->store('public');
        $storage_url = Storage::url($result);

        return [
            'success' => true,
            'message' => 'Image has been successfully uploaded',
            'url' => config('app.url') . $storage_url
        ];
    }

    public function getFileName(Request $request) {
        $found_name = false;

        $time_formatted = date_format(now(), 'c'); # c => ISO 8601
        $f_name = $request->file('image')->getClientOriginalName();
        $base_name = "_${time_formatted}_${f_name}";

        while (!$found_name) {
            $code = Str::random(8);
            $full = $code . $base_name;

            if (Storage::disk('local')->exists($full)) {
                continue;
            }

            $found_name = false;
            return $full;
        }
    }
}
