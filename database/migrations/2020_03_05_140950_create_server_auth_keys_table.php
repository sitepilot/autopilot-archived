<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServerAuthKeysTable  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_auth_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->longText('vars')->nullable();
            $table->timestamps();
        });

        Schema::create('server_auth_keyables', function (Blueprint $table) {
            $table->unsignedBigInteger('key_id');
            $table->unsignedBigInteger('keyable_id');
            $table->string('keyable_type');

            $table->foreign('key_id')
                ->references('id')->on('server_auth_keys')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('server_auth_keyables');
        Schema::dropIfExists('server_auth_keys');
    }
}
