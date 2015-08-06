<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translations', function(Blueprint $table)
        {
            $table->engine = 'MyISAM';

            // Primary key.
            $table->increments('id')->primary();

            // Related definition.
            $table->integer('definition_id')->length(9)->unsigned();
            $table->foreign('definition_id')
                ->references('id')->on('definitions')
                ->onDelete('cascade');

            // Translations
            $table->string('language', 3)->index(); // Main language (not expecting sub-languages here).
            $table->text('translation');            // Actual translation.
            $table->text('literal');                // Literal translation.
            $table->text('meaning');                // Elaboration on the meaning of the definition.

            $table->timestamps();
        });

        DB::statement('ALTER TABLE translations ADD FULLTEXT idx_fulltext(translation, literal, meaning)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('translations');
    }
}

