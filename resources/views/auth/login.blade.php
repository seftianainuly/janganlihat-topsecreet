<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — GadgetHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { min-height:100vh; margin:0; display:grid; place-items:center; padding:1.25rem; background:#f6f7fb; color:#172033; font-family:'DM Sans',sans-serif; }
        .login-card { width:100%; max-width:430px; border:1px solid #e7eaf0; border-radius:1rem; background:#fff; padding:2rem; box-shadow:0 12px 36px rgb(16 24 40 / 7%); }
        .login-input { width:100%; border:1px solid #d0d5dd; border-radius:.65rem; padding:.75rem .9rem; color:#172033; outline:none; }
        .login-input:focus { border-color:#6258dd; box-shadow:0 0 0 3px rgb(80 72 216 / 12%); }
        .login-button { width:100%; border:0; border-radius:.65rem; background:#5146c8; padding:.8rem 1rem; color:#fff; font-weight:700; }
        .login-button:hover { background:#3931bc; }
        [x-cloak] { display:none !important; }
    </style>
</head>
<body>
    <main class="login-card" x-data="{ showPass: false }">
        <a href="{{ route('home') }}" class="text-xl font-extrabold tracking-tight" style="color:#5146c8">GadgetHub</a>
        <h1 class="mt-8 text-2xl font-bold tracking-tight">Masuk ke dashboard</h1>
        <p class="mt-2 text-sm text-slate-500">Kelola artikel dan konten GadgetHub.</p>

        @if(session('success'))
            <div role="status" class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="mt-7 space-y-5">
            @csrf
            <div>
                <label for="email" class="mb-2 block text-sm font-semibold">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus
                       placeholder="admin@gadgethub.com" class="login-input">
                @error('email')
                    <p role="alert" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-semibold">Password</label>
                <div class="relative">
                    <input id="password" name="password" :type="showPass ? 'text' : 'password'" autocomplete="current-password" required
                           placeholder="Masukkan password" class="login-input pr-20">
                    <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-3 text-sm font-medium text-indigo-700"
                            x-text="showPass ? 'Sembunyikan' : 'Lihat'"></button>
                </div>
                @error('password')
                    <p role="alert" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-indigo-600" {{ old('remember') ? 'checked' : '' }}>
                Ingat saya
            </label>

            <button type="submit" class="login-button">Masuk</button>
        </form>

        <a href="{{ route('home') }}" class="mt-6 block text-center text-sm font-medium text-slate-500 hover:text-indigo-700">Kembali ke website</a>
    </main>
</body>
</html>
