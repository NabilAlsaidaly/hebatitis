document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("patientForm");
    const resultDiv = document.getElementById("result");

    if (!form) return;

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        resultDiv.innerHTML = `<div class="alert alert-secondary">📡 جاري إرسال البيانات...</div>`;

        const formData = new FormData(form);
        const jsonData = {};
        formData.forEach((value, key) => (jsonData[key] = parseFloat(value)));

        try {
            // تشخيص الحالة
            const diseaseRes = await fetch("/api/predict/disease", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(jsonData),
            });
            const diseaseData = await diseaseRes.json();

            // توصية العلاج
            const treatmentRes = await fetch("/api/predict/treatment", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(jsonData),
            });
            const treatmentData = await treatmentRes.json();

            const label = mapPredictionLabel(diseaseData.prediction_result);

            resultDiv.innerHTML = `
                <div class="alert alert-info shadow fade-in">
                    🧠 <strong>التشخيص:</strong> ${label}<br>
                    💊 <strong>العلاج المقترح:</strong> ${treatmentData.treatment_result}
                </div>
            `;
        } catch (error) {
            console.error(error);
            resultDiv.innerHTML = `<div class="alert alert-danger">⚠️ حدث خطأ أثناء الاتصال بالنموذج!</div>`;
        }
    });
});

// دالة لتحويل نتيجة التشخيص إلى نص
function mapPredictionLabel(code) {
    const labels = {
        0: "سليم",
        1: "مشتبه بالإصابة",
        2: "التهاب كبد",
        3: "تليف كبد",
        4: "تشمع كبد"
    };
    return labels[code] || "غير معروف";
}

// توقع تطور المرض (LSTM)
const lstmRes = await fetch("/api/predict/lstm", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(jsonData),
});
const lstmData = await lstmRes.json();

