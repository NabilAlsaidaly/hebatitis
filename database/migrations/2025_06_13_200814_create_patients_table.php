<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id('Patients_ID');
            $table->string('Name');
            $table->date('Date_Of_Birth')->nullable();
            $table->text('Contact_Info')->nullable();
            $table->foreignId('Doctor_ID')->constrained('users', 'User_ID')->onDelete('cascade');
            $table->foreignId('User_ID')->nullable()->constrained('users', 'User_ID')->onDelete('set null');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
