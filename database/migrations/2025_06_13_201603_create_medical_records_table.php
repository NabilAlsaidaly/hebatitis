<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id('Record_ID');
            $table->foreignId('Patients_ID')->constrained('patients', 'Patients_ID')->onDelete('cascade');

            $table->float('Age')->nullable();
            $table->float('Sex')->nullable();
            $table->float('ALB')->nullable();
            $table->float('ALP')->nullable();
            $table->float('ALT')->nullable();
            $table->float('AST')->nullable();
            $table->float('BIL')->nullable();
            $table->float('CHE')->nullable();
            $table->float('CHOL')->nullable();
            $table->float('CREA')->nullable();
            $table->float('GGT')->nullable();
            $table->float('PROT')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
