<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyDocument;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Response;

class PropertyDocumentController extends Controller
{
    use AuthorizesRequests;

    public function index(Property $property)
    {
        $documents = $property->documents()
            ->with(['unit', 'uploadedBy'])
            ->latest()
            ->paginate(10);

        return view('properties.documents.index', compact('property', 'documents'));
    }

    public function create(Property $property)
    {
        $units = $property->units;
        $categories = $property->getDocumentCategories();

        return view('properties.documents.create', compact('property', 'units', 'categories'));
    }

    public function store(Request $request, Property $property)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string',
            'unit_id' => 'nullable|exists:units,id',
            'is_public' => 'boolean',
            'expiry_date' => 'nullable|date'
        ]);

        $file = $request->file('file');
        $path = $file->store('property-documents/' . $property->id, 'public');

        $document = $property->documents()->create([
            'title' => $request->title,
            'category' => $request->category,
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
            'unit_id' => $request->unit_id,
            'is_public' => $request->boolean('is_public'),
            'expiry_date' => $request->expiry_date,
            'uploaded_by' => Auth::id(),
            'metadata' => [
                'original_name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension()
            ]
        ]);

        return redirect()
            ->route('properties.documents.index', $property)
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(Property $property, PropertyDocument $document)
    {
        $this->authorize('view', $document);

        return view('properties.documents.show', compact('property', 'document'));
    }

    public function edit(Property $property, PropertyDocument $document)
    {
        $this->authorize('update', $document);

        $units = $property->units;
        $categories = $property->getDocumentCategories();

        return view('properties.documents.edit', compact('property', 'document', 'units', 'categories'));
    }

    public function update(Request $request, Property $property, PropertyDocument $document)
    {
        $this->authorize('update', $document);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_id' => 'nullable|exists:units,id',
            'is_public' => 'boolean',
            'expiry_date' => 'nullable|date'
        ]);

        $document->update($request->only([
            'title',
            'category',
            'description',
            'unit_id',
            'is_public',
            'expiry_date'
        ]));

        return redirect()
            ->route('properties.documents.index', $property)
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(Property $property, PropertyDocument $document)
    {
        $this->authorize('delete', $document);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()
            ->route('properties.documents.index', $property)
            ->with('success', 'Document deleted successfully.');
    }

    public function download(Property $property, PropertyDocument $document)
    {
        $this->authorize('view', $document);

        return Response::download(
            Storage::disk('public')->path($document->file_path),
            $document->title . '.' . $document->metadata['extension']
        );
    }
}
