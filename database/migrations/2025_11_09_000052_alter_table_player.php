<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Player;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {

            // 1. Renomeia a coluna
            $table->renameColumn('currentChapter', 'currentStoryNode');

            // 2. MUDA o tipo da coluna (que agora se chama currentStoryNode)
            // Você precisa do doctrine/dbal para isso
            $table->unsignedBigInteger('currentStoryNode')->nullable()->change();

            // 3. ADICIONA a chave estrangeira
            $table->foreign('currentStoryNode') // Nome da coluna na tabela 'players'
                  ->references('id')
                  ->on('story_nodes') // <--- APONTA PARA A TABELA CORRETA
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {

            // O 'down' deve reverter o 'up' na ordem INVERSA

            // 1. Remove a chave estrangeira
            // O Laravel cria o nome assim: [tabela]_[coluna]_foreign
            $table->dropForeign(['currentStoryNode']); // ou $table->dropForeign('players_currentStoryNode_foreign');

            // 2. Muda o tipo da coluna de volta
            // (Assumindo que era um 'integer' simples e não nulo)
            $table->integer('currentStoryNode')->nullable(false)->change();

            // 3. Renomeia de volta
            $table->renameColumn('currentStoryNode', 'currentChapter');
        });
    }
};
