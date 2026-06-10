<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ config('vault.title') }} — Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Fira+Code:wght@300;400&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --bg:       #080b10;
    --surface:  #0e1420;
    --border:   #1e2a3a;
    --accent:   #00e5ff;
    --accent2:  #ff3d71;
    --text:     #c8d8e8;
    --muted:    #4a5a6a;
    --font-display: 'Syne', sans-serif;
    --font-mono:    'Fira Code', monospace;
  }

  body {
    background: var(--bg);
    color: var(--text);
    font-family: var(--font-mono);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
      linear-gradient(rgba(0,229,255,0.03) 1px, transparent 1px),
      linear-gradient(90deg, rgba(0,229,255,0.03) 1px, transparent 1px);
    background-size: 40px 40px;
    animation: gridMove 20s linear infinite;
    pointer-events: none;
  }

  @keyframes gridMove {
    0%   { background-position: 0 0; }
    100% { background-position: 40px 40px; }
  }

  body::after {
    content: '';
    position: fixed;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(0,229,255,0.06) 0%, transparent 70%);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    pointer-events: none;
    animation: pulse 4s ease-in-out infinite;
  }

  @keyframes pulse {
    0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
    50%       { opacity: 1;   transform: translate(-50%, -50%) scale(1.1); }
  }

  .login-wrap {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 420px;
    padding: 1rem;
    animation: fadeUp 0.6s ease both;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .login-header { text-align: center; margin-bottom: 2.5rem; }
  .login-header .glyph {
    font-size: 2.5rem; display: block; margin-bottom: 0.75rem;
    filter: drop-shadow(0 0 12px var(--accent));
  }
  .login-header h1 {
    font-family: var(--font-display); font-size: 1.8rem; font-weight: 800;
    letter-spacing: -0.02em; color: #fff;
  }
  .login-header h1 span { color: var(--accent); }
  .login-header p {
    font-size: 0.75rem; color: var(--muted); margin-top: 0.4rem;
    letter-spacing: 0.1em; text-transform: uppercase;
  }

  .card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 12px; padding: 2rem;
    box-shadow: 0 0 40px rgba(0,0,0,0.5), 0 0 0 1px rgba(0,229,255,0.05);
  }

  .field { margin-bottom: 1.25rem; }
  .field label {
    display: block; font-size: 0.7rem; letter-spacing: 0.12em;
    text-transform: uppercase; color: var(--muted); margin-bottom: 0.5rem;
  }
  .field input {
    width: 100%; background: var(--bg); border: 1px solid var(--border);
    border-radius: 6px; color: var(--text); font-family: var(--font-mono);
    font-size: 0.9rem; padding: 0.75rem 1rem; outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .field input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0,229,255,0.1); }

  .btn-login {
    width: 100%; background: var(--accent); color: #000; border: none;
    border-radius: 6px; font-family: var(--font-display); font-weight: 700;
    font-size: 0.9rem; letter-spacing: 0.08em; text-transform: uppercase;
    padding: 0.85rem; cursor: pointer; margin-top: 0.5rem;
    transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
  }
  .btn-login:hover { background: #33eaff; box-shadow: 0 0 20px rgba(0,229,255,0.4); }
  .btn-login:active { transform: scale(0.98); }

  .error {
    background: rgba(255,61,113,0.1); border: 1px solid rgba(255,61,113,0.3);
    border-radius: 6px; color: var(--accent2); font-size: 0.8rem;
    padding: 0.6rem 0.9rem; margin-bottom: 1.25rem; letter-spacing: 0.03em;
  }

  .footer-note {
    text-align: center; font-size: 0.65rem; color: var(--muted);
    margin-top: 1.5rem; letter-spacing: 0.08em;
  }
</style>
</head>
<body>
<div class="login-wrap">
  <div class="login-header">
    <span class="glyph">⬡</span>
    <h1>Alea <span>Lab</span> Vault</h1>
    <p>Private media storage</p>
  </div>
  <div class="card">
    @if ($errors->any())
      <div class="error">⚠ {{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('login.submit') }}" autocomplete="off">
      @csrf
      <div class="field">
        <label>Username</label>
        <input type="text" name="username" required autofocus value="{{ old('username') }}">
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>
      <button type="submit" class="btn-login">Access Vault →</button>
    </form>
  </div>
  <p class="footer-note">alea artificium · lab.alfredoalea.com</p>
</div>
</body>
</html>
