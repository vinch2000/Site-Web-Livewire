<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiensController;

// Page d'accueil - Connexion
Route::get('/', [AuthController::class, 'index'])->name('home');

/* 
** Authentification
*/
// Vue inscription
Route::get('/inscription', [AuthController::class, 'inscription'])->name('inscription');
// Enregistrement
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Connexion
Route::post('/login', [AuthController::class, 'login'])->name('login');
// Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* 
** Gestion immobilier
*/
Route::get('/biens-immobilier', [BiensController::class, 'index'])->name('biens.index');

Route::get('/biens-immobilier/editer/{id}', [BiensController::class, 'edit'])->defaults('action', 'editer')->name('biens.edit');
Route::get('/biens-immobilier/ajouter', [BiensController::class, 'edit'])->defaults('action', 'ajouter')->name('biens.ajout');
Route::get('/biens-immobilier/consulter/{id}', [BiensController::class, 'edit'])->defaults('action', 'consulter')->name('biens.consulter');

Route::post('/biens-immobilier/store', [BiensController::class, 'store'])->name('biens.store');
Route::put('/biens-immobilier/update/{id}', [BiensController::class, 'update'])->name('biens.update');