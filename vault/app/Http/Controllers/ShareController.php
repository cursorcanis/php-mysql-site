<?php

namespace App\Http\Controllers;

use App\Models\File;

// Ports share.php — public view of a file via its share token.
class ShareController extends Controller
{
    public function show(string $token)
    {
        $file = null;

        if (preg_match('/^[a-f0-9]{48}$/', $token)) {
            $file = File::with('tags')->where('share_token', $token)->first();
        }

        return view('share', ['file' => $file]);
    }
}
