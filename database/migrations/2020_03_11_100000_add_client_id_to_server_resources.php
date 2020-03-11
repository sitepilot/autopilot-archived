<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientIdToServerResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_hosts', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->after('group_id')->nullable();

            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('set null');
        });

        Schema::table('server_users', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->after('host_id')->nullable();

            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('server_hosts', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });

        Schema::table('server_users', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
}
