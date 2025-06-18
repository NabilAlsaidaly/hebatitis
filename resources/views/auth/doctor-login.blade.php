<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل دخول الطبيب</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <form method="POST" action="{{ route('doctor.login') }}" class="bg-white p-4 shadow rounded w-100" style="max-width: 400px;">
        @csrf
        <h4 class="mb-4 text-center">🩺 تسجيل دخول الطبيب</h4>

        <div class="mb-3">
            <label for="email" class="form-label">📧 البريد الإلكتروني</label>
            <input type="email" name="email" id="email" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">🔒 كلمة المرور</label>
            <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <button class="btn btn-primary w-100">دخول</button>
    </form>
</body>
</html>
