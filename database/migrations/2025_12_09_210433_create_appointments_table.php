<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('doctor_name');
            $table->date('date');
            $table->string('time');
            $table->text('reason')->nullable();
            $table->enum('status', ['pendiente', 'realizada', 'cancelada'])->default('pendiente');
            $table->timestamps();
            $table->string('consultorio');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
