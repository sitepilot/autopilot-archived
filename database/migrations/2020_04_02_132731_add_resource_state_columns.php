<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResourceStateColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_hosts', function (Blueprint $table) {
            $table->smallInteger('state')
                ->after('client_id')
                ->default(0);
        });

        Schema::table('server_users', function (Blueprint $table) {
            $table->smallInteger('state')
                ->after('client_id')
                ->default(0);
        });

        Schema::table('server_apps', function (Blueprint $table) {
            $table->smallInteger('state')
                ->after('user_id')
                ->default(0);
        });

        Schema::table('server_databases', function (Blueprint $table) {
            $table->smallInteger('state')
                ->after('app_id')
                ->default(0);
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
            $table->dropColumn('state');
        });

        Schema::table('server_users', function (Blueprint $table) {
            $table->dropColumn('state');
        });

        Schema::table('server_apps', function (Blueprint $table) {
            $table->dropColumn('state');
        });

        Schema::table('server_databases', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
}
