document.addEventListener("DOMContentLoaded", function () {
    // 🔹 أقسام الصفحة
    const navItems = document.querySelectorAll(".list-group-item-action");
    const sections = document.querySelectorAll(".section");

    navItems.forEach((item) => {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            navItems.forEach((i) => i.classList.remove("active"));
            this.classList.add("active");

            const targetId = this.id.replace("nav", "section");
            sections.forEach((s) => s.classList.add("d-none"));
            const target = document.getElementById(targetId);
            if (target) target.classList.remove("d-none");

            // تحميل حسب القسم
            if (this.id === "nav-patients") fetchPatients();
            if (this.id === "nav-analysis") populatePatientDropdown();
            if (this.id === "nav-reports") initReports();
            if (this.id === "nav-preprocessing") initPreprocessing();
            if (this.id === "nav-stats") loadStats();
        });
    });

    // -----------------------------------------------------------
    // 🧾 المرضى
    const addPatientForm = document.getElementById("addPatientForm");
    const patientTableBody = document.getElementById("patientTableBody");

    if (addPatientForm) {
        addPatientForm.addEventListener("submit", async function (e) {
            e.preventDefault();
            const formData = new FormData(addPatientForm);
            const newPatient = {
                name: formData.get("name"),
                dob: formData.get("dob"),
                contact_info: formData.get("contact_info"),
            };

            try {
                const res = await fetch("/api/patients", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify(newPatient),
                });
                await res.json();
                addPatientForm.reset();
                bootstrap.Modal.getInstance(
                    document.getElementById("addPatientModal")
                ).hide();
                fetchPatients();
            } catch (err) {
                console.error("❌ فشل في إضافة المريض:", err);
            }
        });
    }

    async function fetchPatients() {
        try {
            const res = await fetch("/api/patients");
            const data = await res.json();
            patientTableBody.innerHTML = "";
            data.forEach((p, i) => {
                patientTableBody.innerHTML += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${p.Name}</td>
                        <td>${p.Date_Of_Birth || "—"}</td>
                        <td>${p.Contact_Info || "—"}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary">✏️ تعديل</button>
                            <button class="btn btn-sm btn-outline-danger">🗑️ حذف</button>
                        </td>
                    </tr>`;
            });
        } catch (err) {
            console.error("❌ فشل تحميل المرضى:", err);
        }
    }

    // -----------------------------------------------------------
    // 🧪 التحليل
    const form = document.getElementById("analysisForm");
    const resultDiv = document.getElementById("analysisResult");
    const patientSelect = document.getElementById("patient_id");

    async function populatePatientDropdown() {
        if (!patientSelect) return;
        try {
            const res = await fetch("/api/patients");
            const data = await res.json();
            patientSelect.innerHTML =
                '<option value="">-- اختر مريضاً --</option>';
            data.forEach((p) => {
                patientSelect.innerHTML += `<option value="${p.id}">${p.Name}</option>`;
            });
        } catch (error) {
            console.error("❌ فشل تحميل قائمة المرضى:", error);
        }
    }

    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();
            resultDiv.innerHTML = `<div class="alert alert-secondary">📡 جاري إرسال البيانات...</div>`;
            const formData = new FormData(form);
            const jsonData = {};
            formData.forEach(
                (value, key) => (jsonData[key] = parseFloat(value))
            );

            try {
                const [disease, treatment, lstm] = await Promise.all([
                    fetch("/api/predict/disease", request(jsonData)),
                    fetch("/api/predict/treatment", request(jsonData)),
                    fetch("/api/predict/lstm", request(jsonData)),
                ]);
                const diseaseData = await disease.json();
                const treatmentData = await treatment.json();
                const lstmData = await lstm.json();

                resultDiv.innerHTML = `
                    <div class="alert alert-info shadow fade-in">
                        🧠 <strong>التشخيص:</strong> ${mapPredictionLabel(
                            diseaseData.prediction_result
                        )}<br>
                        💊 <strong>العلاج:</strong> ${
                            treatmentData.treatment_result
                        }<br>
                        📈 <strong>تطور المرض:</strong> ${mapPredictionLabel(
                            lstmData.lstm_result.prediction
                        )}<br>
                        🎯 <strong>نسبة الثقة:</strong> ${(
                            lstmData.lstm_result.confidence * 100
                        ).toFixed(1)}%
                    </div>`;

                await fetch(
                    "/api/records",
                    request({
                        patient_id: parseInt(patientSelect.value),
                        ...jsonData,
                        prediction: diseaseData.prediction_result,
                        confidence: lstmData.lstm_result.confidence,
                        treatment: treatmentData.treatment_result,
                    })
                );
            } catch (err) {
                console.error("❌ خطأ أثناء تحليل البيانات:", err);
                resultDiv.innerHTML = `<div class="alert alert-danger">⚠️ حدث خطأ أثناء الاتصال بالخادم!</div>`;
            }
        });
    }

    // -----------------------------------------------------------
    // 🧾 التقارير
    const uploadForm = document.getElementById("uploadReportForm");
    const reportTableBody = document.getElementById("reportTableBody");

    async function initReports() {
        await populateReportsPatients();
        await fetchReports();
    }

    async function populateReportsPatients() {
        const selects = [
            document.getElementById("report_patient_id"),
            document.getElementById("report_patient_select"),
        ];
        try {
            const res = await fetch("/api/patients");
            const data = await res.json();
            selects.forEach((select) => {
                if (select) {
                    select.innerHTML = `<option value="">-- اختر --</option>`;
                    data.forEach((p) => {
                        select.innerHTML += `<option value="${p.id}">${p.Name}</option>`;
                    });
                }
            });
        } catch (err) {
            console.error("❌ فشل تحميل المرضى للتقارير:", err);
        }
    }

    async function fetchReports() {
        // (مستقبلاً: إحضار حسب المريض المختار)
        reportTableBody.innerHTML = `
            <tr><td colspan="4">📂 سيتم تحميل التقارير عند الربط الكامل بقاعدة البيانات</td></tr>
        `;
    }

    if (uploadForm) {
        uploadForm.addEventListener("submit", async function (e) {
            e.preventDefault();
            const formData = new FormData(uploadForm);
            try {
                const res = await fetch("/api/reports", {
                    method: "POST",
                    body: formData,
                });
                await res.json();
                bootstrap.Modal.getInstance(
                    document.getElementById("uploadReportModal")
                ).hide();
                uploadForm.reset();
                fetchReports();
            } catch (err) {
                console.error("❌ فشل رفع التقرير:", err);
            }
        });
    }

    // -----------------------------------------------------------
    // ⚙️ المعالجة المسبقة
    async function initPreprocessing() {
        const select = document.getElementById("pre_patient_id");
        const tableBody = document.getElementById("preTableBody");
        const section = document.getElementById("preprocessingContent");
        const warning = document.getElementById("preWarnings");
        const noMsg = document.getElementById("noRecordsMessage");

        try {
            const res = await fetch("/api/patients");
            const data = await res.json();
            select.innerHTML = `<option value="">-- اختر مريضًا --</option>`;
            data.forEach((p) => {
                select.innerHTML += `<option value="${p.id}">${p.Name}</option>`;
            });

            select.addEventListener("change", async function () {
                const id = select.value;
                if (!id) return;

                const res = await fetch(`/api/preprocessing/${id}`);
                const record = await res.json();

                if (!record) {
                    section.classList.add("d-none");
                    noMsg.classList.remove("d-none");
                    return;
                }

                section.classList.remove("d-none");
                noMsg.classList.add("d-none");
                warning.classList.add("d-none");

                tableBody.innerHTML = "";
                for (const key in record) {
                    const value = record[key];
                    let note = "";
                    if (value === null || value < 0) {
                        note = "❗ تحقق من القيمة";
                        warning.classList.remove("d-none");
                    }
                    tableBody.innerHTML += `
                        <tr>
                            <td>${key}</td>
                            <td>${value ?? "غير مدخل"}</td>
                            <td>${note}</td>
                        </tr>`;
                }
            });
        } catch (err) {
            console.error("❌ فشل المعالجة المسبقة:", err);
        }
    }

    // -----------------------------------------------------------
    // 📊 الإحصاءات
    async function loadStats() {
        try {
            const res = await fetch("/api/stats");
            const stats = await res.json();
            document.getElementById("statPatients").innerText =
                stats.patients || 0;
            document.getElementById("statRecords").innerText =
                stats.records || 0;
            document.getElementById("statReports").innerText =
                stats.reports || 0;
            document.getElementById("statAI").innerText =
                stats.predictions || 0;

            renderChart(stats.distribution || {});
        } catch (err) {
            console.error("❌ فشل تحميل الإحصاءات:", err);
        }
    }

    function renderChart(data) {
        const ctx = document.getElementById("diagnosisChart").getContext("2d");
        new Chart(ctx, {
            type: "pie",
            data: {
                labels: Object.keys(data),
                datasets: [
                    {
                        data: Object.values(data),
                        backgroundColor: [
                            "#198754",
                            "#ffc107",
                            "#fd7e14",
                            "#dc3545",
                            "#0d6efd",
                        ],
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: "bottom" },
                },
            },
        });
    }

    // -----------------------------------------------------------
    // أدوات مساعدة
    function mapPredictionLabel(code) {
        const labels = {
            0: "سليم",
            1: "مشتبه بالإصابة",
            2: "التهاب كبد",
            3: "تليف كبد",
            4: "تشمع كبد",
        };
        return labels[code] || "غير معروف";
    }

    function request(data) {
        return {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify(data),
        };
    }

    console.log("✅ dashboard.js loaded");
});
