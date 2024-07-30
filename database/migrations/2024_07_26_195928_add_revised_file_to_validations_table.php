<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('validations', function (Blueprint $table) {
            $table->string('revised_file')->nullable()->after('notes');
        });
    }

    public function down()
    {
        Schema::table('validations', function (Blueprint $table) {
            $table->dropColumn('revised_file');
        });
    }
};