<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('project_hours', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('server_apps', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('server_auth_keys', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('server_databases', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('server_firewall_rules', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('server_groups', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('server_hosts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('server_users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
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
        Schema::table('clients', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('project_hours', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('server_apps', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('server_auth_keys', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('server_databases', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('server_firewall_rules', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('server_groups', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('server_hosts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('server_users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
