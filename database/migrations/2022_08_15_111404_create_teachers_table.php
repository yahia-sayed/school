<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{

    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('full_name', 100);
            $table->date('DOB');
            $table->string('phone_number', 15);
            $table->integer('salary')->nullable();
            $table->string('CV')->nullable();
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('set null')->onUpdate('set null');
            $table->string('address');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
