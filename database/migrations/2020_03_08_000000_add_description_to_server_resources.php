<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToServerResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_groups', function (Blueprint $table) {
            $table->text('description')->after('name')->nullable();
        });

        Schema::table('server_hosts', function (Blueprint $table) {
            $table->text('description')->after('name')->nullable();
        });

        Schema::table('server_users', function (Blueprint $table) {
            $table->text('description')->after('name')->nullable();
        });

        Schema::table('server_apps', function (Blueprint $table) {
            $table->text('description')->after('name')->nullable();
        });

        Schema::table('server_databases', function (Blueprint $table) {
            $table->text('description')->after('name')->nullable();
        });

        Schema::table('server_firewall_rules', function (Blueprint $table) {
            $table->text('description')->after('name')->nullable();
        });

        Schema::table('server_auth_keys', function (Blueprint $table) {
            $table->text('description')->after('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('server_groups', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('server_hosts', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('server_users', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('server_apps', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('server_databases', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('server_firewall_rules', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('server_auth_keys', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
