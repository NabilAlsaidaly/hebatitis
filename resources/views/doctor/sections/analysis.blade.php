<div class="card shadow mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">🧪 تحليل بيانات مريض</h5>
    </div>
    <div class="card-body">
        <form id="analysisForm">
            {{-- اختيار المريض --}}
            <div class="mb-3">
                <label for="patient_id" class="form-label">اختر المريض</label>
                <select class="form-select" id="patient_id" name="patient_id" required>
                    {{-- سيتم تعبئتها من JS --}}
                </select>
            </div>

            {{-- مدخلات التحليل --}}
            <div class="row">
                @foreach(['ALT', 'AST', 'ALP', 'BIL', 'CHE', 'ALB', 'CHOL', 'CREA', 'GGT', 'PROT'] as $field)
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ $field }}</label>
                        <input type="number" step="any" class="form-control" name="{{ $field }}" required>
                    </div>
                @endforeach
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">🔍 تحليل البيانات</button>
            </div>
        </form>
    </div>
</div>

{{-- النتيجة --}}
<div id="analysisResult" class="mt-3"></div>
