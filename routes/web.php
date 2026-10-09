<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController; // 1. Ajoutez cet import ici
use App\Models\House;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Nouvelles pages séparées
Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/apropos', function () {
    return view('apropos');
})->name('apropos');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Tableau de bord dynamique avec statistiques
Route::get('/dashboard', function () {
    $totalHouses = House::count();
    $availableHouses = House::where('status', 'disponible')->count();
    $totalTenants = User::where('role', 'client')->count();
    $houses = House::latest()->take(5)->get();

    return view('dashboard', compact('totalHouses', 'availableHouses', 'totalTenants', 'houses'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    // Gestion du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des maisons (CRUD complet)
    Route::resource('houses', HouseController::class);

    // Gestion des locataires / clients (CRUD complet)
    Route::resource('tenants', TenantController::class);

    // Gestion des utilisateurs (CRUD complet pour l'administration)
    Route::resource('users', UserController::class); // 2. Ajoutez cette route ici
});

require __DIR__.'/auth.php';