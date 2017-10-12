<?php

use Illuminate\Database\Migrations\Migration;

class DropUnusedTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('activity');
        Schema::dropIfExists('forum_thread_visitations');
        Schema::dropIfExists('sessions');
    }
}
