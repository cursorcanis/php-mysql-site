<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// Ports the upload + delete handlers from dashboard.php and the
// direct file serving that uploads/ used to provide (now access-controlled
// through routes, with files kept in private storage).
class FileController extends Controller
{
    public function store(Request $request)
    {
        $maxKb = (int) (config('vault.max_file_size') / 1024);

        $request->validate([
            'file'  => ['required', 'file', 'max:' . $maxKb,
                        'mimes:jpg,jpeg,png,gif,webp,txt,doc,docx'],
            'title' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ], [
            'file.max'   => 'File exceeds the ' . ($maxKb / 1024) . ' MB limit.',
            'file.mimes' => 'Invalid file type. Allowed: JPG, PNG, GIF, WEBP, TXT, DOCX, DOC.',
        ]);

        $upload = $request->file('file');
        $ext    = strtolower($upload->getClientOriginalExtension());
        $name   = bin2hex(random_bytes(16)) . '.' . $ext;

        $upload->storeAs(config('vault.upload_path'), $name, config('vault.disk'));

        $file = File::create([
            'filename'      => $name,
            'original_name' => $upload->getClientOriginalName(),
            'mime_type'     => $upload->getMimeType(),
            'file_size'     => $upload->getSize(),
            'file_type'     => self::typeFor($upload->getMimeType()),
            'title'         => $request->input('title') ?: null,
            'notes'         => $request->input('notes') ?: null,
            'uploaded_at'   => now(),
        ]);

        $tagIds = array_filter(array_map('intval', explode(',', $request->input('tag_ids', ''))));
        if ($tagIds) {
            $file->tags()->sync($tagIds);
        }

        return redirect()->route('dashboard')->with('success', 'File uploaded!');
    }

    // Stream a file inline (image preview, text, docx). Keyed on the random
    // filename, which is unguessable — same access model as the old uploads/.
    public function show(string $name)
    {
        $file = File::where('filename', $name)->firstOrFail();

        return $this->stream($file, inline: true);
    }

    public function download(string $name)
    {
        $file = File::where('filename', $name)->firstOrFail();

        return $this->stream($file, inline: false);
    }

    public function destroy(Request $request, File $file)
    {
        Storage::disk(config('vault.disk'))
            ->delete(config('vault.upload_path') . '/' . $file->filename);

        $file->delete(); // cascade removes image_tags rows

        return redirect()->route('dashboard');
    }

    protected function stream(File $file, bool $inline)
    {
        $path = config('vault.upload_path') . '/' . $file->filename;
        $disk = Storage::disk(config('vault.disk'));

        abort_unless($disk->exists($path), 404);

        $disposition = $inline ? 'inline' : 'attachment';
        $headers = [
            'Content-Type'        => $file->mime_type,
            'Content-Disposition' => $disposition . '; filename="' . $file->original_name . '"',
        ];

        return $disk->response($path, $file->original_name, $headers, $disposition);
    }

    public static function typeFor(string $mime): string
    {
        if (str_starts_with($mime, 'image/')) {
            return 'image';
        }
        if ($mime === 'text/plain' || str_starts_with($mime, 'application/')) {
            return 'document';
        }

        return 'file';
    }
}
