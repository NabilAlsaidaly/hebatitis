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
            if (this.id === "nav-patients") {
                fetchPatients();
                bindAddPatientFormEvents(); // ✅ ربط النموذج عند فتح القسم
            }
            if (this.id === "nav-analysis") populatePatientDropdown();
            if (this.id === "nav-reports") initReports();
            if (this.id === "nav-preprocessing") initPreprocessing();
            if (this.id === "nav-stats") loadStats();
            if (this.id === "nav-lstm") initLSTM();
        });
    });

    // -----------------------------------------------------------
    // 🧾 قسم المرضى
    const patientTableBody = document.getElementById("patientTableBody");

    function bindAddPatientFormEvents() {
        const addPatientForm = document.getElementById("addPatientForm");
        if (!addPatientForm || addPatientForm.dataset.bound === "true") return;

        addPatientForm.addEventListener("submit", async function (e) {
            e.preventDefault();
            const formData = new FormData(addPatientForm);

            const newPatient = {
                name: formData.get("name"),
                dob: formData.get("dob"),
                contact_info: formData.get("contact_info"),
                email: formData.get("email"),
                password: formData.get("password"),
            };

            try {
                const res = await fetch("/doctor/patients", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify(newPatient),
                });

                const response = await res.json();
                console.log("✅ تمت إضافة المريض:", response);
                addPatientForm.reset();
                bootstrap.Modal.getInstance(
                    document.getElementById("addPatientModal")
                ).hide();
                fetchPatients();
            } catch (err) {
                console.error("❌ فشل في إضافة المريض:", err);
                alert("⚠️ حدث خطأ أثناء الإضافة");
            }
        });

        addPatientForm.dataset.bound = "true";
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
                    <button class="btn btn-sm btn-outline-primary"
                            onclick="editPatient(${p.id}, '${p.Name}', '${
                    p.Date_Of_Birth ?? ""
                }', '${p.Contact_Info ?? ""}')">
                        ✏️ تعديل
                    </button>
                    <button class="btn btn-sm btn-outline-danger"
                            onclick="deletePatient(${p.id})">
                        🗑️ حذف
                    </button>
                    <button class="btn btn-sm btn-outline-secondary"
                            onclick="viewPatientDetails(${p.id})">
                        📋 عرض التفاصيل
                    </button>
                </td>
            </tr>`;
            });

            window.editPatient = function (id, name, dob, contact_info) {
                const form = document.getElementById("addPatientForm");
                const modalTitle = document.querySelector(
                    "#addPatientModal .modal-title"
                );
                const saveBtn = form.querySelector("button[type='submit']");
                const modal = new bootstrap.Modal(
                    document.getElementById("addPatientModal")
                );

                form.name.value = name;
                form.dob.value = dob;
                form.contact_info.value = contact_info;

                const emailField = form.querySelector("input[name='email']");
                const passwordField = form.querySelector(
                    "input[name='password']"
                );
                if (emailField) emailField.parentElement.remove();
                if (passwordField) passwordField.parentElement.remove();

                modalTitle.innerText = "✏️ تعديل مريض";
                saveBtn.innerText = "🔄 تحديث";
                modal.show();

                // تخزين مرجع للنموذج الأصلي قبل استبداله
                const originalForm = form;
                const newForm = form.cloneNode(true);
                form.parentNode.replaceChild(newForm, form);

                newForm.addEventListener("submit", async function (e) {
                    e.preventDefault();
                    const formData = new FormData(newForm);

                    const updatedPatient = {
                        name: formData.get("name"),
                        dob: formData.get("dob"),
                        contact_info: formData.get("contact_info"),
                    };

                    try {
                        const res = await fetch(`/api/patients/${id}`, {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).content,
                            },
                            body: JSON.stringify(updatedPatient),
                        });

                        const result = await res.json();
                        console.log("✅ تم التعديل:", result);
                        modal.hide();
                        fetchPatients();

                        // إعادة إنشاء نموذج جديد للإضافة بعد الانتهاء من التعديل
                        const container = newForm.parentNode;
                        const freshForm = document.createElement("form");
                        freshForm.id = "addPatientForm";
                        freshForm.className = "modal-content";
                        freshForm.innerHTML = `
                        <input type="hidden" name="_token" value="${
                            document.querySelector('meta[name="csrf-token"]')
                                .content
                        }">
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
                    `;
                        container.replaceChild(freshForm, newForm);
                        bindAddPatientFormEvents();
                    } catch (err) {
                        console.error("❌ فشل التعديل:", err);
                        alert("⚠️ حدث خطأ أثناء التعديل");
                    }
                });
            };
        } catch (err) {
            console.error("❌ فشل تحميل المرضى:", err);
        }
    }

    window.deletePatient = async function (id) {
        if (!confirm("هل أنت متأكد من حذف المريض؟")) return;

        try {
            const res = await fetch(`/api/patients/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            });

            await res.json();
            fetchPatients();
        } catch (err) {
            console.error("❌ فشل الحذف:", err);
            alert("حدث خطأ أثناء الحذف");
        }
    };

    window.viewPatientDetails = async function (patientId) {
        const modal = new bootstrap.Modal(
            document.getElementById("patientDetailsModal")
        );
        const nameField = document.getElementById("detailName");
        const dobField = document.getElementById("detailDOB");
        const tableBody = document.getElementById("recordsTableBody");

        try {
            const res = await fetch(`/api/patients/${patientId}/records`);
            const data = await res.json();

            nameField.textContent = data.patient.name;
            dobField.textContent = data.patient.dob ?? "غير متوفر";
            tableBody.innerHTML = "";

            data.records.forEach((record) => {
                const diagnosis = record.diagnosis?.disease_stage ?? "—";
                const prediction = record.prediction?.result ?? "—";

                const row = `
                <tr>
                    <td>${record.created_at?.slice(0, 10) ?? "—"}</td>
                    <td>${record.ALT ?? "—"}</td>
                    <td>${record.AST ?? "—"}</td>
                    <td>${record.BIL ?? "—"}</td>
                    <td>${record.ALB ?? "—"}</td>
                    <td>${record.CHOL ?? "—"}</td>
                    <td>${diagnosis}</td>
                    <td>${prediction}</td>
                </tr>`;
                tableBody.innerHTML += row;
            });

            modal.show();
        } catch (err) {
            console.error("❌ فشل في تحميل تفاصيل المريض:", err);
            alert("حدث خطأ أثناء تحميل التفاصيل");
        }
    };

    // دالة إعادة تهيئة نموذج إضافة المريض
    function resetAddPatientForm() {
        const form = document.getElementById("addPatientForm");
        const modalTitle = document.querySelector(
            "#addPatientModal .modal-title"
        );
        const saveBtn = form.querySelector("button[type='submit']");

        // إعادة تعيين النموذج
        form.reset();

        // إعادة إضافة حقول البريد الإلكتروني وكلمة المرور إذا تمت إزالتها
        const formContent = form.innerHTML;
        if (!formContent.includes('name="email"')) {
            const emailField = `
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>`;
            form.insertAdjacentHTML("beforeend", emailField);
        }

        if (!formContent.includes('name="password"')) {
            const passwordField = `
        <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>`;
            form.insertAdjacentHTML("beforeend", passwordField);
        }

        // تغيير عنوان النموذج وزر الحفظ
        modalTitle.innerText = "➕ إضافة مريض جديد";
        saveBtn.innerText = "💾 حفظ";

        // إعادة ربط أحداث النموذج
        bindAddPatientFormEvents();
    }

    // 🟢 تشغيل أولي
    bindAddPatientFormEvents();
    fetchPatients();

    // -----------------------------------------------------------
    // 🧪 قسم التحليل
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

    function renderProbabilitiesChart(probabilities) {
    if (!probabilities || !Array.isArray(probabilities)) {
        return "<span class='text-muted'>غير متوفرة</span>";
    }

    const labels = ["سليم", "مشتبه بالإصابة", "التهاب كبد", "تليف كبد", "تشمع كبد"];
    let html = '<div class="probabilities-container">';

    probabilities.forEach((prob, index) => {
        if (index < labels.length) {
            const percentage = Math.round(prob * 100);
            html += `
                <div class="prob-item mb-1">
                    <small>${labels[index]}: ${percentage}%</small>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: ${percentage}%"
                            aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            `;
        }
    });

    html += '</div>';
    return html;
}


    if (form) {
    form.addEventListener("submit", async function (e) {
        e.preventDefault();
        resultDiv.innerHTML = `<div class="alert alert-secondary">📡 جاري إرسال البيانات...</div>`;

        const formData = new FormData(form);
        const jsonData = {};
        formData.forEach((value, key) => {
            jsonData[key] = parseFloat(value); // نحول الأرقام
        });

        try {
            // 🔹 استدعاء التشخيص
            const diseaseResponse = await fetch(
                "/api/predict/disease",
                request(jsonData)
            );

            if (!diseaseResponse.ok) {
                const error = await diseaseResponse.json();
                throw new Error("⚠️ فشل التحليل: " + (error.error || "خطأ غير معروف"));
            }

            const diseaseData = await diseaseResponse.json();

            // ✅ إضافة التصنيف Category بعد التشخيص
            if (
                diseaseData &&
                typeof diseaseData.prediction_result !== "undefined"
            ) {
                jsonData.Category = diseaseData.prediction_result;
            } else {
                throw new Error("❌ فشل في استخراج التصنيف من استجابة التشخيص.");
            }

            // 🔹 استدعاء العلاج
            const treatmentResponse = await fetch(
                "/api/predict/treatment",
                request(jsonData)
            );

            if (!treatmentResponse.ok) {
                const error = await treatmentResponse.json();
                throw new Error("⚠️ فشل التوصية بالعلاج: " + (error.error || "خطأ غير معروف"));
            }

            const treatmentData = await treatmentResponse.json();

            // ✅ عرض النتائج
            resultDiv.innerHTML = `
                <div class="alert alert-info shadow fade-in">
                    🧠 <strong>التشخيص:</strong> ${mapPredictionLabel(
                        diseaseData.prediction_result
                    )}<br>
                    💊 <strong>العلاج:</strong> ${treatmentData.treatment_result}<br>
                    📊 <strong>احتمالات التصنيف:</strong>
                    <div class="mt-2">
                        ${renderProbabilitiesChart(diseaseData.probabilities)}
                    </div>
                </div>
            `;

            // ✅ حفظ النتائج
            await saveResult(
                patientSelect.value,
                jsonData,
                diseaseData,
                treatmentData
            );
            if (saveResult) {
                console.log("✅ تم الحفظ:", saveResult);
            }

        } catch (err) {
            console.error("❌ خطأ أثناء تحليل البيانات:", err);
            resultDiv.innerHTML = `<div class="alert alert-danger">⚠️ ${err.message}</div>`;
        }
    });
}




