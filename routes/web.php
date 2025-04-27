<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiculeController;
use App\Http\Controllers\LocationRequestController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ClientMessageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Middleware;

Route::get('/', [AuthController::class, 'index'])->name('index');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.submit');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('auth.forgot-password');
Route::get('/profil', [\App\Http\Controllers\ProfileController::class, 'show'])->name('auth.profil')->middleware('auth');
Route::get('/mes_reservations', [LocationRequestController::class, 'mes_reservations'])->name('mes_reservations')->middleware('auth');
Route::get('/mes_rdv', [LocationRequestController::class, 'mes_rdv'])->name('mes_rdv')->middleware('auth');
Route::get('/mes_missions', [\App\Http\Controllers\MissionController::class, 'mesMissions'])->name('mes_missions')->middleware('auth');

// Routes pour la gestion du profil
Route::middleware(['auth'])->group(function () {
    // Route supprimée car dupliquée avec auth.profil
    Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/password/update', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');
    
    // Routes pour la gestion des documents
    Route::get('/documents/edit/{type}', [\App\Http\Controllers\DocumentController::class, 'edit'])->name('documents.edit');
    Route::post('/documents/update', [\App\Http\Controllers\DocumentController::class, 'update'])->name('documents.update');

    Route::get('/client/messages', [ClientMessageController::class, 'index'])->name('client.messages');
    Route::get('/client/messages/{sujet}', [ClientMessageController::class, 'show'])->name('client.conversation');
    Route::post('/client/messages/{sujet}/reply', [ClientMessageController::class, 'reply'])->name('client.reply');
});


