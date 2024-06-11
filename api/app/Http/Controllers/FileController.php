<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function index()
    {
        $files = File::all();
        return response()->json($files);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'link' => 'required|file|mimes:pdf,doc,docx',
            'description' => 'nullable|string',
            'module_id' => 'required|exists:modules,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $file = File::create($request->all());
        return response()->json($file, 201);
    }

    public function update(Request $request, File $file)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'string',
            'link' => 'file|mimes:pdf,doc,docx',
            'description' => 'nullable|string',
            'module_id' => 'exists:modules,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $file->update($request->all());
        return response()->json($file);
    }

    public function destroy(File $file)
    {
        $file->delete();
        return response()->json(['message' => 'File deleted'], 204);
    }
}
