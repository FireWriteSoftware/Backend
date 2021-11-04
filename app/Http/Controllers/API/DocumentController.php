<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentCollection;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function get_all(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'integer',
            'paginate' => 'boolean',
            'search' => 'string',
            'sort' => 'array',
            'sort.column' => 'string|required_with:sort',
            'sort.method' => 'string|required_with:sort',
            'additional' => 'array',
            'recent' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $data = (new Document);

        $per_page = $request->get('per_page', 15);
        $paginate_data = $request->get('paginate', true);
        $recent = $request->get('recent', 0);
        $search = $request->get('search');

        if ($request->has('sort.column') && $request->has('sort.method')) {
            $data = $data->orderBy(
                $request->get('sort')['column'],
                $request->get('sort')['method'],
            );
        }

        # Hide expired & limit exceeded documents
        $data = $data->where('expires_at', '<', DB::raw('NOW()'));

        if ($recent > 0) {
            $data = $data->take($recent);
        }

        if ($search) {
            foreach ((new Document())->getFillable() as $inx => $column) {
                if ($inx === 0) {
                    $data = $data->where($column, 'LIKE', '%' . $search . '%');
                } else {
                    $data = $data->orWhere($column, 'LIKE', '%' . $search . '%');
                }
            }
        }

        if ($paginate_data) {
            $data = $data->paginate($per_page);
        } else {
            $data = $data->get();
        }

        $response = (new DocumentCollection($data));

        if ($request->has('additional')) {
            $additional = $request->get('additional');

            $response = $response::additional(array_merge([
                'success' => true,
                'message' => __('base.base.get_all_success+')
            ],
                $additional));
        }

        return $response;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_category' => 'nullable|boolean',
            'category_id' => 'nullable|integer|exists:categories,id',
            'is_post' => 'nullable|boolean',
            'post_id' => 'nullable|integer|exists:posts,id',
            'title' => 'required|string|max:255',
            'file' => 'required|max:10240|mimes:doc,docx,odt,jpg,png,jpeg,svg,gif',
            'expires_at' => 'nullable|date|after_or_equal:now',
            'password' => 'nullable|string|max:255',
            'max_downloads' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $data = $request->all();
        if ($request->has('password')) $data['password'] = Hash::make($data['password']);
        $result = $request->file('file')->store('public');
        $data['file_name'] = config('app.url') . Storage::url($result);
        $data['file'] = null;
        $data['user_id'] = auth()->id();

        $created_object = Document::create($data);

        if (is_null($created_object)) {
            return $this->sendError(__('base.base.store_unknown_error'), [], 500);
        }

        $response = new \App\Http\Resources\Document($created_object);
        return $this->sendResponse($response, __('base.base.store_success'));
    }

    public function get_single(Request $request, Document $document)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        if ($document->password) {
            if (!$request->has('password')) {
                return $this->sendError(__('documents.password.required'));
            }

            if (Hash::check($request->get('password'), $document->password)) {
                return $this->sendError(__('documents.password.invalid'));
            }
        }

        if ($document->max_downloads > $document->downloads()->count()) {
            return $this->sendError(__('documents.downloads.reached_limit'));
        }

        if ($document->expires_at >= Carbon::now()) {
            return $this->sendError(__('documents.expired'));
        }

        $document->downloads()->create([
           'user_id' => auth()->id()
        ]);

        $response = new \App\Http\Resources\Document($document);
        return $this->sendResponse($response, __('base.base.get_success'));
    }

    public function update(Request $request, Document $document)
    {
        $validator = Validator::make($request->all(), [
            'is_category' => 'nullable|boolean',
            'category_id' => 'nullable|integer|exists:categories,id',
            'is_post' => 'nullable|boolean',
            'post_id' => 'nullable|integer|exists:posts,id',
            'title' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date|after_or_equal:now',
            'password' => 'nullable|string|max:255',
            'max_downloads' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $data = $request->all();
        if ($request->has('password')) $data['password'] = Crypt::encrypt($data['password']);

        $document->update($data);
        $document->save();

        $response = new \App\Http\Resources\Document($document);
        return $this->sendResponse($response, __('base.base.get_success'));
    }

    public function delete(Document $document)
    {
        $document->delete();

        return $this->sendResponse([
            'id' => $document->id
        ], __('base.base.soft_delete_success'));
    }

    public function force_delete(Document $document) {
        $id = $document->id;
        Storage::delete('public/' . $document->file_name);
        $document->forceDelete();

        return $this->sendResponse([
            'id' => $id
        ], __('base.base.force_delete_success'));
    }

    public function recover($id) {
        $item = Document::withTrashed()->find($id);

        if (is_null($item)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $item->restore();

        return $this->sendResponse([
            'id' => $item->id
        ], __('base.base.soft_delete_success'));
    }
}