// Route pour les admin
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/', [AuthController::class, 'showadmin'])->name('admin.index');
    Route::get('/stats', [AuthController::class, 'showstats'])->name('admin.stats');
    Route::get('/stats/data/{period}', [\App\Http\Controllers\StatsController::class, 'getStatData'])->name('admin.stats.data');
    Route::get('/depense', [AuthController::class, 'showdepense'])->name('admin.depense');
    Route::get('/benefices', [\App\Http\Controllers\BeneficeController::class, 'index'])->name('admin.benefices');
    Route::post('/benefices/store', [\App\Http\Controllers\BeneficeController::class, 'store'])->name('benefices.store');
    Route::delete('/benefices/{id}', [\App\Http\Controllers\BeneficeController::class, 'destroy'])->name('benefices.destroy');
    
    // Routes API pour les données du graphique des bénéfices
    Route::get('/benefices/data/yearly', [\App\Http\Controllers\BeneficeController::class, 'getYearlyData'])->name('benefices.data.yearly');
    Route::get('/benefices/data/monthly', [\App\Http\Controllers\BeneficeController::class, 'getMonthlyData'])->name('benefices.data.monthly');
    Route::get('/benefices/data/weekly', [\App\Http\Controllers\BeneficeController::class, 'getWeeklyData'])->name('benefices.data.weekly');
    Route::get('/benefices/data/daily', [\App\Http\Controllers\BeneficeController::class, 'getDailyData'])->name('benefices.data.daily');
    Route::get('/benefices/data/hourly', [\App\Http\Controllers\BeneficeController::class, 'getHourlyData'])->name('benefices.data.hourly');
    
    Route::post('/depense/store', [\App\Http\Controllers\DepenseController::class, 'store'])->name('depenses.store');
    Route::put('/depense/{depense}', [\App\Http\Controllers\DepenseController::class, 'update'])->name('depenses.update');
    Route::delete('/depense/{depense}', [\App\Http\Controllers\DepenseController::class, 'destroy'])->name('depenses.destroy');
    
    // Routes API pour les données du graphique des dépenses
    Route::get('/depense/data/yearly', [\App\Http\Controllers\DepenseController::class, 'getYearlyData'])->name('depenses.data.yearly');
    Route::get('/depense/data/monthly', [\App\Http\Controllers\DepenseController::class, 'getMonthlyData'])->name('depenses.data.monthly');
    Route::get('/depense/data/weekly', [\App\Http\Controllers\DepenseController::class, 'getWeeklyData'])->name('depenses.data.weekly');
    Route::get('/depense/data/daily', [\App\Http\Controllers\DepenseController::class, 'getDailyData'])->name('depenses.data.daily');
    
    // User routes - specific routes before parameterized routes
    Route::get('/users/search', [AuthController::class, 'searchUsers'])->name('admin.users.search');
    Route::get('/users', [AuthController::class, 'showusers'])->name('admin.users');
    Route::get('/users-stats', [AuthController::class, 'getUsersStats'])->name('admin.users.stats');
    Route::delete('/users/{id}', [AuthController::class, 'destroyUser'])->name('admin.users.delete');
    Route::post('/users/store', [AuthController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/users/{id}/verify-pieces', [AuthController::class, 'verifyUserPieces'])->name('admin.users.verify_pieces');
    Route::post('/users/{id}/refuse-pieces', [AuthController::class, 'refuseUserPieces'])->name('admin.users.refuse_pieces');
    
    Route::get('/gestionnaire', [AuthController::class, 'showgestionnaire'])->name('admin.gestionnaire');
    Route::post('/gestionnaire/store', [\App\Http\Controllers\GestionnaireController::class, 'store'])->name('admin.gestionnaire.store');
    Route::get('/gestionnaire/search', [\App\Http\Controllers\GestionnaireController::class, 'search'])->name('admin.gestionnaire.search');
    Route::delete('/gestionnaire/{id}', [\App\Http\Controllers\GestionnaireController::class, 'destroy'])->name('admin.gestionnaire.delete');
    Route::patch('/gestionnaire/{id}/update-verification', [\App\Http\Controllers\GestionnaireController::class, 'updateVerification'])->name('admin.gestionnaire.update-verification');
    Route::get('/chauffeur', [AuthController::class, 'showchauffeur'])->name('admin.chauffeur');
    Route::get('/chauffeur/search', [AuthController::class, 'searchChauffeur'])->name('admin.chauffeur.search');
    Route::post('/chauffeur/store', [AuthController::class, 'storeChauffeur'])->name('admin.chauffeur.store');
    Route::delete('/chauffeur/{id}', [AuthController::class, 'destroyChauffeur'])->name('admin.chauffeur.delete');
    Route::get('/vehicules', [AuthController::class, 'showvehicules'])->name('admin.vehicules');
    Route::get('/reservations', [AuthController::class, 'showreservations'])->name('admin.reservations');
    Route::get('/rdv', [AuthController::class, 'showrdv'])->name('admin.rdv');
    Route::get('/rdv/statut/{statut}', [AuthController::class, 'showrdv'])->name('admin.rdv.filter');
    Route::get('/rdv/fin/{fin_rdv}', [AuthController::class, 'showrdv'])->name('admin.rdv.filter.fin');
    Route::get('/rdv/filter/{statut}/{fin_rdv?}', [AuthController::class, 'showrdv'])->name('admin.rdv.combined.filter');
    Route::get('/rdv/{id}', [LocationRequestController::class, 'showRdv'])->name('admin.rdv.show');
    Route::post('/rdv/{id}/confirm', [LocationRequestController::class, 'confirmRdv'])->name('admin.rdv.confirm');
    Route::post('/rdv/{id}/cancel', [LocationRequestController::class, 'cancelRdv'])->name('admin.rdv.cancel');
    Route::post('/rdv/{id}/negociation', [LocationRequestController::class, 'negociationRdv'])->name('admin.rdv.negociation');
    Route::post('/rdv/{id}/complete', [LocationRequestController::class, 'completeRdv'])->name('admin.rdv.complete');
    Route::post('/rdv/{id}/acheter', [LocationRequestController::class, 'acheterRdv'])->name('admin.rdv.acheter');
    Route::post('/rdv/{id}/refuser', [LocationRequestController::class, 'refuserRdv'])->name('admin.rdv.refuser');
    
    // Routes pour la gestion des réservations (admin)
    Route::get('/reservations/{id}', [LocationRequestController::class, 'show'])->name('admin.reservations.show');
    Route::post('/reservations/{id}/approve', [LocationRequestController::class, 'approve'])->name('admin.reservations.approve');
    Route::post('/reservations/{id}/reject', [LocationRequestController::class, 'reject'])->name('admin.reservations.reject');
    Route::post('/reservations/{id}/complete', [LocationRequestController::class, 'complete'])->name('admin.reservations.complete');
    Route::get('/reservations/statut/{statut}', [AuthController::class, 'showreservations'])->name('admin.reservations.filter');
    
    Route::get('/message', [ContactController::class, 'showmessages'])->name('admin.messages');
    Route::post('/message/send', [ContactController::class, 'sendMessage'])->name('admin.send.message');

    // Routes pour les véhicules (admin)
    Route::get('/vehicules/create', [VehiculeController::class, 'create'])->name('admin.vehicules.create');
    Route::post('/vehicules/location', [VehiculeController::class, 'store_location'])->name('vehicules.store_location');
    Route::post('/vehicules/vente', [VehiculeController::class, 'store_vente'])->name('vehicules.store_vente');
    Route::put('/vehicules/{id}', [VehiculeController::class, 'update'])->name('admin.vehicules.update');
    Route::delete('/vehicules/{id}', [VehiculeController::class, 'destroy'])->name('admin.vehicules.delete');
});

