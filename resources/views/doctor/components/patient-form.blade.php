<form id="patientForm">
    <div class="row">
        @foreach (['Age', 'Sex', 'ALB', 'ALP', 'ALT', 'AST', 'BIL', 'CHE', 'CHOL', 'CREA', 'GGT', 'PROT'] as $field)
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">{{ $field }}</label>
                <input
                    type="number"
                    step="any"
                    class="form-control"
                    name="{{ $field }}"
                    placeholder="أدخل قيمة {{ $field }}"
                    required
                >
            </div>
        @endforeach
    </div>
    <button type="submit" class="btn btn-success w-100">🔍 تحليل البيانات</button>
</form>
