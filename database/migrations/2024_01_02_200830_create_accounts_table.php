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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id(); // tao id big integer - tu dong dang luon - hieu la khoa chinh
            $table->unsignedBigInteger('role_id');
            $table->string('username',30)->unique();
            $table->string('password',200);
            $table->string('email',100)->unique();
            $table->string('phoned',20);
            $table->tinyInteger('gender')->default(1);
            $table->string('address',200)->nullable();
            $table->date('birthday')->nullable();
            $table->string('first_name',60);
            $table->string('last_name',60)->nullable();
            $table->integer('status');
            $table->string('avatar',200)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('last_logout')->nullable();
            $table->string('ip_client',100);
            $table->timestamps(); // tao ra 2 truong created_at va updated_at
            $table->softDeletes(); // tao cot deleted_at

            // tao lien ket khoa ngoai
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
