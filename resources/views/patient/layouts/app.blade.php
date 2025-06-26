<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المريض | نظام طبي ذكي</title>

    {{-- ✅ Bootstrap RTL --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    {{-- ✅ تنسيق مخصص --}}
    <style>
        body {
            background-color: #f9f9f9;
            padding-top: 56px;
        }

        .main-wrapper {
            display: flex;
            flex-direction: row-reverse;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background-color: #ffffff;
            border-left: 1px solid #ddd;
            padding: 1rem;
            height: calc(100vh - 56px);
            position: sticky;
            top: 56px;
            overflow-y: auto;
        }

        .content-area {
            flex-grow: 1;
            padding: 2rem;
        }
    </style>

    @yield('styles')
</head>
<body>

    {{-- ✅ شريط علوي --}}
    @include('patient.components.navbar')

    {{-- ✅ منطقة المحتوى الكاملة --}}
    <div class="main-wrapper">
        {{-- ✅ الشريط الجانبي --}}
        @include('patient.components.sidebar')

        {{-- ✅ المنطقة الأساسية لكل صفحة --}}
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    {{-- ✅ Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
