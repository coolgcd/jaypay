<?php

namespace App\Http\Controllers;

use App\Models\LegalDocument;

use Illuminate\Support\Facades\Storage;

// app/Models/LegalDocument.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    protected $fillable = ['title', 'filename', 'file_path'];
}

