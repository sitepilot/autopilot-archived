<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServerFirewallRulesTable  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_firewall_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->mediumText('vars')->nullable();
            $table->timestamps();
        });

        Schema::create('server_firewall_rule_host', function (Blueprint $table) {
            $table->unsignedBigInteger('rule_id');
            $table->unsignedBigInteger('host_id');

            $table->foreign('rule_id')
                ->references('id')->on('server_firewall_rules')
                ->onDelete('cascade');

            $table->foreign('host_id')
                ->references('id')->on('server_hosts')
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
        Schema::dropIfExists('server_firewall_rule_host');
        Schema::dropIfExists('server_firewall_rules');
    }
}
