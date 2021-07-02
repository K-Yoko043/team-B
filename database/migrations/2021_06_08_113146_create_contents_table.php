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
            $table->unsignedBigInteger('user_id')->nullable()->comment('投稿者');
            $table->string('user_name')->nullable();
            $table->string('tag')->nullable()->comment('タグ');
            $table->text('content_text')->nullable()->comment('投稿内容');
            $table->longText('comments')->nullable()->comment('コメント内容');
            $table->unsignedinteger('position')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE contents ADD FULLTEXT index content_text (`content_text`) with parser ngram');
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
