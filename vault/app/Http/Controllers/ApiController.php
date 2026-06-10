<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Ports api.php. Single endpoint dispatching on `action`, so the dashboard
// JS keeps its api('action', data) helper almost unchanged. CSRF + auth are
// enforced by route middleware rather than hand-rolled checks.
class ApiController extends Controller
{
    public function handle(Request $request)
    {
        return match ($request->input('action')) {
            'get_tags'        => $this->getTags(),
            'create_tag'      => $this->createTag($request),
            'delete_tag'      => $this->deleteTag($request),
            'set_image_tags'  => $this->setImageTags($request),
            'update_image'    => $this->updateImage($request),
            'get_share_token' => $this->getShareToken($request),
            'revoke_share'    => $this->revokeShare($request),
            'search'          => $this->search($request),
            default           => response()->json(['ok' => false, 'error' => 'Unknown action']),
        };
    }

    private function getTags()
    {
        return response()->json(['ok' => true, 'tags' => Tag::orderBy('name')->get()]);
    }

    private function createTag(Request $request)
    {
        $name  = trim((string) $request->input('name'));
        $color = trim((string) $request->input('color', '#00e5ff'));
        if ($name === '') {
            return response()->json(['ok' => false, 'error' => 'Name required']);
        }
        if (! preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#00e5ff';
        }
        if (Tag::where('name', $name)->exists()) {
            return response()->json(['ok' => false, 'error' => 'Tag already exists']);
        }
        $tag = Tag::create(['name' => $name, 'color' => $color]);

        return response()->json(['ok' => true, 'id' => $tag->id, 'name' => $tag->name, 'color' => $tag->color]);
    }

    private function deleteTag(Request $request)
    {
        Tag::where('id', (int) $request->input('tag_id'))->delete();

        return response()->json(['ok' => true]);
    }

    private function setImageTags(Request $request)
    {
        $file = File::find((int) $request->input('image_id'));
        if (! $file) {
            return response()->json(['ok' => false, 'error' => 'Not found']);
        }
        $tagIds = array_map('intval', json_decode($request->input('tag_ids', '[]'), true) ?: []);
        $file->tags()->sync($tagIds);

        return response()->json(['ok' => true]);
    }

    private function updateImage(Request $request)
    {
        $file = File::find((int) $request->input('image_id'));
        if (! $file) {
            return response()->json(['ok' => false, 'error' => 'Not found']);
        }
        $file->update([
            'title' => trim((string) $request->input('title')) ?: null,
            'notes' => trim((string) $request->input('notes')) ?: null,
        ]);

        return response()->json(['ok' => true]);
    }

    private function getShareToken(Request $request)
    {
        $file = File::find((int) $request->input('image_id'));
        if (! $file) {
            return response()->json(['ok' => false, 'error' => 'Not found']);
        }
        if (! $file->share_token) {
            $file->update(['share_token' => bin2hex(random_bytes(24))]);
        }

        return response()->json(['ok' => true, 'token' => $file->share_token]);
    }

    private function revokeShare(Request $request)
    {
        File::where('id', (int) $request->input('image_id'))->update(['share_token' => null]);

        return response()->json(['ok' => true]);
    }

    private function search(Request $request)
    {
        $q     = trim((string) $request->input('q', ''));
        $tagId = (int) $request->input('tag_id');

        $files = File::with('tags')
            ->when($tagId, fn ($query) => $query->whereHas('tags', fn ($t) => $t->where('tags.id', $tagId)))
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('original_name', 'like', "%{$q}%")
                      ->orWhere('notes', 'like', "%{$q}%");
            })
            ->orderByDesc('uploaded_at')
            ->get();

        return response()->json(['ok' => true, 'images' => $files]);
    }
}
