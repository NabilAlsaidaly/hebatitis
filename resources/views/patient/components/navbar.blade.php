<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <span class="navbar-brand ms-auto">👨‍⚕️ نظام طبي ذكي - المريض</span>

        <form method="POST" action="{{ route('patient.logout') }}" class="me-auto">
            @csrf
            <button class="btn btn-outline-light btn-sm" type="submit">🚪 تسجيل الخروج</button>
        </form>
    </div>
</nav>
