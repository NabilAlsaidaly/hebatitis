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
                fetchPatients(); // إعادة تحميل القائمة
            } catch (err) {
                console.error("❌ فشل في إضافة المريض:", err);
                alert("⚠️ حدث خطأ أثناء الإضافة");
            }
        });

        addPatientForm.dataset.bound = "true"; // منع التكرار
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
                        <button class="btn btn-sm btn-outline-primary" onclick="editPatient(${
                            p.id
                        }, '${p.Name}', '${p.Date_Of_Birth ?? ""}', '${
                    p.Contact_Info ?? ""
                }')">✏️ تعديل</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deletePatient(${
                            p.id
                        })">🗑️ حذف</button>
                    </td>
                </tr>`;
            });

            // ✅ دالة التعديل فقط (المعدلة)
            window.editPatient = function (id, name, dob, contact_info) {
                const form = document.getElementById("addPatientForm");
                const modalTitle = document.querySelector(
                    "#addPatientModal .modal-title"
                );
                const saveBtn = form.querySelector("button[type='submit']");
                const modal = new bootstrap.Modal(
                    document.getElementById("addPatientModal")
                );

                // تعبئة النموذج
                form.name.value = name;
                form.dob.value = dob;
                form.contact_info.value = contact_info;

                // ❌ إزالة الحقول: email + password تماماً من DOM
                const emailField = form.querySelector("input[name='email']");
                const passwordField = form.querySelector(
                    "input[name='password']"
                );
                if (emailField) emailField.parentElement.remove();
                if (passwordField) passwordField.parentElement.remove();

                // تحديث النص
                modalTitle.innerText = "✏️ تعديل مريض";
                saveBtn.innerText = "🔄 تحديث";

                // فتح النافذة
                modal.show();

                // إزالة المستمع السابق حتى لا يتكرر
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

                        // إعادة تعيين النصوص
                        modalTitle.innerText = "➕ إضافة مريض جديد";
                        saveBtn.innerText = "💾 حفظ";
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
                // 🔹 استدعاء التحليل
                const diseaseResponse = await fetch(
                    "/api/predict/disease",
                    request(jsonData)
                );
                if (!diseaseResponse.ok) throw new Error("⚠️ التحليل فشل.");
                const diseaseData = await diseaseResponse.json();

                // 🔹 استدعاء العلاج
                const treatmentResponse = await fetch(
                    "/api/predict/treatment",
                    request(jsonData)
                );
                if (!treatmentResponse.ok)
                    throw new Error("⚠️ التوصية بالعلاج فشلت.");
                const treatmentData = await treatmentResponse.json();

                // ✅ عرض النتائج
                resultDiv.innerHTML = `
                    <div class="alert alert-info shadow fade-in">
                        🧠 <strong>التشخيص:</strong> ${mapPredictionLabel(
                            diseaseData.prediction_result
                        )}<br>
                        💊 <strong>العلاج:</strong> ${
                            treatmentData.treatment_result
                        }
                    </div>
                `;

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
                    probabilities: [0.1, 0.2, 0.7], // مؤقتة، استبدلها لاحقاً بالقيم الفعلية
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

    // -----------------------------------------------------------
    // 🧾 قسم التقارير
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
    // ⚙️ قسم المعالجة المسبقة
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
