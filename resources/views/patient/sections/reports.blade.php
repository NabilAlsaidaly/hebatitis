@extends('patient.layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-4">📄 تقارير المريض</h4>

        @if ($reports->isEmpty())
            <div class="alert alert-info">لا توجد تقارير متاحة حاليًا.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>📅 التاريخ</th>
                            <th>الاسم</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ basename($report->file_path) }}</td>
                                <td>
                                    <a href="{{ asset('storage/reports/' . basename($report->file_path)) }}"
                                        class="btn btn-sm btn-outline-success" download>⬇️ تحميل</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
