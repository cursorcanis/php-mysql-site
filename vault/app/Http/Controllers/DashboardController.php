<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Tag;

// Ports the gallery rendering from dashboard.php.
class DashboardController extends Controller
{
    public function index()
    {
        $files = File::with('tags')->orderByDesc('uploaded_at')->get();

        return view('dashboard', [
            'files'     => $files,
            'allTags'   => Tag::orderBy('name')->get(),
            'totalSize' => $files->sum('file_size'),
        ]);
    }
}
