<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PropertyDocumentController extends Controller
{
    public function index(Property $property)
    {
        $documents = $property->documents()->latest()->get();
        return view('properties.documents.index', compact('property', 'documents'));
    }

    public function create(Property $property)
    {
        return view('properties.documents.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB max
            'type' => 'required|string|in:lease,maintenance,other',
            'expiry_date' => 'nullable|date',
        ]);

        $path = $request->file('file')->store('property-documents');

        $property->documents()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $path,
            'type' => $validated['type'],
            'expiry_date' => $validated['expiry_date'],
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('properties.documents.index', $property)
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(Property $property, PropertyDocument $document)
    {
        return view('properties.documents.show', compact('property', 'document'));
    }

    public function edit(Property $property, PropertyDocument $document)
    {
        return view('properties.documents.edit', compact('property', 'document'));
    }

    public function update(Request $request, Property $property, PropertyDocument $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:lease,maintenance,other',
            'expiry_date' => 'nullable|date',
        ]);

        $document->update($validated);

        return redirect()->route('properties.documents.index', $property)
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(Property $property, PropertyDocument $document)
    {
        Storage::delete($document->file_path);
        $document->delete();

        return redirect()->route('properties.documents.index', $property)
            ->with('success', 'Document deleted successfully.');
    }

    public function download(Property $property, PropertyDocument $document)
    {
        return Storage::download($document->file_path, $document->title);
    }
} 