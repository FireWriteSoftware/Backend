<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bans', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('target_id')
                ->comment('Target User')
                ->nullable();

            $table->foreign('target_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('staff_id')
                ->comment('Staff User (Banner)')
                ->nullable();

            $table->foreign('staff_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('reason');
            $table->text('description')->nullable();
            $table->dateTime('ban_until')->nullable(); # If null = lifetime ban
            $table->integer('type')
                ->comment('0 => Global; 1 => Comments; 2 => Posts')
                ->nullable();

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
        Schema::dropIfExists('bans');
    }
}
