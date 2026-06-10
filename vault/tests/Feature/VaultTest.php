<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VaultTest extends TestCase
{
    use RefreshDatabase;

    private function authed()
    {
        return $this->withSession(['vault_auth' => true]);
    }

    public function test_dashboard_requires_auth(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_login_with_valid_credentials(): void
    {
        $this->post('/login', ['username' => 'alfredo', 'password' => 'labvault'])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('vault_auth', true);
    }

    public function test_login_with_invalid_credentials_fails(): void
    {
        $this->post('/login', ['username' => 'alfredo', 'password' => 'wrong'])
            ->assertSessionHasErrors('login')
            ->assertSessionMissing('vault_auth');
    }

    public function test_full_upload_tag_search_share_flow(): void
    {
        Storage::fake('local');
        Tag::create(['name' => 'render', 'color' => '#ffe066']);
        $tag = Tag::first();

        // Upload an image with a tag
        $this->authed()->post('/upload', [
            'file'    => UploadedFile::fake()->image('shot.jpg', 200, 200),
            'title'   => 'My Render',
            'notes'   => 'test notes',
            'tag_ids' => (string) $tag->id,
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseCount('files', 1);
        $file = File::first();
        $this->assertSame('My Render', $file->title);
        $this->assertSame('image', $file->file_type);
        $this->assertTrue($file->tags->contains($tag));
        Storage::disk('local')->assertExists('uploads/' . $file->filename);

        // Dashboard renders the file (exercises dashboard.blade)
        $this->authed()->get('/dashboard')
            ->assertOk()
            ->assertSee('My Render');

        // Serve the file inline
        $this->get(route('files.show', $file->filename))->assertOk();
        $this->get(route('files.download', $file->filename))->assertOk();

        // API: create a tag
        $this->authed()->post('/api', ['action' => 'create_tag', 'name' => 'newtag', 'color' => '#00e5ff'])
            ->assertJson(['ok' => true, 'name' => 'newtag']);

        // API: search finds the file
        $this->authed()->post('/api', ['action' => 'search', 'q' => 'Render'])
            ->assertJson(['ok' => true])
            ->assertJsonFragment(['title' => 'My Render']);

        // API: generate share token
        $res = $this->authed()->post('/api', ['action' => 'get_share_token', 'image_id' => $file->id])
            ->assertJson(['ok' => true]);
        $token = $res->json('token');
        $this->assertMatchesRegularExpression('/^[a-f0-9]{48}$/', $token);

        // Public share page works
        $this->get(route('share', $token))->assertOk()->assertSee('My Render');

        // API: revoke share
        $this->authed()->post('/api', ['action' => 'revoke_share', 'image_id' => $file->id])
            ->assertJson(['ok' => true]);
        $this->assertNull($file->fresh()->share_token);

        // Delete the file
        $this->authed()->delete(route('files.destroy', $file))
            ->assertRedirect(route('dashboard'));
        $this->assertDatabaseCount('files', 0);
        Storage::disk('local')->assertMissing('uploads/' . $file->filename);
    }

    public function test_upload_rejects_disallowed_type(): void
    {
        Storage::fake('local');

        $this->authed()->post('/upload', [
            'file' => UploadedFile::fake()->create('evil.php', 10, 'application/x-php'),
        ])->assertSessionHasErrors('file');

        $this->assertDatabaseCount('files', 0);
    }
}