// Route pour le formulaire de recherche dynamique
Route::post('/filter-options', [AuthController::class, 'getFilterOptions'])->name('filter.options');

// Test route for user search
Route::get('/test-user-search', [AuthController::class, 'searchUsers'])->name('test.users.search');
Route::get('/simple-search', [AuthController::class, 'simpleUserSearch'])->name('simple.user.search');

// Routes pour le processus de demande de location
Route::middleware(['auth'])->group(function () {
    Route::get('/locations/{vehicule_id}', [LocationRequestController::class, 'create'])->name('location.create');
    Route::post('/location/store', [LocationRequestController::class, 'store'])->name('location.store');
    Route::get('/location/{id}', [LocationRequestController::class, 'show'])->name('location.show');
    Route::get('/rdv/{id}', [LocationRequestController::class, 'show_r'])->name('rdv.show');
    Route::post('/location/{id}/cancel', [LocationRequestController::class, 'cancel'])->name('location.cancel');
    Route::patch('/location/{id}/update-departure', [LocationRequestController::class, 'updateDeparture'])->name('location.update_departure');
});


// Routes pour le processus de prise de RDV
Route::get('/vente/{id}', [LocationRequestController::class, 'showvente'])->name('vente.show');
Route::post('/vente/rdv', [LocationRequestController::class, 'store_rdv'])->name('vente.rdv.store');
Route::post('/vente/rdv/{id}/cancel', [LocationRequestController::class, 'cancel_rdv'])->name('vente.rdv.cancel')->middleware('auth');

// Routes pour le processus de contact
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Routes spécifiques à motors2 qui n'existent pas dans motors1
Route::get('/location/{id}/confirmation', [LocationRequestController::class, 'confirmation'])->name('location.confirmation')->middleware('auth');
Route::get('/location/requests', [LocationRequestController::class, 'userRequests'])->name('location.user_requests')->middleware('auth');
Route::get('/admin/location', [LocationRequestController::class, 'adminIndex'])->name('admin.location.index')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
Route::post('/location/{id}/update-status', [LocationRequestController::class, 'updateStatus'])->name('location.update_status')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);

Route::get('/admin/conversation/{sender_id}', [ContactController::class, 'showConversation'])->name('admin.conversation');
Route::post('/admin/reply/{sender_id}', [ContactController::class, 'reply'])->name('admin.reply');

// Routes pour la messagerie
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [App\Http\Controllers\MessageController::class, 'reply'])->name('messages.reply');
});

// Routes pour la réinitialisation du mot de passe
Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// Routes pour la réinitialisation du mot de passe
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
    ->name('password.update');

