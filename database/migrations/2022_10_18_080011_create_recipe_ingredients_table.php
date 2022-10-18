<?php

use App\Models\Ingredient;
use App\Models\Recipe;
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
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->foreignIdFor(Recipe::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Ingredient::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->integer('quantity', false, true)
                ->default(0);
            $table->boolean('optional')
                ->default(false)
                ->comment('If the ingredient is not always needed.');
            $table->boolean('something')
                ->default(false)
                ->comment('If the quantity is just a little bit.');
            $table->timestamps();
            $table->primary(['recipe_id', 'ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
