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
        Schema::create(config('laravel-audit.db_prefix') . 'actions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Create, Update, Delete, etc.
            $table->foreignId('actionable_id'); // Modelo sobre el que se está realizando la acción
            $table->string('actionable_type'); 
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
    }
};
