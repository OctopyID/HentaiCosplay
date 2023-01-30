<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique();
            $table->string('url');
            $table->boolean('status')->default(false);
            $table->foreignId('sub_page_id')->constrained('sub_pages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('images');
    }
};