// ✅ حفظ النتيجة
async function saveResult(patientId, jsonData, diseaseData, treatmentData) {
    try {
        // استخراج الاحتمالات من استجابة API
        const probabilities = diseaseData.probabilities || [0, 0, 0, 0, 0];

        const response = await fetch("/records", {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({
                patient_id: parseInt(patientId),
                ...jsonData,
                prediction: diseaseData.prediction_result,
                treatment: treatmentData.treatment_result,
                probabilities: probabilities, // استخدام الاحتمالات الحقيقية
                confidence: null,
            }),
        });

        if (!response.ok) throw new Error("فشل الحفظ: " + response.status);
        const resData = await response.json();
        alert(resData.message || "✅ تم الحفظ بنجاح");
    } catch (err) {
        console.error("❌ خطأ أثناء الحفظ:", err);
        alert("❌ لم يتم الحفظ: " + err.message);
    }
}

// ... existing code ...

    // -----------------------------------------------------------
    async function initLSTM() {
        const select = document.getElementById("lstm_patient_id");
        const resultDiv = document.getElementById("lstmResult");
        const form = document.getElementById("lstmForm");

        // 🧠 تحميل المرضى في القائمة
        try {
            const res = await fetch("/api/patients");
            const patients = await res.json();
            select.innerHTML = `<option value="">-- اختر مريضاً --</option>`;
            patients.forEach((p) => {
                select.innerHTML += `<option value="${p.id}">${p.Name}</option>`;
            });
        } catch (err) {
            console.error("❌ فشل تحميل المرضى:", err);
            resultDiv.innerHTML = `<div class="alert alert-danger">⚠️ حدث خطأ أثناء تحميل المرضى</div>`;
            return;
        }

        // 📤 عند إرسال النموذج
        form.addEventListener("submit", async function (e) {
            e.preventDefault();
            const patientId = select.value;
            if (!patientId) return alert("يرجى اختيار مريض أولاً.");

            // ⏳ جمع بيانات الأشهر الستة
            const formData = new FormData(form);
            const ALT = formData.getAll("ALT[]").map(Number);
            const AST = formData.getAll("AST[]").map(Number);
            const BIL = formData.getAll("BIL[]").map(Number);

            const series = [];
            for (let i = 0; i < 6; i++) {
                series.push({ ALT: ALT[i], AST: AST[i], BIL: BIL[i] });
            }

            try {
                const response = await fetch("/api/predict/lstm", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify(series),
                });

                const data = await response.json();
                console.log("📥 استجابة LSTM:", data);

                const labels = ["سليم", "مشتبه", "التهاب", "تليف", "تشمع"];
                const result = data.lstm_result || {};

                const label = labels[result.prediction] || "غير معروف";
                const confidenceValue = parseFloat(result.confidence);
                const confidence = isNaN(confidenceValue)
                    ? "غير متوفرة"
                    : (confidenceValue * 100).toFixed(2) + "%";

                // ✅ عرض النتيجة
                resultDiv.innerHTML = `
                <div class="alert alert-info mb-4">
                    🔮 <strong>النتيجة المتوقعة بعد 6 أشهر:</strong> ${label}<br>
                    📊 <strong>نسبة الثقة:</strong> ${confidence}
                </div>
                <canvas id="lstmChart" height="200"></canvas>
            `;

                // 📊 رسم الرسم البياني
                renderLSTMChart(ALT, AST, BIL);
            } catch (err) {
                console.error("❌ فشل تحليل LSTM:", err);
                resultDiv.innerHTML = `<div class="alert alert-danger">⚠️ فشل التحليل. تأكد من إدخال القيم بشكل صحيح.</div>`;
            }
        });

        // 📈 دالة رسم المخطط البياني
        function renderLSTMChart(ALT, AST, BIL) {
            const ctx = document.getElementById("lstmChart").getContext("2d");
            new Chart(ctx, {
                type: "line",
                data: {
                    labels: [
                        "شهر 1",
                        "شهر 2",
                        "شهر 3",
                        "شهر 4",
                        "شهر 5",
                        "شهر 6",
                    ],
                    datasets: [
                        {
                            label: "ALT",
                            data: ALT,
                            borderWidth: 2,
                            borderColor: "#0d6efd",
                            fill: false,
                            tension: 0.4,
                        },
                        {
                            label: "AST",
                            data: AST,
                            borderWidth: 2,
                            borderColor: "#198754",
                            fill: false,
                            tension: 0.4,
                        },
                        {
                            label: "BIL",
                            data: BIL,
                            borderWidth: 2,
                            borderColor: "#dc3545",
                            fill: false,
                            tension: 0.4,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: "top" },
                        title: {
                            display: true,
                            text: "📈 تطور مؤشرات التحاليل خلال 6 أشهر",
                        },
                    },
                },
            });
        }
    }

    window.initLSTM = initLSTM;

    //------------------------------------------------------------
    // 🧾 قسم التقارير
    const uploadForm = document.getElementById("uploadReportForm");
    const reportTableBody = document.getElementById("reportTableBody");
    const reportPatientSelect = document.getElementById(
        "report_patient_select"
    );
    const generateBtn = document.getElementById("generateReportBtn");

    async function initReports() {
        await populateReportsPatients();

        // ✅ إذا كان هناك مريض محدد، حمل تقاريره مباشرة
        if (reportPatientSelect) {
            reportPatientSelect.addEventListener("change", async function () {
                const patientId = this.value;
                if (patientId) await fetchReports(patientId);
            });
        }

        // 📄 توليد تقرير PDF تلقائي - مع منع النقر المتكرر
        if (generateBtn) {
            // إزالة أي مستمعي أحداث سابقة لمنع التكرار
            generateBtn.replaceWith(generateBtn.cloneNode(true));
            const refreshedGenerateBtn =
                document.getElementById("generateReportBtn");

            // إضافة متغير لتتبع حالة الطلب
            let isGeneratingReport = false;

            refreshedGenerateBtn.addEventListener("click", async function () {
                const patientId = reportPatientSelect.value;
                if (!patientId) return alert("⚠️ الرجاء اختيار مريض أولاً.");

                // منع النقرات المتكررة
                if (isGeneratingReport) return;
                isGeneratingReport = true;

                refreshedGenerateBtn.disabled = true;
                refreshedGenerateBtn.innerText = "⏳ جاري إنشاء التقرير...";

                try {
                    const res = await fetch(
                        `/api/reports/generate/${patientId}`,
                        {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).content,
                            },
                        }
                    );

                    const data = await res.json();
                    if (res.ok) {
                        alert(data.message || "✅ تم إنشاء التقرير.");
                        await fetchReports(patientId);
                    } else {
                        console.error("❌ فشل:", data.error);
                        alert("⚠️ لم يتم إنشاء التقرير.");
                    }
                } catch (err) {
                    console.error("❌ خطأ في الإنشاء:", err);
                    alert("⚠️ حدث خطأ أثناء إنشاء التقرير.");
                } finally {
                    refreshedGenerateBtn.disabled = false;
                    refreshedGenerateBtn.innerText = "📄 إنشاء تقرير PDF";
                    isGeneratingReport = false;
                }
            });
        }
    }

    // 🧠 تحميل قائمة المرضى
    async function populateReportsPatients() {
        const selects = [
            document.getElementById("report_patient_id"),
            reportPatientSelect,
        ];

        try {
            const res = await fetch("/api/patients");
            const data = await res.json();

            selects.forEach((select) => {
                if (select) {
                    select.innerHTML = `<option value="">-- اختر مريضاً --</option>`;
                    data.forEach((p) => {
                        select.innerHTML += `<option value="${p.id}">${p.Name}</option>`;
                    });
                }
            });
        } catch (err) {
            console.error("❌ فشل تحميل المرضى:", err);
        }
    }

    // 📥 جلب التقارير لمريض محدد
    async function fetchReports(patientId) {
        try {
            const res = await fetch(`/api/reports/list/${patientId}`);
            const reports = await res.json();

            if (Array.isArray(reports) && reports.length > 0) {
                reportTableBody.innerHTML = "";
                reports.forEach((report, i) => {
                    reportTableBody.innerHTML += `
        <tr>
            <td>${i + 1}</td>
            <td>${report.file_path}</td>
            <td>${report.created_at?.slice(0, 10) || "—"}</td>
            <td class="text-center">
                <a href="/storage/${
                    report.file_path
                }" download class="btn btn-sm btn-outline-success me-1">
                    ⬇️ تحميل
                </a>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteReport(${
                    report.id
                }, event)">
                    🗑️ حذف
                </button>
            </td>
        </tr>
    `;
                });
            } else {
                reportTableBody.innerHTML = `
                <tr><td colspan="4">📂 لا توجد تقارير حالياً لهذا المريض</td></tr>
            `;
            }
            // إزالة هذا الجزء لأنه يتسبب في تكرار مستمعي الأحداث وتكرار إنشاء التقارير
            // تم نقل وظيفة إنشاء التقرير إلى دالة initReports فقط

            window.deleteReport = async function (reportId, event) {
                if (!confirm("هل أنت متأكد أنك تريد حذف هذا التقرير؟")) return;

                // تحديد زر الحذف وتغيير حالته لإظهار أن العملية جارية
                const deleteButton = event
                    ? event.target.closest("button")
                    : document.querySelector(
                          `button[onclick="deleteReport(${reportId})"]`
                      );
                const originalText = deleteButton
                    ? deleteButton.innerHTML
                    : "🗑️ حذف";

                if (deleteButton) {
                    deleteButton.disabled = true;
                    deleteButton.innerHTML = "⏳ جارٍ الحذف...";
                }

                try {
                    // تحديد صف التقرير في الجدول مباشرة للتحديث الفوري
                    const reportRow = deleteButton
                        ? deleteButton.closest("tr")
                        : document
                              .querySelector(
                                  `button[onclick="deleteReport(${reportId})"]`
                              )
                              ?.closest("tr");

                    const res = await fetch(`/api/reports/${reportId}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                        },
                    });

                    const result = await res.json();

                    if (res.ok) {
                        if (reportRow) {
                            // إزالة الصف من الجدول فورًا بتأثير بصري
                            reportRow.style.transition = "opacity 0.3s";
                            reportRow.style.opacity = "0";

                            setTimeout(() => {
                                // إزالة الصف من DOM بعد انتهاء التأثير البصري
                                reportRow.remove();

                                // التحقق مما إذا كان الجدول فارغًا الآن
                                if (reportTableBody.children.length === 0) {
                                    reportTableBody.innerHTML = `<tr><td colspan="4">📂 لا توجد تقارير حالياً لهذا المريض</td></tr>`;
                                }

                                alert(result.message || "✅ تم حذف التقرير");
                            }, 300);
                        } else {
                            // إذا لم نتمكن من العثور على الصف، نقوم بتحديث الجدول بالكامل
                            alert(result.message || "✅ تم حذف التقرير");
                            const currentPatientId = document.getElementById(
                                "report_patient_select"
                            ).value;
                            if (currentPatientId) {
                                await fetchReports(currentPatientId);
                            }
                        }
                    } else {
                        // إعادة الزر إلى حالته الأصلية في حالة الفشل
                        if (deleteButton) {
                            deleteButton.disabled = false;
                            deleteButton.innerHTML = originalText;
                        }
                        alert(result.error || "⚠️ فشل حذف التقرير");
                    }
                } catch (err) {
                    console.error("❌ فشل حذف التقرير:", err);
                    alert("⚠️ حدث خطأ أثناء الحذف");
                    // إعادة الزر إلى حالته الأصلية في حالة الخطأ
                    if (deleteButton) {
                        deleteButton.disabled = false;
                        deleteButton.innerHTML = originalText;
                    }
                }
            };
        } catch (err) {
            console.error("❌ فشل تحميل التقارير:", err);
            reportTableBody.innerHTML = `<tr><td colspan="4">⚠️ حدث خطأ</td></tr>`;
        }
    }

    // -----------------------------------------------------------
    // ⚙️ المعالجة المسبقة
   async function initPreprocessing() {
    const select = document.getElementById("pre_patient_id");
    const tableBody = document.getElementById("preTableBody");
    const section = document.getElementById("preprocessingContent");
    const warning = document.getElementById("preWarnings");
    const noMsg = document.getElementById("noRecordsMessage");
    const sendButton = document.getElementById("sendToAI");

    try {
        // تحميل قائمة المرضى
        const res = await fetch("/api/patients");
        const data = await res.json();

        select.innerHTML = `<option value="">-- اختر مريضًا --</option>`;
        data.forEach((p) => {
            select.innerHTML += `<option value="${p.id}">${p.Name}</option>`;
        });

        // عند اختيار مريض
        select.addEventListener("change", async function () {
            const id = select.value;
            if (!id) return;

            const res = await fetch(`/api/preprocessing/${id}`);
            const record = await res.json();

            if (!record || Object.keys(record).length === 0) {
                section.classList.add("d-none");
                noMsg.classList.remove("d-none");
                return;
            }

            section.classList.remove("d-none");
            noMsg.classList.add("d-none");
            warning.classList.add("d-none");
            tableBody.innerHTML = "";

            let hasIssue = false;

            // ترتيب الحقول
            const fieldsOrder = [
                "Age",
                "Sex",
                "ALB",
                "ALP",
                "ALT",
                "AST",
                "BIL",
                "CHE",
                "CHOL",
                "CREA",
                "GGT",
                "PROT",
            ];

            fieldsOrder.forEach((key) => {
                const value = record[key];
                let note = "";

                if (value === null || value < 0) {
                    note = "❗ تحقق من القيمة";
                    warning.classList.remove("d-none");
                    hasIssue = true;
                }

                tableBody.innerHTML += `
                    <tr>
                        <td>${key}</td>
                        <td>${value ?? "غير مدخل"}</td>
                        <td>${note}</td>
                    </tr>`;
            });

            // منع الإرسال في حال وجود أخطاء
            sendButton.disabled = hasIssue;
        });
    } catch (err) {
        console.error("❌ فشل تحميل البيانات:", err);
    }
}

// ✅ إرسال البيانات إلى الذكاء الاصطناعي بعد الضغط على زر التحليل
document
    .getElementById("sendToAI")
    .addEventListener("click", async function () {
        const rows = document.querySelectorAll("#preTableBody tr");
        const aiBox = document.getElementById("aiResultBox");
        const diagnosisText = document.getElementById("aiDiagnosis");
        const treatmentText = document.getElementById("aiTreatment");

        const data = {};
        rows.forEach((row) => {
            const cells = row.querySelectorAll("td");
            const key = cells[0].innerText;
            const value = parseFloat(cells[1].innerText);
            data[key] = isNaN(value) ? null : value;
        });

        try {
            // 1️⃣ التشخيص
            const predRes = await fetch("/api/predict/disease", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data),
            });

            const predJson = await predRes.json();
            console.log("🎯 تشخيص:", predJson);

            if (
                predJson &&
                typeof predJson.prediction_result !== "undefined"
            ) {
                data.Category = predJson.prediction_result;
            } else {
                throw new Error("❌ لم يتم استخراج التصنيف من استجابة التشخيص.");
            }

            // 2️⃣ العلاج
            const treatRes = await fetch("/api/predict/treatment", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data),
            });

            const treatJson = await treatRes.json();
            console.log("💊 علاج:", treatJson);

            // 3️⃣ عرض النتائج
            const predictionValue = predJson.prediction_result;
            const treatmentValue = treatJson.treatment_result;
            const predictionLabel = mapPredictionLabel(predictionValue);

            diagnosisText.innerHTML = `🔍 التشخيص: <strong>${predictionLabel}</strong>`;
            treatmentText.innerHTML = `💊 العلاج المقترح: <strong>${treatmentValue}</strong>`;
            aiBox.classList.remove("d-none");

        } catch (err) {
            console.error("❌ فشل الاتصال بالذكاء الاصطناعي:", err);
            aiBox.classList.add("d-none");
        }
    });

    // ... existing code ...

    // -----------------------------------------------------------
    // 📊 قسم الإحصاءات
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
            type: "bar",
            data: {
                labels: Object.keys(data),
                datasets: [
                    {
                        label: "عدد الحالات",
                        data: Object.values(data),
                        backgroundColor: [
                            "#198754", // سليم
                            "#ffc107", // مشتبه
                            "#fd7e14", // التهاب
                            "#dc3545", // تليف
                            "#0d6efd", // تشمع
                        ],
                        borderRadius: 8, // زوايا ناعمة للأعمدة
                        barThickness: 40, // حجم العمود
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false, // إخفاء مفتاح الألوان لأنه واضح من الرسم
                    },
                    title: {
                        display: true,
                        text: "📊 توزيع التشخيصات المرضية",
                        font: {
                            size: 18,
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `العدد: ${context.parsed.y}`;
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: "نوع الحالة",
                            font: { size: 14 },
                        },
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: "عدد المرضى",
                            font: { size: 14 },
                        },
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                        },
                        grid: {
                            drawBorder: false,
                        },
                    },
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
            credentials: "same-origin", // 🔐 مهم جدًا لإرسال الكوكيز
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
