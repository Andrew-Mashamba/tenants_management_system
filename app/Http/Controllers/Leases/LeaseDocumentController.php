<?php

namespace App\Http\Controllers\Leases;

use App\Models\LeaseDocument;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class LeaseDocumentController extends Controller
{
    public function download(LeaseDocument $document)
    {
        Gate::authorize('download', $document);

        return Storage::download($document->file_path, $document->file_name);
    }
} 