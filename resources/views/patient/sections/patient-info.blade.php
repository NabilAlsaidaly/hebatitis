@extends('patient.layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">🧍‍♂️ بياناتي الشخصية</h4>

    @if($patient)
        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">👤 الاسم الكامل:</dt>
                    <dd class="col-sm-9">{{ $patient->Name }}</dd>

                    <dt class="col-sm-3">📧 البريد الإلكتروني:</dt>
                    <dd class="col-sm-9">{{ Auth::user()->Email }}</dd>

                    <dt class="col-sm-3">🎂 تاريخ الميلاد:</dt>
                    <dd class="col-sm-9">{{ $patient->Date_Of_Birth }}</dd>

                    <dt class="col-sm-3">📞 رقم التواصل:</dt>
                    <dd class="col-sm-9">{{ $patient->Contact_Info }}</dd>
                </dl>
            </div>
        </div>
    @else
        <div class="alert alert-danger mt-4">
            لم يتم العثور على بيانات المريض.
        </div>
    @endif
</div>
@endsection
