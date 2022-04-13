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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string("status_name");
            $table->integer("order");
        });

        $statuses = [
            "Хочу просмотреть",
            "Смотрю",
            "Бросил",
            "Просмотрел"
        ];
        foreach ($statuses as $index => $status) {
            \Illuminate\Support\Facades\DB::table("statuses")->insert([
                "status_name" => $status,
                "order" => $index
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
};
