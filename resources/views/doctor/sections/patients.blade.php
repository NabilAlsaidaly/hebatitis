<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">👨‍⚕️ إدارة المرضى</h5>
        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addPatientModal">➕ إضافة مريض</button>
    </div>

    <div class="card-body">
        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>الاسم الكامل</th>
                    <th>تاريخ الميلاد</th>
                    <th>معلومات التواصل</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="patientTableBody">
                {{-- يتم تعبئة المرضى هنا من خلال JS لاحقًا --}}
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: إضافة مريض -->
<div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addPatientForm" class="modal-content">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-header">
                <h5 class="modal-title">➕ إضافة مريض جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">الاسم الكامل</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">تاريخ الميلاد</label>
                    <input type="date" name="dob" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">معلومات الاتصال</label>
                    <input type="text" name="contact_info" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني للمريض</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">كلمة المرور للمريض</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">💾 حفظ</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </form>

    </div>
</div>
