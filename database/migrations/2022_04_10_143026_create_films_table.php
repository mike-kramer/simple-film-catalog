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
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string("title", 500);
            $table->integer("year")->index();
            $table->integer("rate")->index();
            $table->string("cover")->nullable();
            $table->string("source")->nullable();
            $table->string("torrent_file")->nullable();
            $table->string("country_id", 6);
            $table->text("description");
            $table->foreignId("status_id")->constrained();
            $table->string("type", 20)->index();
            $table->timestamps();
        });

        Schema::create("film_genre", function (Blueprint $table) {
            $table->foreignId("film_id")->constrained()->cascadeOnDelete();
            $table->foreignId("genre_id")->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('films');
        Schema::dropIfExists('film_genre');
    }
};
