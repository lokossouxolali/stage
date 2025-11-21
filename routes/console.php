<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Commandes personnalisées pour la gestion de stages
Artisan::command('stages:cleanup', function () {
    $this->info('Nettoyage des stages expirés...');
    // Logique de nettoyage des stages expirés
    $this->info('Nettoyage terminé.');
})->purpose('Nettoyer les stages expirés');

Artisan::command('stages:notify', function () {
    $this->info('Envoi des notifications de stages...');
    // Logique d'envoi de notifications
    $this->info('Notifications envoyées.');
})->purpose('Envoyer les notifications de stages');

Artisan::command('stages:backup', function () {
    $this->info('Sauvegarde des données de stages...');
    // Logique de sauvegarde
    $this->info('Sauvegarde terminée.');
})->purpose('Sauvegarder les données de stages');

Artisan::command('stages:generate-reports', function () {
    $this->info('Génération des rapports de stages...');
    // Logique de génération de rapports
    $this->info('Rapports générés.');
})->purpose('Générer les rapports de stages');

Artisan::command('stages:send-reminders', function () {
    $this->info('Envoi des rappels de stages...');
    // Logique d'envoi de rappels
    $this->info('Rappels envoyés.');
})->purpose('Envoyer les rappels de stages');

Artisan::command('stages:update-status', function () {
    $this->info('Mise à jour des statuts de stages...');
    // Logique de mise à jour des statuts
    $this->info('Statuts mis à jour.');
})->purpose('Mettre à jour les statuts de stages');

// Tâches planifiées
Schedule::command('stages:cleanup')->daily();
Schedule::command('stages:notify')->hourly();
Schedule::command('stages:backup')->weekly();
Schedule::command('stages:generate-reports')->monthly();
Schedule::command('stages:send-reminders')->daily();
Schedule::command('stages:update-status')->everyMinute();