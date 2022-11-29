<?php

use App\Models\Recipe;
use App\Models\Times;
use App\Models\TimesUnit;
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
        Schema::create('recipe_times', function (Blueprint $table) {
            $table->foreignIdFor(Recipe::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Times::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(TimesUnit::class)->constrained()->cascadeOnDelete();
            $table->double('duration', null, 3);
            $table->primary(['recipe_id', 'times_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_times');
    }
};
