<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeModuleIdNullableInMeetings extends Migration
{
    public function up()
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')->nullable()->change();  // Make module_id nullable
        });
    }

    public function down()
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')->nullable(false)->change();  // Revert back if necessary
        });
    }
}
