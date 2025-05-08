<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $documents = Document::whereHas('property', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['versions', 'category'])->get();

        return response()->json($documents);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'category_id' => 'required|exists:document_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB max
            'expiry_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $path = $file->store('documents');

        $document = Document::create([
            'property_id' => $request->property_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'expiry_date' => $request->expiry_date,
        ]);

        DocumentVersion::create([
            'document_id' => $document->id,
            'version' => '1.0',
            'file_path' => $path,
            'uploaded_by' => Auth::id(),
        ]);

        return response()->json($document->load(['versions', 'category']), 201);
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);
        return response()->json($document->load(['versions', 'category']));
    }

    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'expiry_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $document->update($request->all());
        return response()->json($document->load(['versions', 'category']));
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);
        
        foreach ($document->versions as $version) {
            Storage::delete($version->file_path);
        }
        
        $document->delete();
        return response()->json(null, 204);
    }

    public function download(Document $document)
    {
        $this->authorize('view', $document);
        $latestVersion = $document->versions()->latest()->first();
        
        return Storage::download($latestVersion->file_path, $document->name);
    }

    public function uploadNewVersion(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $latestVersion = $document->versions()->latest()->first();
        $versionParts = explode('.', $latestVersion->version);
        $newVersion = $versionParts[0] . '.' . ($versionParts[1] + 1);

        $file = $request->file('file');
        $path = $file->store('documents');

        DocumentVersion::create([
            'document_id' => $document->id,
            'version' => $newVersion,
            'file_path' => $path,
            'description' => $request->description,
            'uploaded_by' => Auth::id(),
        ]);

        return response()->json($document->load(['versions', 'category']));
    }
} 