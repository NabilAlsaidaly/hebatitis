<div class="sidebar">
    <ul class="nav flex-column text-end">
        <li class="nav-item mb-3">
            <a class="nav-link d-flex justify-content-between align-items-center" href="{{ route('patient.dashboard') }}">
                <span>لوحة التحكم</span>
                <i class="bi bi-house-door"></i>
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link d-flex justify-content-between align-items-center" href="{{ route('patient.info') }}">
                <span>معلوماتي</span>
                <i class="bi bi-person-circle"></i>
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link d-flex justify-content-between align-items-center" href="{{ route('patient.records') }}">
                <span>التحاليل</span>
                <i class="bi bi-clipboard2-pulse"></i>
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link d-flex justify-content-between align-items-center" href="{{ route('patient.reports') }}">
                <span>التقارير</span>
                <i class="bi bi-file-earmark-text"></i>
            </a>
        </li>
        <li class="nav-item mb-3">
            <a class="nav-link d-flex justify-content-between align-items-center" href="{{ route('patient.chart') }}">
                <span>المخطط البياني</span>
                <i class="bi bi-graph-up-arrow"></i>
            </a>
        </li>
    </ul>
</div>
