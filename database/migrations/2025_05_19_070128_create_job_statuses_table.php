<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('job_statuses', function (Blueprint $table) {
            $table->string('job_id')->primary();
            $table->string('status')->default('processing');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_statuses');
    }
}