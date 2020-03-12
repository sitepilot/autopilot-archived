<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAutoIncrementOfResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update("ALTER TABLE users AUTO_INCREMENT = " . env('APP_START_ID', 1) . ";");
        DB::update("ALTER TABLE clients AUTO_INCREMENT = " . env('APP_START_ID', 1) . ";");
        DB::update("ALTER TABLE projects AUTO_INCREMENT = " . env('APP_START_ID', 1) . ";");
        DB::update("ALTER TABLE project_hours AUTO_INCREMENT = " . env('APP_START_ID', 1) . ";");
        DB::update("ALTER TABLE server_groups AUTO_INCREMENT = " . env('APP_START_ID', 1) . ";");
        DB::update("ALTER TABLE server_hosts AUTO_INCREMENT = " . env('APP_START_ID', 1) . ";");
        DB::update("ALTER TABLE server_users AUTO_INCREMENT = " . env('APP_START_ID', 1) . ";");
        DB::update("ALTER TABLE server_databases AUTO_INCREMENT = " . env('APP_START_ID', 1) . ";");
        DB::update("ALTER TABLE server_apps AUTO_INCREMENT = " . env('APP_START_ID', 1) . ";");
        DB::update("ALTER TABLE server_auth_keys AUTO_INCREMENT = " . env('APP_START_ID', 1) . ";");
        DB::update("ALTER TABLE server_firewall_rules AUTO_INCREMENT = " . env('APP_START_ID', 1) . ";");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
