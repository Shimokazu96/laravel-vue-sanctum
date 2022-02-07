<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('tel')->nullable();
            // $table->string('furigana')->nullable();
            $table->string('nickname')->nullable();
            $table->string('zip')->nullable();
            $table->integer('pref')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('building')->nullable();
            // $table->integer('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->text('introduction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
