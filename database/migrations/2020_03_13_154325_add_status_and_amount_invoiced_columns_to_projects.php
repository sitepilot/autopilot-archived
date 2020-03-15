<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndAmountInvoicedColumnsToProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('budget', 'offer');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->float('invoiced')->nullable()->after('offer');
            $table->string('state', 50)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('offer', 'budget');
            $table->dropColumn('invoiced');
            $table->dropColumn('state');
        });
    }
}
