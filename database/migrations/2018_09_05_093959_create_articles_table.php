<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->comment('标题');
            $table->text('content')->comment('正文');
            $table->integer('view')->unsigned()->default(0)->comment('浏览次数');
            $table->string('category', 255)->comment('分类的名称, 每篇文章只能有一个分类');
            $table->text('summary')->comment('概述');
            $table->string('slug', 255)->comment('标题别名, 用于url, 英文单词, 用-分隔');

            $table->timestamps();
        });

        DB::statement("ALTER TABLE `articles` comment '文章表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
