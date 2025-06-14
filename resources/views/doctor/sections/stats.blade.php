<div class="card shadow mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">📈 إحصاءات عامة</h5>
    </div>
    <div class="card-body">
        {{-- مؤشرات عامة --}}
        <div class="row text-center mb-4">
            <div class="col-md-3">
                <div class="alert alert-primary">
                    👥 عدد المرضى<br>
                    <strong id="statPatients">0</strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-success">
                    🧪 عدد التحاليل<br>
                    <strong id="statRecords">0</strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-warning">
                    📄 عدد التقارير<br>
                    <strong id="statReports">0</strong>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-danger">
                    🧠 تحليلات ذكاء صناعي<br>
                    <strong id="statAI">0</strong>
                </div>
            </div>
        </div>

        {{-- رسم بياني: توزيع التشخيص --}}
        <div class="card mt-4">
            <div class="card-header bg-light">
                <strong>🩻 توزيع نتائج التشخيص</strong>
            </div>
            <div class="card-body">
                <canvas id="diagnosisChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>
