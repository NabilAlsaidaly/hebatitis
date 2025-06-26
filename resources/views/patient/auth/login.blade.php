@extends('patient.layouts.guest')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="mb-4 text-center">🔐 تسجيل دخول المريض</h3>

    <form method="POST" action="{{ route('patient.login.submit') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">📧 البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" placeholder="example@email.com" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">🔑 كلمة المرور</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">➡️ دخول</button>
        </div>
    </form>
</div>
@endsection
