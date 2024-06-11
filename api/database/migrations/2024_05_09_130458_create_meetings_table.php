<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->time('start_hour');
            $table->time('end_hour');
            $table->integer('break_time');
            $table->string('location');
            $table->foreignId('school_id');
            $table->foreignId('admin_id');       // Clé étrangère pour l'administrateur
            $table->foreignId('trainer_id');     // Clé étrangère pour le trainer
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('trainer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
