<div class="card shadow">
    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📄 إدارة التقارير الطبية</h5>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-8">
                <label for="report_patient_select" class="form-label">اختر المريض</label>
                <select class="form-select" id="report_patient_select" name="patient_id">
                    {{-- تعبئة من JS --}}
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button id="generateReportBtn" class="btn btn-primary w-100">
                    📄 إنشاء تقرير PDF
                </button>
            </div>
        </div>

        <table class="table table-striped table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>المسار</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="reportTableBody">
                {{-- يتم ملؤه من JavaScript --}}
            </tbody>
        </table>
    </div>
</div>
