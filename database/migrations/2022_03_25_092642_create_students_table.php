<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_no');
            $table->string('address');
            $table->foreignId('faculty_id')->constrained();
            $table->string('email')->unique();
            $table->string('college_email');
            $table->string('parent_name');
            $table->string('parent_contact');
            $table->integer('year');
            $table->string('image')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('symbol_no');
            $table->string('documents')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
