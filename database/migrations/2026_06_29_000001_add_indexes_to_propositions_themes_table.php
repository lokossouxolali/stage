<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Les colonnes fiche_stage_path, proposition_theme_path, envoye_au_directeur et
        // envoye_a_l_admin ont été ajoutées par erreur dans 'proposition_themes' (mauvais nom).
        // On les ajoute maintenant dans la vraie table 'propositions_themes'.
        Schema::table('propositions_themes', function (Blueprint $table) {
            if (! Schema::hasColumn('propositions_themes', 'fiche_stage_path')) {
                $table->string('fiche_stage_path')->nullable()->after('methodologie');
            }
            if (! Schema::hasColumn('propositions_themes', 'proposition_theme_path')) {
                $table->string('proposition_theme_path')->nullable()->after('fiche_stage_path');
            }
            if (! Schema::hasColumn('propositions_themes', 'envoye_au_directeur')) {
                $table->boolean('envoye_au_directeur')->default(false)->after('proposition_theme_path');
            }
            if (! Schema::hasColumn('propositions_themes', 'envoye_a_l_admin')) {
                $table->boolean('envoye_a_l_admin')->default(false)->after('envoye_au_directeur');
            }
        });

        // Index manquants sur propositions_themes
        $this->addIndexIfMissing('propositions_themes', ['statut']);
        $this->addIndexIfMissing('propositions_themes', ['envoye_au_directeur']);
        $this->addIndexIfMissing('propositions_themes', ['date_soumission']);
        $this->addIndexIfMissing('propositions_themes', ['directeur_memoire_id', 'envoye_au_directeur']);

        // Index manquants sur candidatures
        $this->addIndexIfMissing('candidatures', ['etudiant_id']);
        $this->addIndexIfMissing('candidatures', ['date_candidature']);

        // Index composite notifications (couvre les 3 requêtes fréquentes du topbar)
        $this->addIndexIfMissing('notifications', ['user_id', 'lu', 'created_at']);
    }

    public function down(): void
    {
        $this->dropIndexIfExists('notifications', ['user_id', 'lu', 'created_at']);
        $this->dropIndexIfExists('candidatures', ['date_candidature']);
        $this->dropIndexIfExists('candidatures', ['etudiant_id']);
        $this->dropIndexIfExists('propositions_themes', ['directeur_memoire_id', 'envoye_au_directeur']);
        $this->dropIndexIfExists('propositions_themes', ['date_soumission']);
        $this->dropIndexIfExists('propositions_themes', ['envoye_au_directeur']);
        $this->dropIndexIfExists('propositions_themes', ['statut']);

        Schema::table('propositions_themes', function (Blueprint $table) {
            $table->dropColumn(['fiche_stage_path', 'proposition_theme_path', 'envoye_au_directeur', 'envoye_a_l_admin']);
        });
    }

    private function addIndexIfMissing(string $table, array $columns): void
    {
        $indexName = $this->indexName($table, $columns);
        if ($this->indexExists($table, $indexName)) {
            return;
        }
        Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName) {
            $blueprint->index($columns, $indexName);
        });
    }

    private function dropIndexIfExists(string $table, array $columns): void
    {
        $indexName = $this->indexName($table, $columns);
        if (! $this->indexExists($table, $indexName)) {
            return;
        }
        Schema::table($table, function (Blueprint $blueprint) use ($indexName) {
            $blueprint->dropIndex($indexName);
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
        return $table.'_'.implode('_', $columns).'_idx';
    }
};
