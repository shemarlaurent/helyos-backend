<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AbyssForumAbyssUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abyss_forum_abyss_user', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('abyss_user_id');
            $table->unsignedBigInteger('abyss_forum_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abyss_forum_abyss_user');
    }
}
