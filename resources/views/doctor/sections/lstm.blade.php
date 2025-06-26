<div class="container py-3">
    <h4 class="mb-3">📈 توقع تطور المرض خلال 6 أشهر باستخدام LSTM</h4>

    {{-- اختيار المريض --}}
    <div class="mb-3">
        <label for="lstm_patient_id" class="form-label">👨‍⚕️ اختر المريض:</label>
        <select class="form-select" name="patient_id" id="lstm_patient_id" required>
            <option value="">-- تحميل المرضى... --</option>
            {{-- يتم ملؤها ديناميكياً عبر JS --}}
        </select>
    </div>

    {{-- نموذج إدخال القيم الشهرية --}}
    <form id="lstmForm">
        <div class="row">
            @for ($i = 1; $i <= 6; $i++)
                <div class="col-md-4 mb-3">
                    <label for="alt_{{ $i }}">ALT شهر {{ $i }}</label>
                    <input type="number" step="any" class="form-control" id="alt_{{ $i }}" name="ALT[]" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="ast_{{ $i }}">AST شهر {{ $i }}</label>
                    <input type="number" step="any" class="form-control" id="ast_{{ $i }}" name="AST[]" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="bil_{{ $i }}">BIL شهر {{ $i }}</label>
                    <input type="number" step="any" class="form-control" id="bil_{{ $i }}" name="BIL[]" required>
                </div>
            @endfor
        </div>

        <button type="submit" class="btn btn-success">🔍 تحليل تطور المرض</button>
    </form>

    {{-- عرض النتيجة --}}
    <div id="lstmResult" class="mt-4">
        {{-- النتيجة والنسبة تظهر هنا --}}
        {{-- الرسم البياني --}}
        <canvas id="lstmChart" height="160" class="mt-4"></canvas>
    </div>
</div>
{{-- تضمين مكتبة Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


