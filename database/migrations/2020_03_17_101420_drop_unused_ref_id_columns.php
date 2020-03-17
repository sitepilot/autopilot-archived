<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedRefIdColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('refid');
        });

        Schema::table('server_users', function (Blueprint $table) {
            $table->dropColumn('refid');
        });
        
        Schema::table('server_hosts', function (Blueprint $table) {
            $table->dropColumn('refid');
        });

        Schema::table('server_apps', function (Blueprint $table) {
            $table->dropColumn('refid');
        });

        Schema::table('server_auth_keys', function (Blueprint $table) {
            $table->dropColumn('refid');
        });

        Schema::table('server_databases', function (Blueprint $table) {
            $table->dropColumn('refid');
        });

        Schema::table('server_firewall_rules', function (Blueprint $table) {
            $table->dropColumn('refid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('refid', 50)->after('id')->unique()->nullable()->index();
        });

        Schema::table('server_hosts', function (Blueprint $table) {
            $table->string('refid', 50)->after('id')->unique()->nullable()->index();
        });

        Schema::table('server_apps', function (Blueprint $table) {
            $table->string('refid', 50)->after('id')->unique()->nullable()->index();
        });

        Schema::table('server_users', function (Blueprint $table) {
            $table->string('refid', 50)->after('id')->unique()->nullable()->index();
        });

        Schema::table('server_auth_keys', function (Blueprint $table) {
            $table->string('refid', 50)->after('id')->unique()->nullable()->index();
        });

        Schema::table('server_databases', function (Blueprint $table) {
            $table->string('refid', 50)->after('id')->unique()->nullable()->index();
        });

        Schema::table('server_firewall_rules', function (Blueprint $table) {
            $table->string('refid', 50)->after('id')->unique()->nullable()->index();
        });
    }
}
