<div class="card shadow mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">🧪 المعالجة المسبقة للبيانات</h5>
    </div>
    <div class="card-body">

        {{-- اختيار المريض --}}
        <div class="mb-3">
            <label for="pre_patient_id" class="form-label">اختر المريض</label>
            <select class="form-select" id="pre_patient_id">
                {{-- تعبئة من JavaScript --}}
            </select>
        </div>

        {{-- عرض التحاليل الأخيرة --}}
        <div id="preprocessingContent" class="d-none">
            <h6 class="mb-3">🩺 آخر التحاليل المدخلة:</h6>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>التحليل</th>
                        <th>القيمة</th>
                        <th>الملاحظات</th>
                    </tr>
                </thead>
                <tbody id="preTableBody">
                    {{-- تعبئة من JavaScript --}}
                </tbody>
            </table>

            <div id="preWarnings" class="alert alert-warning d-none">
                ⚠️ يوجد تحاليل تحتوي على قيم غير منطقية أو ناقصة
            </div>

            <div class="text-end">
                <button class="btn btn-outline-primary" id="sendToAI">📡 إرسال إلى الذكاء الاصطناعي</button>
            </div>
        </div>

        {{-- رسالة لا يوجد تحاليل --}}
        <div id="noRecordsMessage" class="alert alert-secondary d-none">
            لا توجد تحاليل محفوظة لهذا المريض حتى الآن.
        </div>

    </div>
</div>
