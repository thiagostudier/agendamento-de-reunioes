<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewToMeetingsTable extends Migration
{
    public function up()
    {
        Schema::table('meetings', function (Blueprint $table) {
            //
            $table->boolean('new');
        });
    }

    public function down()
    {
        Schema::table('meetings', function (Blueprint $table) {
            //
        });
    }
}
