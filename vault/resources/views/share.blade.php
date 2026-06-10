<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $file ? ($file->title ?: $file->original_name) . ' — Alea Lab Vault' : 'Not Found — Alea Lab Vault' }}</title>
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#00e5ff">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Fira+Code:wght@300;400&display=swap" rel="stylesheet">
<style>
@verbatim
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --bg: #080b10; --surface: #0e1420; --border: #1e2a3a;
    --accent: #00e5ff; --accent2: #ff3d71; --text: #c8d8e8; --muted: #4a5a6a;
    --font-display: 'Syne', sans-serif; --font-mono: 'Fira Code', monospace;
  }
  body {
    background: var(--bg); color: var(--text); font-family: var(--font-mono);
    min-height: 100vh; display: flex; flex-direction: column; align-items: center;
  }
  body::before {
    content: ''; position: fixed; inset: 0;
    background-image: linear-gradient(rgba(0,229,255,0.02) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(0,229,255,0.02) 1px, transparent 1px);
    background-size: 40px 40px; pointer-events: none; z-index: 0;
  }
  .wrap { position: relative; z-index: 1; width: 100%; max-width: 680px; padding: 2rem 1.25rem 4rem; }
  .topbar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);
  }
  .logo { font-family: var(--font-display); font-weight: 800; color: #fff; font-size: 1.1rem; }
  .logo span { color: var(--accent); }
  .badge {
    font-size: 0.65rem; letter-spacing: 0.1em; text-transform: uppercase;
    color: var(--muted); border: 1px solid var(--border); border-radius: 4px; padding: 0.25rem 0.6rem;
  }
  .img-hero {
    width: 100%; border-radius: 12px; display: block;
    border: 1px solid var(--border); box-shadow: 0 0 40px rgba(0,0,0,0.5);
    margin-bottom: 1.25rem; animation: fadeUp 0.5s ease both;
  }
  .doc-hero {
    width: 100%; aspect-ratio: 16/9; border-radius: 12px; border: 1px solid var(--border);
    background: var(--surface); display: flex; align-items: center; justify-content: center;
    flex-direction: column; gap: .75rem; margin-bottom: 1.25rem; color: var(--muted);
    font-family: var(--font-display); font-weight: 700; letter-spacing: .06em;
    animation: fadeUp 0.5s ease both;
  }
  .doc-hero .doc-icon { font-size: 3.5rem; }
  @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
  .img-title {
    font-family: var(--font-display); font-size: 1.4rem; font-weight: 800;
    color: #fff; margin-bottom: 0.5rem; animation: fadeUp 0.5s ease 0.1s both;
  }
  .img-meta {
    font-size: 0.7rem; color: var(--muted); margin-bottom: 1rem;
    animation: fadeUp 0.5s ease 0.15s both;
  }
  .tags {
    display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 1rem;
    animation: fadeUp 0.5s ease 0.2s both;
  }
  .tag {
    font-size: 0.65rem; letter-spacing: 0.08em; text-transform: uppercase;
    padding: 0.25rem 0.6rem; border-radius: 999px; border: 1px solid;
    font-family: var(--font-mono);
  }
  .notes {
    background: var(--surface); border: 1px solid var(--border); border-radius: 8px;
    padding: 1rem; font-size: 0.8rem; color: var(--text); line-height: 1.6;
    margin-bottom: 1.5rem; animation: fadeUp 0.5s ease 0.25s both;
  }
  .btn-dl {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: var(--accent); color: #000; border: none; border-radius: 8px;
    font-family: var(--font-display); font-weight: 700; font-size: 0.85rem;
    letter-spacing: 0.06em; text-transform: uppercase; padding: 0.85rem 1.75rem;
    cursor: pointer; text-decoration: none; animation: fadeUp 0.5s ease 0.3s both;
    transition: background 0.2s, box-shadow 0.2s;
  }
  .btn-dl:hover { background: #33eaff; box-shadow: 0 0 20px rgba(0,229,255,0.3); }
  .not-found { text-align: center; padding: 5rem 1rem; animation: fadeUp 0.5s ease both; }
  .not-found .icon { font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.3; }
  .not-found h2 { font-family: var(--font-display); color: #fff; margin-bottom: 0.5rem; }
  .not-found p { font-size: 0.8rem; color: var(--muted); }
@endverbatim
</style>
</head>
<body>
<div class="wrap">
  <div class="topbar">
    <div class="logo">Alea <span>Lab</span> Vault</div>
    <span class="badge">Shared File</span>
  </div>

  @if ($file)
    @if (str_starts_with($file->mime_type, 'image/'))
      <img class="img-hero" src="{{ route('files.show', $file->filename) }}"
           alt="{{ $file->original_name }}">
    @else
      <div class="doc-hero">
        <span class="doc-icon">📄</span>
        <span>{{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }} file</span>
      </div>
    @endif

    <div class="img-title">{{ $file->title ?: $file->original_name }}</div>
    <div class="img-meta">
      {{ $file->uploaded_at->format('F j, Y') }} ·
      {{ round($file->file_size / 1024) }} KB ·
      {{ $file->mime_type }}
    </div>

    @if ($file->tags->count())
      <div class="tags">
        @foreach ($file->tags as $tag)
          <span class="tag" style="color:{{ $tag->color }};border-color:{{ $tag->color }}33">
            {{ $tag->name }}
          </span>
        @endforeach
      </div>
    @endif

    @if ($file->notes)
      <div class="notes">{!! nl2br(e($file->notes)) !!}</div>
    @endif

    <a href="{{ route('files.download', $file->filename) }}" class="btn-dl">↓ Download</a>
  @else
    <div class="not-found">
      <span class="icon">⬡</span>
      <h2>File not found</h2>
      <p>This share link may have expired or been revoked.</p>
    </div>
  @endif
</div>
</body>
</html>
