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
        Schema::create('page_likes', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('page_id')->constrained()
                ->onDeleteCascade()
                ->onUpdateCascade();

            $table->foreignId('user_id')->constrained()
                ->onDeleteCascade()
                ->onUpdateCascade();

            $table->unique(['page_id', 'user_id']);

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
        Schema::dropIfExists('page_likes');
    }
};
