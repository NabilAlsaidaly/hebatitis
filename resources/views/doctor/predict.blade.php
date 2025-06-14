@extends('doctor.layouts.app')

@section('title', 'نموذج التنبؤ بالحالة ')
@section('page-title', 'نموذج التنبؤ بالحالة والعلاج')

@section('content')

    @include('doctor.components.patient-form')

    <hr>

    <div id="result" class="mt-4"></div>

@endsection
