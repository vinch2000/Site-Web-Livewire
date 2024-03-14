<?php

use App\Livewire\BienListing;
use App\Livewire\BienCrud;
use App\Livewire\UserConnection;
use App\Livewire\UserInscription;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Cette page home permet de vérifier si l'utilisateur est auth ou pas
// Et on redirige en fonction vers la connection ou vers la page index immo
Route::get('/', [AuthController::class, 'index'])->name('home');

// On place les routes de connection et d'inscription dans un middleware Guest
// qui est fournit par défaut avec Laravel et permet de s'assurer que l'utilisateur n'est pas connecté
Route::group(['middleware' => ['guest']], function () {
    Route::get('/connection', UserConnection::class)->name('connection');
    Route::get('/inscription', UserInscription::class)->name('inscription');
});

// On place ici les routes de l'utilisateur connecté et dont le rôle est celui qu'on a créé pour eux (ici utilisateur)
// Voir dans database/seeders/PermissionSeeder & RoleSeeder
Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['role:utilisateur']], function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/biens-immobilier', BienListing::class)->name('biens.index');

        Route::get('/biens-immobilier/ajouter', BienCrud::class)->defaults('action', 'ajouter')->name('biens.ajout');
        Route::get('/biens-immobilier/editer/{id}', BienCrud::class)->defaults('action', 'editer')->name('biens.edit');
        Route::get('/biens-immobilier/consulter/{id}', BienCrud::class)->defaults('action', 'consulter')->name('biens.consulter');
    });
});