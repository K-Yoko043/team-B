<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('goriller_id')->nullable()->comment('投稿者');
            $table->string('tag')->nullable()->comment('タグ');
            $table->string('title')->nullable()->comment('タイトル');
            $table->string('content_text')->nullable()->comment('投稿内容');
            $table->unsignedinteger('position')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents');
    }
}
