<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('title', 100);
            $table->string('description');
            $table->enum('difficulty', ['easy', 'normal', 'hard']);
            $table->integer('prep_time', false, true)
                ->comment('The time it takes to cook the recipe');
            $table->string('prep_time_uom')
                ->comment('The unit of measure of the prep_time. Like hours or minutes.');
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
        Schema::dropIfExists('recipes');
    }
};
