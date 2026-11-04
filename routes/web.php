<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\LocataireController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialActivityController;
use App\Http\Controllers\ContactController; // 👈 Importation du contrôleur de contact
use App\Http\Controllers\SiteLogoController;
use App\Http\Controllers\SitePageImageController;
use App\Support\SitePageImages;
use App\Models\House;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $houses = House::where('status', 'disponible')->latest()->get();
    return view('welcome', compact('houses'));
})->name('home');

// Nouvelles pages séparées
Route::get('/services', function () {
    return view('services', ['serviceImages' => SitePageImages::forPage('services')]);
})->name('services');

Route::get('/apropos', function () {
    return view('apropos', ['aboutImages' => SitePageImages::forPage('apropos')]);
})->name('apropos');

// 👇 Routes pour la page de contact et le traitement du formulaire (GET et POST)
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Tableau de bord redirigé vers le DashboardController
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/dashboard/{section}', [DashboardController::class, 'index'])
    ->whereIn('section', ['reservations', 'locations', 'paiements'])
    ->middleware(['auth', 'verified'])
    ->name('client.dashboard.section');

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    Route::get('/annual-report', [FinancialActivityController::class, 'index'])->name('annual-report.index');
    Route::post('/annual-report', [FinancialActivityController::class, 'store'])->name('annual-report.store');

    Route::get('/settings/logo', [SiteLogoController::class, 'edit'])->name('site-logo.edit');
    Route::post('/settings/logo', [SiteLogoController::class, 'update'])->name('site-logo.update');
    Route::get('/settings/page-images', [SitePageImageController::class, 'edit'])->name('site-page-images.edit');
    Route::post('/settings/page-images', [SitePageImageController::class, 'update'])->name('site-page-images.update');

    // Gestion du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des maisons (CRUD complet)
    Route::resource('houses', HouseController::class);

    // Route permettant à un client connecté de choisir/réserver une maison depuis la page d'accueil
    Route::post('/houses/{house}/choisir', [HouseController::class, 'clientChoisir'])->name('houses.client.choisir');

    // Gestion des locataires (CRUD complet)
    Route::resource('locataires', LocataireController::class);

    // Routes spécifiques pour l'attribution des maisons à un locataire
    Route::get('locataires/{locataire}/attribuer', [LocataireController::class, 'attribuerMaisonForm'])->name('locataires.attribuer.form');
    Route::post('locataires/{locataire}/attribuer', [LocataireController::class, 'attribuerMaison'])->name('locataires.attribuer');

    // Gestion des utilisateurs (CRUD complet pour l'administration)
    Route::resource('users', UserController::class);

    // Gestion complète de la Caisse
    Route::resource('payments', PaymentController::class);
    
    // Route spécifique pour l'impression du reçu de loyer
    Route::get('payments/{payment}/receipt', [PaymentController::class, 'show'])->name('payments.receipt');
});

require __DIR__.'/auth.php';