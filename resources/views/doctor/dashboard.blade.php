@extends('doctor.layouts.app')

@section('title', 'لوحة التحكم')
@section('content')

    {{-- لوحة الترحيب --}}
    <div id="section-dashboard" class="section">
        <h4>مرحبًا بك في لوحة الطبيب 👨‍⚕️</h4>
        <p class="text-muted">اختر أحد الأقسام من القائمة الجانبية لبدء العمل.</p>
    </div>

    {{-- قسم إدارة المرضى --}}
    <div id="section-patients" class="section d-none">
        @include('doctor.sections.patients')
    </div>

    {{-- قسم تطور المرض --}}
    <div id="section-lstm" class="section d-none">
        @include('doctor.sections.lstm')
    </div>


    {{-- قسم التحليل --}}
    <div id="section-analysis" class="section d-none">
        @include('doctor.sections.analysis')
    </div>


    {{-- قسم المعالجة المسبقة --}}
    <div id="section-preprocessing" class="section d-none">
        @include('doctor.sections.preprocessing')
    </div>

    {{-- قسم التقارير --}}
    <div id="section-reports" class="section d-none">
        @include('doctor.sections.reports')
    </div>

    {{-- قسم الإحصاءات --}}
    <div id="section-stats" class="section d-none">
        @include('doctor.sections.stats')
    </div>

@endsection
