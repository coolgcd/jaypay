<?php

// app/Http/Controllers/LegalDocumentController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LegalDocument;

class LegalDocumentController extends Controller
{
    public function index()
    {
        $documents = LegalDocument::all();
        return view('front.legal', compact('documents')); // assuming your Blade file is legal.blade.php
    }

    public function download($id)
    {
        $document = LegalDocument::findOrFail($id);
        return response()->download(storage_path("app/{$document->file_path}"));
    }
}
