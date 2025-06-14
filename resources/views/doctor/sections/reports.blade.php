<div class="card shadow">
    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📄 إدارة التقارير الطبية</h5>
        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#uploadReportModal">➕ رفع تقرير</button>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <label for="report_patient_id" class="form-label">اختر المريض</label>
            <select class="form-select" id="report_patient_id" name="patient_id">
                {{-- تعبئة من JS --}}
            </select>
        </div>

        <table class="table table-striped table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>اسم الملف</th>
                    <th>تاريخ الرفع</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="reportTableBody">
                {{-- التقارير سيتم عرضها هنا من خلال JS --}}
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: رفع تقرير -->
<div class="modal fade" id="uploadReportModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="uploadReportForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">➕ رفع تقرير جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="report_patient_select" class="form-label">اختر المريض</label>
                    <select class="form-select" id="report_patient_select" name="patient_id" required>
                        {{-- تعبئة من JS --}}
                    </select>
                </div>
                <div class="mb-3">
                    <label for="report_file" class="form-label">اختر ملف التقرير</label>
                    <input type="file" class="form-control" id="report_file" name="file" accept=".pdf,.jpg,.png,.jpeg" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">📤 رفع</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </form>
    </div>
</div>
