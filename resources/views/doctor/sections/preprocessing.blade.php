<div class="card shadow mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">🧪 المعالجة المسبقة للبيانات</h5>
    </div>
    <div class="card-body">

        {{-- اختيار المريض --}}
        <div class="mb-3">
            <label for="pre_patient_id" class="form-label">اختر المريض</label>
            <select class="form-select" id="pre_patient_id">
                <option value="">-- اختر مريضًا --</option>
            </select>
        </div>

        {{-- نتائج التحاليل --}}
        <div id="preprocessingContent" class="d-none">
            <h6 class="mb-3">🩺 آخر التحاليل المسجلة:</h6>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>التحليل</th>
                        <th>القيمة</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody id="preTableBody">
                    {{-- البيانات من JavaScript --}}
                </tbody>
            </table>

            <div id="preWarnings" class="alert alert-warning d-none">
                ⚠️ هناك قيم غير منطقية أو ناقصة يُرجى مراجعتها قبل الإرسال.
            </div>

            <div class="text-end">
                <button class="btn btn-outline-primary" id="sendToAI">📡 إرسال إلى الذكاء الاصطناعي</button>
            </div>
            <div id="aiResultBox" class="alert alert-info mt-4 d-none">
                <h6 class="mb-2">🧠 نتائج التحليل بالذكاء الاصطناعي:</h6>
                <p id="aiDiagnosis">🔍 التشخيص: <strong>...</strong></p>
                <p id="aiTreatment">💊 العلاج المقترح: <strong>...</strong></p>
            </div>

        </div>

        {{-- إن لم توجد تحاليل --}}
        <div id="noRecordsMessage" class="alert alert-secondary d-none">
            لا توجد تحاليل محفوظة لهذا المريض حتى الآن.
        </div>

    </div>
</div>
