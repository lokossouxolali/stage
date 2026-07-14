<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposition_themes', function (Blueprint $table) {
            if (!Schema::hasColumn('proposition_themes', 'fiche_stage_path')) {
                $table->string('fiche_stage_path')->nullable()->after('methodologie');
            }
            if (!Schema::hasColumn('proposition_themes', 'proposition_theme_path')) {
                $table->string('proposition_theme_path')->nullable()->after('fiche_stage_path');
            }
            if (!Schema::hasColumn('proposition_themes', 'envoye_au_directeur')) {
                $table->boolean('envoye_au_directeur')->default(false)->after('proposition_theme_path');
            }
            if (!Schema::hasColumn('proposition_themes', 'envoye_a_l_admin')) {
                $table->boolean('envoye_a_l_admin')->default(false)->after('envoye_au_directeur');
            }
        });

        $this->addIndexIfMissing('users', ['role']);
        $this->addIndexIfMissing('users', ['statut_inscription']);
        $this->addIndexIfMissing('users', ['est_actif']);
        $this->addIndexIfMissing('offres', ['statut']);
        $this->addIndexIfMissing('offres', ['type_stage']);
        $this->addIndexIfMissing('offres', ['date_limite_candidature']);
        $this->addIndexIfMissing('candidatures', ['statut']);
        $this->addIndexIfMissing('notifications', ['user_id', 'lu']);
        $this->addIndexIfMissing('notifications', ['created_at']);
        $this->addIndexIfMissing('proposition_themes', ['statut']);
        $this->addIndexIfMissing('proposition_themes', ['envoye_au_directeur']);
    }

    public function down(): void
    {
        $this->dropIndexIfExists('proposition_themes', ['envoye_au_directeur']);
        $this->dropIndexIfExists('proposition_themes', ['statut']);
        $this->dropIndexIfExists('notifications', ['created_at']);
        $this->dropIndexIfExists('notifications', ['user_id', 'lu']);
        $this->dropIndexIfExists('candidatures', ['statut']);
        $this->dropIndexIfExists('offres', ['date_limite_candidature']);
        $this->dropIndexIfExists('offres', ['type_stage']);
        $this->dropIndexIfExists('offres', ['statut']);
        $this->dropIndexIfExists('users', ['est_actif']);
        $this->dropIndexIfExists('users', ['statut_inscription']);
        $this->dropIndexIfExists('users', ['role']);
    }

    private function addIndexIfMissing(string $table, array $columns): void
    {
        $indexName = $this->indexName($table, $columns);

        if ($this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $tableBlueprint) use ($columns, $indexName) {
            $tableBlueprint->index($columns, $indexName);
        });
    }

    private function dropIndexIfExists(string $table, array $columns): void
    {
        $indexName = $this->indexName($table, $columns);

        if (!$this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $tableBlueprint) use ($indexName) {
            $tableBlueprint->dropIndex($indexName);
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        if (DB::getDriverName() === 'sqlite') {
            return collect(DB::select("PRAGMA index_list('$table')"))
                ->contains(fn ($index) => $index->name === $indexName);
        }

        $database = DB::getDatabaseName();

        return DB::table('information_schema.statistics')
            ->where('table_schema', $database)
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
    }

    private function indexName(string $table, array $columns): string
    {
        return $table . '_' . implode('_', $columns) . '_idx';
    }
};
