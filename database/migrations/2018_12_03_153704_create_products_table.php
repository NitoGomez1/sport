<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('product_level_id')->nullable();
            $table->unsignedInteger('dkt_id')->unique()->nullable();
            $table->string('name')->unique();
            $table->text('description');
            $table->string('source')->unique()->nullable();
            $table->string('image');
            $table->decimal('price', 6, 2)->nullable();
            $table->string('gtin', 13)->unique()->nullable();
            $table->string('color', 20)->nullable();
            $table->string('size', 20)->nullable();
            $table->string('material', 20)->index();
            $table->string('supermodel', 20)->nullable()->index();
            $table->unsignedInteger('id_article')->nullable();
            $table->unsignedInteger('review_count')->default(0);
            $table->string('product_md5');
            $table->boolean('is_prototype')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->foreign('product_level_id')
                ->references('id')
                ->on('categories')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
