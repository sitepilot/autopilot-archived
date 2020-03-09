<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppIdToServerDatabases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_databases', function (Blueprint $table) {
            $table->unsignedBigInteger('app_id')->after('user_id')->nullable();

            $table->foreign('app_id')
                ->references('id')->on('server_databases')
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
        Schema::table('server_databases', function (Blueprint $table) {
            $table->dropForeign(['app_id']);
            $table->dropColumn('app_id');
        });
    }
}
