<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['uploadedBy', 'documentable'])
            ->latest()
            ->paginate(10);

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:lease,contract,form,invoice,other',
            'file' => 'required|file|max:10240', // 10MB max
            'documentable_type' => 'required|string',
            'documentable_id' => 'required|integer'
        ]);

        $file = $request->file('file');
        $path = $file->store('documents');

        $document = Document::create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
            'documentable_type' => $request->documentable_type,
            'documentable_id' => $request->documentable_id,
        ]);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);
        return view('documents.show', compact('document'));
    }

    public function download(Document $document)
    {
        $this->authorize('view', $document);
        
        return response()->download(
            storage_path('app/' . $document->file_path),
            $document->name . '.' . $document->file_type
        );
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);
        
        // Delete the file from storage
        if (file_exists(storage_path('app/' . $document->file_path))) {
            unlink(storage_path('app/' . $document->file_path));
        }
        
        $document->delete();
        
        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function updateStatus(Document $document, Request $request)
    {
        $this->authorize('update', $document);

        $request->validate([
            'status' => 'required|string|in:active,archived,deleted'
        ]);

        $document->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Document status updated successfully.');
    }
} 