<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Lab Vault">
<meta name="theme-color" content="#00e5ff">
<link rel="manifest" href="/manifest.json">
<link rel="apple-touch-icon" href="/icons/icon-192.png">
<title>{{ config('vault.title') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Fira+Code:wght@300;400&display=swap" rel="stylesheet">
<style>
@verbatim
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#080b10;--surface:#0e1420;--border:#1e2a3a;
  --accent:#00e5ff;--accent2:#ff3d71;--accent3:#ffe066;
  --text:#c8d8e8;--muted:#4a5a6a;
  --nav-h:64px;--safe-b:env(safe-area-inset-bottom,0px);
  --fd:'Syne',sans-serif;--fm:'Fira Code',monospace;
}
html,body{height:100%}
body{background:var(--bg);color:var(--text);font-family:var(--fm);overscroll-behavior:none}
body::before{content:'';position:fixed;inset:0;z-index:0;
  background-image:linear-gradient(rgba(0,229,255,.02) 1px,transparent 1px),
  linear-gradient(90deg,rgba(0,229,255,.02) 1px,transparent 1px);
  background-size:40px 40px;pointer-events:none}
.app{position:relative;z-index:1;display:flex;flex-direction:column;min-height:100vh}
.topbar{position:sticky;top:0;z-index:100;background:rgba(8,11,16,.93);
  backdrop-filter:blur(12px);border-bottom:1px solid var(--border);
  padding:.75rem 1rem;display:flex;align-items:center;gap:.6rem}
.logo{font-family:var(--fd);font-weight:800;font-size:1.05rem;color:#fff;flex-shrink:0}
.logo span{color:var(--accent)}
.search-bar{flex:1;display:flex;align-items:center;gap:.5rem;
  background:var(--surface);border:1px solid var(--border);border-radius:8px;
  padding:.45rem .75rem;transition:border-color .2s}
.search-bar:focus-within{border-color:var(--accent)}
.search-bar input{flex:1;background:none;border:none;outline:none;
  font-family:var(--fm);font-size:.82rem;color:var(--text)}
.search-bar input::placeholder{color:var(--muted)}
.sicon{color:var(--muted);font-size:.9rem;flex-shrink:0}
.btn-out{background:none;border:1px solid var(--border);color:var(--muted);
  border-radius:6px;font-size:1rem;width:36px;height:36px;
  display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;
  transition:border-color .2s,color .2s}
.btn-out:hover{border-color:var(--accent2);color:var(--accent2)}
.main{flex:1;padding:1rem;padding-bottom:calc(var(--nav-h) + var(--safe-b) + 1rem)}
.install-banner{display:none;background:var(--surface);border:1px solid rgba(0,229,255,.2);
  border-radius:10px;padding:.85rem 1rem;margin-bottom:1rem;
  align-items:center;gap:.75rem}
.install-banner.show{display:flex}
.ib-text{flex:1;font-size:.78rem;color:var(--text)}
.ib-text strong{display:block;font-family:var(--fd);color:#fff;margin-bottom:.2rem}
.btn-install{background:var(--accent);color:#000;border:none;border-radius:6px;
  font-family:var(--fd);font-weight:700;font-size:.75rem;padding:.5rem 1rem;cursor:pointer;flex-shrink:0}
.alert{border-radius:6px;font-size:.78rem;padding:.65rem .9rem;margin-bottom:.85rem}
.alert-error{background:rgba(255,61,113,.1);border:1px solid rgba(255,61,113,.3);color:var(--accent2)}
.alert-success{background:rgba(0,229,255,.08);border:1px solid rgba(0,229,255,.2);color:var(--accent)}
.stats{display:flex;gap:.75rem;margin-bottom:1rem}
.stat{flex:1;background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:.75rem}
.stat-label{font-size:.6rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);margin-bottom:.3rem}
.stat-val{font-family:var(--fd);font-size:1.4rem;font-weight:800;color:var(--accent);line-height:1}
.stat:nth-child(2) .stat-val{color:var(--accent3)}
.tag-filters{display:flex;gap:.5rem;overflow-x:auto;padding:0 0 .75rem;
  scrollbar-width:none;-webkit-overflow-scrolling:touch}
.tag-filters::-webkit-scrollbar{display:none}
.tag-pill{flex-shrink:0;font-size:.65rem;font-family:var(--fm);letter-spacing:.08em;
  text-transform:uppercase;padding:.35rem .8rem;border-radius:999px;
  border:1px solid var(--border);background:none;color:var(--muted);
  cursor:pointer;transition:all .15s;white-space:nowrap}
.tag-pill.active{background:rgba(0,229,255,.08)}
.gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
@media(min-width:540px){.gallery{grid-template-columns:repeat(3,1fr)}}
@media(min-width:768px){.gallery{grid-template-columns:repeat(4,1fr)}}
@media(min-width:1024px){.gallery{grid-template-columns:repeat(5,1fr)}}
.img-card{background:var(--surface);border:1px solid var(--border);
  border-radius:10px;overflow:hidden;cursor:pointer;
  animation:fadeUp .35s ease both;transition:border-color .2s,transform .2s}
.img-card:active{transform:scale(.97)}
.img-card:hover{border-color:rgba(0,229,255,.25)}
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.img-thumb{width:100%;aspect-ratio:1;object-fit:cover;display:block;background:var(--bg)}
.doc-thumb{display:flex;align-items:center;justify-content:center;flex-direction:column;gap:.4rem;
  color:var(--muted);font-family:var(--fd);font-weight:700;font-size:.85rem;letter-spacing:.06em}
.doc-thumb .doc-icon{font-size:2.2rem}
.img-body{padding:.6rem}
.img-title-sm{font-family:var(--fd);font-size:.75rem;font-weight:600;color:#fff;
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:.3rem}
.img-tags{display:flex;flex-wrap:wrap;gap:.25rem}
.tag-chip{font-size:.55rem;letter-spacing:.06em;text-transform:uppercase;
  padding:.15rem .4rem;border-radius:999px;border:1px solid}
.empty{grid-column:1/-1;text-align:center;padding:4rem 1rem;color:var(--muted);font-size:.82rem}
.empty .icon{font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.3}
.bottom-nav{position:fixed;bottom:0;left:0;right:0;z-index:200;
  height:calc(var(--nav-h) + var(--safe-b));
  background:rgba(8,11,16,.95);backdrop-filter:blur(12px);
  border-top:1px solid var(--border);
  display:flex;align-items:flex-start;padding-top:.5rem;padding-bottom:var(--safe-b)}
.nav-item{flex:1;display:flex;flex-direction:column;align-items:center;gap:.25rem;
  background:none;border:none;cursor:pointer;font-family:var(--fm);font-size:.6rem;
  letter-spacing:.08em;text-transform:uppercase;color:var(--muted);padding:.25rem 0;
  transition:color .2s}
.nav-icon{font-size:1.3rem;line-height:1}
.nav-item.active{color:var(--accent)}
.nav-upload{width:52px;height:52px;margin-top:-18px;background:var(--accent);
  border-radius:50%;border:none;color:#000;font-size:1.5rem;cursor:pointer;
  display:flex;align-items:center;justify-content:center;flex-shrink:0;
  box-shadow:0 0 20px rgba(0,229,255,.4);transition:background .2s,transform .1s}
.nav-upload:active{transform:scale(.92)}
.nav-upload:hover{background:#33eaff}
.overlay{display:none;position:fixed;inset:0;z-index:300;
  background:rgba(0,0,0,.85);backdrop-filter:blur(4px);
  align-items:flex-end;justify-content:center}
.overlay.open{display:flex}
@media(min-width:600px){.overlay{align-items:center}}
.sheet{background:var(--surface);border:1px solid var(--border);
  border-radius:16px 16px 0 0;width:100%;max-width:560px;
  padding:1.25rem 1.25rem calc(1.25rem + var(--safe-b));
  max-height:92vh;overflow-y:auto;animation:slideUp .3s ease}
@media(min-width:600px){.sheet{border-radius:16px;max-height:85vh}}
@keyframes slideUp{from{transform:translateY(40px);opacity:0}to{transform:translateY(0);opacity:1}}
.sheet-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem}
.sheet-title{font-family:var(--fd);font-weight:800;font-size:1rem;color:#fff}
.btn-close{background:none;border:1px solid var(--border);color:var(--muted);
  border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;
  cursor:pointer;font-size:.9rem;flex-shrink:0;transition:border-color .2s,color .2s}
.btn-close:hover{border-color:var(--accent2);color:var(--accent2)}
.field{margin-bottom:1rem}
.field label{display:block;font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;
  color:var(--muted);margin-bottom:.4rem}
.field input[type="text"],.field textarea{width:100%;background:var(--bg);border:1px solid var(--border);
  border-radius:6px;color:var(--text);font-family:var(--fm);font-size:.85rem;
  padding:.65rem .9rem;outline:none;transition:border-color .2s;resize:vertical}
.field input:focus,.field textarea:focus{border-color:var(--accent)}
.capture-zone{border:2px dashed var(--border);border-radius:10px;padding:2rem 1rem;
  text-align:center;cursor:pointer;position:relative;
  transition:border-color .2s,background .2s;margin-bottom:1rem}
.capture-zone:hover,.capture-zone.has-file{border-color:var(--accent);background:rgba(0,229,255,.04)}
.capture-zone input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
.capture-icon{font-size:2rem;display:block;margin-bottom:.5rem}
.ctext{font-size:.8rem;color:var(--muted)}
.ctext strong{display:block;color:var(--text);font-family:var(--fd);font-weight:700;
  margin-bottom:.25rem;font-size:.9rem}
#capture-preview{width:100%;max-height:200px;object-fit:cover;
  border-radius:6px;display:none;margin-bottom:.75rem}
.tag-select{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.4rem}
.tag-toggle{font-size:.65rem;font-family:var(--fm);letter-spacing:.08em;text-transform:uppercase;
  padding:.3rem .7rem;border-radius:999px;border:1px solid var(--border);
  background:none;color:var(--muted);cursor:pointer;transition:all .15s}
.btn-primary{width:100%;background:var(--accent);color:#000;border:none;border-radius:8px;
  font-family:var(--fd);font-weight:700;font-size:.9rem;letter-spacing:.06em;text-transform:uppercase;
  padding:.9rem;cursor:pointer;transition:background .2s,box-shadow .2s}
.btn-primary:hover{background:#33eaff;box-shadow:0 0 20px rgba(0,229,255,.3)}
.btn-primary:disabled{opacity:.5;cursor:not-allowed}
.btn-secondary{width:100%;background:none;color:var(--text);border:1px solid var(--border);
  border-radius:8px;font-family:var(--fm);font-size:.8rem;letter-spacing:.06em;
  text-transform:uppercase;padding:.75rem;cursor:pointer;margin-top:.5rem;
  transition:border-color .2s,color .2s;text-decoration:none;display:block;text-align:center}
.btn-secondary:hover{border-color:var(--accent);color:var(--accent)}
.btn-danger-full{width:100%;background:rgba(255,61,113,.1);color:var(--accent2);
  border:1px solid rgba(255,61,113,.3);border-radius:8px;font-family:var(--fm);font-size:.8rem;
  letter-spacing:.06em;text-transform:uppercase;padding:.75rem;cursor:pointer;margin-top:.5rem;
  transition:background .2s}
.btn-danger-full:hover{background:rgba(255,61,113,.2)}
.detail-img{width:100%;border-radius:8px;margin-bottom:1rem}
.detail-meta{font-size:.7rem;color:var(--muted);margin-bottom:.75rem;line-height:1.8}
.detail-tags{display:flex;flex-wrap:wrap;gap:.35rem;margin-bottom:1rem}
.share-box{background:var(--bg);border:1px solid var(--border);border-radius:8px;
  padding:.75rem;margin-bottom:.75rem}
.share-label{font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}
.share-url{font-family:var(--fm);font-size:.72rem;color:var(--accent);word-break:break-all;margin-top:.25rem}
.share-actions{display:flex;gap:.5rem;margin-top:.5rem}
.btn-share{flex:1;background:none;border:1px solid var(--accent);color:var(--accent);
  border-radius:6px;font-family:var(--fm);font-size:.7rem;padding:.4rem .75rem;cursor:pointer;
  letter-spacing:.06em;text-transform:uppercase;transition:background .2s}
.btn-share:hover{background:rgba(0,229,255,.1)}
.btn-revoke{flex:1;background:none;border:1px solid rgba(255,61,113,.4);color:var(--accent2);
  border-radius:6px;font-family:var(--fm);font-size:.7rem;padding:.4rem .75rem;cursor:pointer;
  letter-spacing:.06em;text-transform:uppercase;transition:background .2s}
.btn-revoke:hover{background:rgba(255,61,113,.08)}
.tag-mgmt-row{display:flex;align-items:center;justify-content:space-between;
  padding:.6rem 0;border-bottom:1px solid var(--border)}
.tag-mgmt-row:last-child{border-bottom:none}
.tname{display:flex;align-items:center;gap:.6rem;font-size:.82rem}
.swatch{width:10px;height:10px;border-radius:50%;flex-shrink:0}
.btn-tdel{background:none;border:none;color:var(--muted);cursor:pointer;font-size:.9rem;padding:.25rem}
.btn-tdel:hover{color:var(--accent2)}
.color-swatches{display:flex;gap:.4rem;flex-wrap:wrap;margin-top:.5rem}
.csw{width:24px;height:24px;border-radius:50%;cursor:pointer;
  border:2px solid transparent;transition:border-color .15s}
.csw.active,.csw:hover{border-color:#fff}
.spinner{display:inline-block;width:16px;height:16px;border:2px solid rgba(0,229,255,.2);
  border-top-color:var(--accent);border-radius:50%;animation:spin .6s linear infinite;vertical-align:middle;margin-right:.4rem}
@keyframes spin{to{transform:rotate(360deg)}}
@endverbatim
</style>
</head>
<body>
<div class="app">
  <header class="topbar">
    <div class="logo">Alea <span>Lab</span></div>
    <div class="search-bar">
      <span class="sicon">⌕</span>
      <input type="text" id="searchInput" placeholder="Search images…" autocomplete="off">
    </div>
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
      @csrf
      <button type="submit" class="btn-out" title="Logout">⏻</button>
    </form>
  </header>

  <main class="main">
    <div class="install-banner" id="installBanner">
      <div class="ib-text"><strong>Install Lab Vault</strong>Add to home screen for quick access</div>
      <button class="btn-install" id="installBtn">Install</button>
    </div>

    @if (session('error'))
      <div class="alert alert-error">⚠ {{ session('error') }}</div>
    @endif
    @error('file')
      <div class="alert alert-error">⚠ {{ $message }}</div>
    @enderror
    @if (session('success'))
      <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif

    <div class="stats">
      <div class="stat"><div class="stat-label">Files</div><div class="stat-val">{{ $files->count() }}</div></div>
      <div class="stat"><div class="stat-label">Storage</div>
        <div class="stat-val">{{ $totalSize > 1048576 ? round($totalSize/1048576,1).'MB' : round($totalSize/1024).'KB' }}</div>
      </div>
    </div>

    @if ($allTags->count())
    <div class="tag-filters" id="tagFilters">
      <button class="tag-pill active" data-tag-id="0" style="color:var(--accent);border-color:var(--accent)">All</button>
      @foreach ($allTags as $tag)
        <button class="tag-pill" data-tag-id="{{ $tag->id }}" style="color:{{ $tag->color }}">
          {{ $tag->name }}
        </button>
      @endforeach
    </div>
    @endif

    <div class="gallery" id="gallery">
      @if ($files->isEmpty())
        <div class="empty"><span class="icon">⬡</span>No files yet — tap ＋ to upload.</div>
      @else
        @foreach ($files as $i => $file)
          <div class="img-card" style="animation-delay:{{ $i*.03 }}s"
               data-id="{{ $file->id }}"
               data-title="{{ $file->title }}"
               data-orig="{{ $file->original_name }}"
               data-notes="{{ $file->notes }}"
               data-date="{{ $file->uploaded_at }}"
               data-size="{{ $file->file_size }}"
               data-mime="{{ $file->mime_type }}"
               data-src="{{ route('files.show', $file->filename) }}"
               data-download="{{ route('files.download', $file->filename) }}"
               data-tags="{{ json_encode($file->tags->map(fn($t)=>['id'=>$t->id,'name'=>$t->name,'color'=>$t->color])) }}"
               onclick="openDetail(this)">
            @if (str_starts_with($file->mime_type, 'image/'))
              <img class="img-thumb" src="{{ route('files.show', $file->filename) }}"
                   alt="{{ $file->original_name }}" loading="lazy">
            @else
              <div class="img-thumb doc-thumb">
                <span class="doc-icon">📄</span>
                <span>{{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}</span>
              </div>
            @endif
            <div class="img-body">
              <div class="img-title-sm">{{ $file->title ?: $file->original_name }}</div>
              @if ($file->tags->count())
                <div class="img-tags">
                  @foreach ($file->tags as $t)
                    <span class="tag-chip" style="color:{{ $t->color }};border-color:{{ $t->color }}44">
                      {{ $t->name }}
                    </span>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </main>

  <nav class="bottom-nav">
    <button class="nav-item active" id="navGallery">
      <span class="nav-icon">⊞</span>Gallery
    </button>
    <button class="nav-upload" onclick="openOverlay('uploadOverlay')" title="Upload">＋</button>
    <button class="nav-item" id="navTags" onclick="openOverlay('tagsOverlay')">
      <span class="nav-icon">⊛</span>Tags
    </button>
  </nav>
</div>

<!-- Upload Modal -->
<div class="overlay" id="uploadOverlay" onclick="overlayClick(event,'uploadOverlay')">
  <div class="sheet">
    <div class="sheet-header">
      <span class="sheet-title">Upload File</span>
      <button class="btn-close" onclick="closeOverlay('uploadOverlay')">✕</button>
    </div>
    <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data" id="uploadForm">
      @csrf
      <input type="hidden" name="tag_ids" id="selectedTagIds" value="">
      <img id="capture-preview" alt="Preview">
      <div class="capture-zone" id="captureZone">
        <input type="file" name="file" id="fileInput" accept=".jpg,.jpeg,.png,.gif,.webp,.txt,.doc,.docx" required>
        <span class="capture-icon">📄</span>
        <div class="ctext"><strong>Tap to choose file</strong>Images, documents, text · max 100 MB</div>
      </div>
      <div class="field">
        <label>Title (optional)</label>
        <input type="text" name="title" placeholder="e.g. Project Notes">
      </div>
      <div class="field">
        <label>Notes (optional)</label>
        <textarea name="notes" rows="2" placeholder="e.g. Q2 planning docs"></textarea>
      </div>
      <div class="field">
        <label>Tags</label>
        <div class="tag-select" id="uploadTagSelect">
          @foreach ($allTags as $tag)
            <button type="button" class="tag-toggle" data-id="{{ $tag->id }}"
                    data-color="{{ $tag->color }}"
                    onclick="toggleUploadTag(this)">{{ $tag->name }}</button>
          @endforeach
        </div>
      </div>
      <button type="submit" class="btn-primary" id="uploadBtn">Upload →</button>
    </form>
  </div>
</div>

<!-- Detail Modal -->
<div class="overlay" id="detailOverlay" onclick="overlayClick(event,'detailOverlay')">
  <div class="sheet">
    <div class="sheet-header">
      <span class="sheet-title" id="detailTitle">File</span>
      <button class="btn-close" onclick="closeOverlay('detailOverlay')">✕</button>
    </div>
    <img class="detail-img" id="detailImg" src="" alt="" style="display:none">
    <pre id="detailText" style="display:none;background:var(--bg);border:1px solid var(--border);border-radius:6px;padding:1rem;overflow:auto;max-height:300px;font-size:.75rem;color:var(--text);white-space:pre-wrap;word-wrap:break-word;margin-bottom:1rem"></pre>
    <iframe id="detailDocIframe" style="display:none;width:100%;height:400px;border:1px solid var(--border);border-radius:6px;margin-bottom:1rem"></iframe>
    <div class="detail-meta" id="detailMeta"></div>
    <div class="detail-tags" id="detailTagsRow"></div>
    <div class="field">
      <label>Notes</label>
      <textarea id="detailNotes" rows="3" style="width:100%;background:var(--bg);border:1px solid var(--border);border-radius:6px;color:var(--text);font-family:var(--fm);font-size:.82rem;padding:.65rem .9rem;outline:none;resize:vertical;transition:border-color .2s"></textarea>
    </div>
    <div class="field">
      <label>Tags</label>
      <div class="tag-select" id="detailTagSelect"></div>
    </div>
    <button class="btn-secondary" onclick="saveImageEdits()" id="saveEditsBtn" style="margin-top:.5rem">Save Changes</button>
    <div class="share-box">
      <div class="share-label">Share Link</div>
      <div class="share-url" id="shareUrl">Not shared</div>
      <div class="share-actions">
        <button class="btn-share" onclick="genShareLink()">Generate Link</button>
        <button class="btn-revoke" id="revokeBtn" onclick="revokeShare()" style="display:none">Revoke</button>
      </div>
    </div>
    <a id="detailDownload" href="#" download class="btn-secondary">↓ Download</a>
    <form method="POST" id="deleteForm" action="" onsubmit="return confirm('Delete this file permanently?')">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn-danger-full">✕ Delete File</button>
    </form>
  </div>
</div>

<!-- Tags Modal -->
<div class="overlay" id="tagsOverlay" onclick="overlayClick(event,'tagsOverlay')">
  <div class="sheet">
    <div class="sheet-header">
      <span class="sheet-title">Manage Tags</span>
      <button class="btn-close" onclick="closeOverlay('tagsOverlay')">✕</button>
    </div>
    <div id="tagsList">
      @foreach ($allTags as $tag)
        <div class="tag-mgmt-row" id="tagRow_{{ $tag->id }}">
          <div class="tname">
            <span class="swatch" style="background:{{ $tag->color }}"></span>
            <span style="color:{{ $tag->color }}">{{ $tag->name }}</span>
          </div>
          <button class="btn-tdel" onclick="deleteTag({{ $tag->id }})">✕</button>
        </div>
      @endforeach
    </div>
    <div style="margin-top:1.5rem">
      <div style="font-family:var(--fd);font-weight:700;font-size:.85rem;color:#fff;margin-bottom:.75rem">New Tag</div>
      <div class="field">
        <label>Name</label>
        <input type="text" id="newTagName" placeholder="e.g. workflow">
      </div>
      <div class="field">
        <label>Color</label>
        <div class="color-swatches" id="colorPicker">
          @foreach (['#00e5ff','#ffe066','#a78bfa','#34d399','#ff3d71','#fb923c','#f472b6','#60a5fa'] as $c)
            <div class="csw {{ $c==='#00e5ff'?'active':'' }}" style="background:{{ $c }}" data-color="{{ $c }}" onclick="selectColor(this)"></div>
          @endforeach
        </div>
      </div>
      <button class="btn-primary" onclick="createTag()">Create Tag</button>
    </div>
  </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const BASE = window.location.origin;
const DELETE_BASE = "{{ url('/file') }}";
let activeImage = null;
let selectedUploadTags = new Set();
let selectedDetailTags = new Set();
let allTagsData = @json($allTags);
let activeTagFilter = 0;
let selectedTagColor = '#00e5ff';

// PWA
let deferredInstall = null;
window.addEventListener('beforeinstallprompt', e => {
  e.preventDefault(); deferredInstall = e;
  document.getElementById('installBanner').classList.add('show');
});
document.getElementById('installBtn')?.addEventListener('click', async () => {
  if (!deferredInstall) return;
  deferredInstall.prompt();
  const {outcome} = await deferredInstall.userChoice;
  if (outcome==='accepted') document.getElementById('installBanner').classList.remove('show');
  deferredInstall = null;
});
if ('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js').catch(()=>{});

function openOverlay(id) { document.getElementById(id).classList.add('open'); }
function closeOverlay(id){ document.getElementById(id).classList.remove('open'); }
function overlayClick(e,id){ if(e.target===document.getElementById(id)) closeOverlay(id); }

document.getElementById('fileInput').addEventListener('change', function(){
  const file = this.files[0]; if (!file) return;
  document.getElementById('captureZone').classList.add('has-file');
  document.querySelector('.ctext strong').textContent = '✓ ' + file.name;
  if (file.type.startsWith('image/')) {
    const r = new FileReader();
    r.onload = e => { const p=document.getElementById('capture-preview'); p.src=e.target.result; p.style.display='block'; };
    r.readAsDataURL(file);
  } else {
    document.getElementById('capture-preview').style.display='none';
  }
});

function toggleUploadTag(btn){
  const id=parseInt(btn.dataset.id), color=btn.dataset.color;
  if(selectedUploadTags.has(id)){
    selectedUploadTags.delete(id); btn.classList.remove('selected');
    btn.style.color=btn.style.borderColor=btn.style.background='';
  } else {
    selectedUploadTags.add(id); btn.classList.add('selected');
    btn.style.color=color; btn.style.borderColor=color; btn.style.background=color+'22';
  }
  document.getElementById('selectedTagIds').value=[...selectedUploadTags].join(',');
}

document.getElementById('uploadForm').addEventListener('submit', function(){
  const b=document.getElementById('uploadBtn');
  b.disabled=true; b.innerHTML='<span class="spinner"></span>Uploading…';
});

function openDetail(card){
  const tags = JSON.parse(card.dataset.tags||'[]');
  const mime = card.dataset.mime || 'image/jpeg';
  const isImage = mime.startsWith('image/');
  const isText = mime === 'text/plain';
  const isDoc = mime.includes('wordprocessingml') || mime === 'application/msword';

  activeImage = {
    id: card.dataset.id, src: card.dataset.src, download: card.dataset.download,
    title: card.dataset.title||card.dataset.orig,
    notes: card.dataset.notes, tags, orig: card.dataset.orig, mime
  };

  document.getElementById('detailTitle').textContent = activeImage.title||activeImage.orig;

  document.getElementById('detailImg').style.display = 'none';
  document.getElementById('detailText').style.display = 'none';
  document.getElementById('detailDocIframe').style.display = 'none';

  if(isImage){
    document.getElementById('detailImg').src = activeImage.src;
    document.getElementById('detailImg').style.display = 'block';
  } else if(isText){
    document.getElementById('detailText').style.display = 'block';
    fetch(activeImage.src).then(r=>r.text()).then(txt=>
      document.getElementById('detailText').textContent = txt.substring(0, 10000)
    ).catch(()=> document.getElementById('detailText').textContent = '[Unable to load text file]');
  } else if(isDoc){
    document.getElementById('detailDocIframe').style.display = 'block';
    document.getElementById('detailDocIframe').src = `https://docs.google.com/gviz/viewer?url=${encodeURIComponent(activeImage.src)}&embedded=true`;
  }

  document.getElementById('detailMeta').textContent =
    new Date(card.dataset.date).toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'}) +
    ' · ' + (parseInt(card.dataset.size)>1048576 ? (card.dataset.size/1048576).toFixed(1)+' MB' : Math.round(card.dataset.size/1024)+' KB');
  document.getElementById('detailNotes').value = activeImage.notes;
  document.getElementById('deleteForm').action = DELETE_BASE + '/' + activeImage.id;
  const dl = document.getElementById('detailDownload');
  dl.href = activeImage.download; dl.download = activeImage.orig;

  document.getElementById('detailTagsRow').innerHTML = tags.map(t=>
    `<span class="tag-chip" style="color:${t.color};border-color:${t.color}44">${t.name}</span>`
  ).join('');

  selectedDetailTags = new Set(tags.map(t=>t.id));
  document.getElementById('detailTagSelect').innerHTML = allTagsData.map(t=>{
    const on = selectedDetailTags.has(t.id);
    return `<button type="button" class="tag-toggle${on?' selected':''}" data-id="${t.id}" data-color="${t.color}"
      style="${on?`color:${t.color};border-color:${t.color};background:${t.color}22`:''}"
      onclick="toggleDetailTag(this)">${t.name}</button>`;
  }).join('');

  document.getElementById('shareUrl').textContent = 'Not shared';
  document.getElementById('revokeBtn').style.display = 'none';
  document.getElementById('saveEditsBtn').textContent = 'Save Changes';
  openOverlay('detailOverlay');
}

function toggleDetailTag(btn){
  const id=parseInt(btn.dataset.id), color=btn.dataset.color;
  if(selectedDetailTags.has(id)){
    selectedDetailTags.delete(id); btn.classList.remove('selected');
    btn.style.color=btn.style.borderColor=btn.style.background='';
  } else {
    selectedDetailTags.add(id); btn.classList.add('selected');
    btn.style.color=color; btn.style.borderColor=color; btn.style.background=color+'22';
  }
}

async function saveImageEdits(){
  const btn=document.getElementById('saveEditsBtn');
  btn.disabled=true; btn.textContent='Saving…';
  const notes = document.getElementById('detailNotes').value;
  await api('update_image',{image_id:activeImage.id,title:activeImage.title,notes});
  await api('set_image_tags',{image_id:activeImage.id,tag_ids:JSON.stringify([...selectedDetailTags])});
  btn.disabled=false; btn.textContent='Saved ✓';
  setTimeout(()=>{ btn.textContent='Save Changes'; },1500);
  const card = document.querySelector(`.img-card[data-id="${activeImage.id}"]`);
  if(card){
    card.dataset.notes = notes;
    const selTags = allTagsData.filter(t=>selectedDetailTags.has(t.id));
    card.dataset.tags = JSON.stringify(selTags.map(t=>({id:t.id,name:t.name,color:t.color})));
    card.querySelector('.img-tags')?.remove();
    const body = card.querySelector('.img-body');
    if (selTags.length && body){
      body.insertAdjacentHTML('beforeend', `<div class="img-tags">` + selTags.map(t=>
        `<span class="tag-chip" style="color:${t.color};border-color:${t.color}44">${t.name}</span>`
      ).join('') + `</div>`);
    }
  }
}

async function genShareLink(){
  const res = await api('get_share_token',{image_id:activeImage.id});
  if(res.ok){
    const url=`${BASE}/s/${res.token}`;
    document.getElementById('shareUrl').textContent=url;
    document.getElementById('revokeBtn').style.display='';
    if(navigator.share){ navigator.share({title:activeImage.title,url}).catch(()=>{}); }
    else { navigator.clipboard?.writeText(url).then(()=>{ document.getElementById('shareUrl').textContent='✓ Copied: '+url; }); }
  }
}

async function revokeShare(){
  if(!confirm('Revoke this share link?')) return;
  const res = await api('revoke_share',{image_id:activeImage.id});
  if(res.ok){ document.getElementById('shareUrl').textContent='Not shared'; document.getElementById('revokeBtn').style.display='none'; }
}

function selectColor(el){
  document.querySelectorAll('.csw').forEach(e=>e.classList.remove('active'));
  el.classList.add('active'); selectedTagColor=el.dataset.color;
}
async function createTag(){
  const name=document.getElementById('newTagName').value.trim();
  if(!name) return;
  const res=await api('create_tag',{name,color:selectedTagColor});
  if(res.ok){
    allTagsData.push({id:res.id,name:res.name,color:res.color});
    document.getElementById('tagsList').insertAdjacentHTML('beforeend',
      `<div class="tag-mgmt-row" id="tagRow_${res.id}">
        <div class="tname"><span class="swatch" style="background:${res.color}"></span>
        <span style="color:${res.color}">${res.name}</span></div>
        <button class="btn-tdel" onclick="deleteTag(${res.id})">✕</button></div>`);
    document.getElementById('tagFilters')?.insertAdjacentHTML('beforeend',
      `<button class="tag-pill" data-tag-id="${res.id}" style="color:${res.color}">${res.name}</button>`);
    document.getElementById('uploadTagSelect').insertAdjacentHTML('beforeend',
      `<button type="button" class="tag-toggle" data-id="${res.id}" data-color="${res.color}"
        onclick="toggleUploadTag(this)">${res.name}</button>`);
    document.getElementById('newTagName').value='';
  } else { alert(res.error||'Failed'); }
}
async function deleteTag(id){
  if(!confirm('Delete this tag?')) return;
  const res=await api('delete_tag',{tag_id:id});
  if(res.ok){
    document.getElementById('tagRow_'+id)?.remove();
    document.querySelector(`[data-tag-id="${id}"]`)?.remove();
    allTagsData=allTagsData.filter(t=>t.id!==id);
  }
}

document.getElementById('tagFilters')?.addEventListener('click', e=>{
  const btn=e.target.closest('.tag-pill'); if(!btn) return;
  document.querySelectorAll('.tag-pill').forEach(p=>p.classList.remove('active'));
  btn.classList.add('active');
  activeTagFilter=parseInt(btn.dataset.tagId);
  applyFilters();
});
document.getElementById('searchInput').addEventListener('input', applyFilters);
function applyFilters(){
  const q=document.getElementById('searchInput').value.toLowerCase().trim();
  document.querySelectorAll('.img-card').forEach(card=>{
    const title=(card.dataset.title||card.dataset.orig||'').toLowerCase();
    const notes=(card.dataset.notes||'').toLowerCase();
    const tags=JSON.parse(card.dataset.tags||'[]');
    const matchSearch=!q||title.includes(q)||notes.includes(q)||tags.some(t=>t.name.toLowerCase().includes(q));
    const matchTag=activeTagFilter===0||tags.some(t=>t.id===activeTagFilter);
    card.style.display=matchSearch&&matchTag?'':'none';
  });
}

async function api(action,data){
  const form=new FormData();
  Object.entries(data).forEach(([k,v])=>form.append(k,v));
  form.append('action',action);
  try {
    const r=await fetch('{{ route('api') }}',{
      method:'POST', body:form,
      headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}
    });
    return await r.json();
  } catch { return {ok:false,error:'Network error'}; }
}
</script>
</body>
</html>
