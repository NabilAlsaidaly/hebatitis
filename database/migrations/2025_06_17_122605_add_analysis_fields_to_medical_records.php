<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            // ✅ لا نعيد تعريف Doctor_ID إذا كان مضاف مسبقًا

            // 🧠 نتائج الذكاء الاصطناعي
            $table->integer('Prediction')->nullable()->after('Sex');
            $table->float('Confidence')->nullable()->after('Prediction');
            $table->text('Treatment')->nullable()->after('Confidence');

            // 🔐 مفتاح خارجي للطبيب (Doctor_ID موجود مسبقًا)
            $table->foreign('Doctor_ID')
                ->references('User_ID')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropForeign(['Doctor_ID']);
            $table->dropColumn(['Prediction', 'Confidence', 'Treatment']);
        });
    }
};
