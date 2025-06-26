@extends('patient.layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-4">📋 تحاليل المريض</h4>

        @if ($records->isEmpty())
            <div class="alert alert-info">لا توجد تحاليل حتى الآن.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle" dir="ltr">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>ALB</th>
                            <th>ALP</th>
                            <th>ALT</th>
                            <th>AST</th>
                            <th>BIL</th>
                            <th>CHE</th>
                            <th>CHOL</th>
                            <th>CREA</th>
                            <th>GGT</th>
                            <th>PROT</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr>
                                <td>{{ $record->created_at->format('Y-m-d') }}</td>
                                <td>{{ $record->ALB }}</td>
                                <td>{{ $record->ALP }}</td>
                                <td>{{ $record->ALT }}</td>
                                <td>{{ $record->AST }}</td>
                                <td>{{ $record->BIL }}</td>
                                <td>{{ $record->CHE }}</td>
                                <td>{{ $record->CHOL }}</td>
                                <td>{{ $record->CREA }}</td>
                                <td>{{ $record->GGT }}</td>
                                <td>{{ $record->PROT }}</td>
                                <td>{{ $record->prediction->result ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
