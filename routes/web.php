<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthenticatedUserController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'home')->name('home');
Route::get('/admin', [AuthenticatedUserController::class, 'home_admin'])->name('home_admin')->middleware('auth')
                                                                            ->middleware('is_admin');
Route::get('/acceuil', [AuthenticatedUserController::class, 'acceuil'])->name('acceuil');

Route::middleware('auth')->group(function() {
    Route::middleware('has_type')->group(function() {
        //MDOFICATION UTILISATEUR
        Route::get('/user/password', [AuthenticatedUserController::class, 'newPassword'])->name('user.password');
        Route::put('/user/password', [AuthenticatedUserController::class, 'updatePassword'])->name('user.update_password');
        Route::get('/user/name', [AuthenticatedUserController::class, 'newName'])->name('user.name');
        Route::put('/user/name', [AuthenticatedUserController::class, 'updateName'])->name('user.update_name');

        //PARTIE ADMIN
        Route::middleware('is_admin')->group(function () {
            Route::get('admin/user/accept/{id}', [AdminController::class, 'acceptUser'])->name('user.accept');
            Route::get('/admin/user/reject/{id}', [AdminController::class, 'rejectUser'])->name('user.reject');
            //Route pour les differents listes
            Route::get('/admin/user/attente/index', [AdminController::class, 'AttenteIndex'])->name('admin.attente');
            Route::get('admin/user/index', [AdminController::class, 'indexUser'])->name('user.index');
            Route::get('admin/user/index/enseignants', [AdminController::class, 'enseignantIndex'])->name('enseignant.index');
            Route::get('admin/user/index/etudiants', [AdminController::class, 'etudiantIndex'])->name('etudiant.index');
            Route::post('admin/user/search', [AdminController::class, 'userSearch'])->name('user.search');
            //Modification utiisateur par l'admin
            Route::get('admin/user/create', [AdminController::class, 'createUser'])->name('user.create');
            Route::post('admin/user/create', [AdminController::class, 'storeUser'])->name('user.store');
            Route::get('admin/user/modify/{id}', [AdminController::class, 'modifyUser'])->name('user.modify');
            Route::put('admin/user/update/{id}', [AdminController::class, 'updateUser'])->name('user.update');
            Route::get('admin/user/delete/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');
            Route::post('admin/associate/{id_enseignant}', [AdminController::class, 'CoursEnseignants'])->name('cours.enseignants');
            //COURS
            Route::get('cours/index', [CoursController::class, 'index'])->name('cours.index');
            Route::post('cours/index/search', [CoursController::class, 'search'])->name('cours.search');
            Route::get('cours/create', [CoursController::class, 'create'])->name('cours.create');
            Route::post('cours/create', [CoursController::class, 'store'])->name('cours.store');
            Route::get('cours/modify/{id}', [CoursController::class, 'modify'])->name('cours.modify');
            Route::post('cours/update/{id}', [CoursController::class, 'update'])->name('cours.update');
            Route::get('cours/delete/{id}', [CoursController::class, 'delete'])->name('cours.delete');
            Route::post('cours/associate/enseignant/{id_cours}', [CoursController::class, 'CoursEnseignants'])->name('enseignants.cours');
            Route::post('cours/enseignant/index/', [CoursController::class, 'coursEnseignantIndex'])->name('cours.enseignants_index');
            //Formation
            Route::get('formation/index', [FormationController::class, 'index'])->name('formation.index');
            Route::post('formation/store', [FormationController::class, 'store'])->name('formation.store');
            Route::get('formation/modify/{id}', [FormationController::class, 'modify'])->name('formation.modify');
            Route::post('formation/modify/{id}', [FormationController::class, 'update'])->name('formation.update');
            Route::get('formation/delete/{id}', [FormationController::class, 'delete'])->name('formation.delete');
        });

        //ETUDIANTS
        Route::get('user/cours/formations/index', [EtudiantController::class, 'indexCoursFormation'])->name('cours.formation_index');
        Route::get('user/cours/inscription/{id}', [EtudiantController::class, 'inscription'])->name('cours.inscription');
        Route::get('user/cours/desinscription/{id}', [EtudiantController::class, 'desinscription'])->name('cours.desinscription');
        Route::get('user/cours/inscrit/index', [EtudiantController::class, 'inscritIndex'])->name('cours.inscrit');
        Route::post('user/cours/formation/search', [EtudiantController::class, 'search'])->name('cours_formation.search');


        //ENSEIGNANTS
        Route::get('user/enseignant/cours/index', [CoursController::class, 'CoursEnseignant'])->name('cours.enseignant');

        //PLANNING
        Route::get('user/cours/planning', [PlanningController::class, 'affichage'])->name('planning.affichage');
        Route::get('user/cours/planning/filtre_cours', [PlanningController::class, 'filtreCours'])->name('affichage.cours_filtre');
        Route::get('user/cours/planning/filtre', [PlanningController::class, 'filtreSemaine'])->name('planning.filtre_semaine');
        Route::get('user/cours/planning/view/{id}', [PlanningController::class, 'viewPlanning'])->name('planning.view');
        Route::get('user/enseignant/planning/create', [PlanningController::class, 'create'])->name('planning.create');
        Route::post('user/enseignant/planning/store', [PlanningController::class, 'store'])->name('planning.store');
        Route::get('user/enseignant/planning/modify/{id}', [PlanningController::class, 'modify'])->name('planning.modify');
        Route::post('user/enseignant/planning/update/{id}', [PlanningController::class, 'update'])->name('planning.update');
        Route::get('user/enseignant/planning/delete/{id}', [PlanningController::class, 'delete'])->name('planning.delete');
    });
});

Route::get('/user/register', [RegisterUserController::class, 'registerForm'])->name('register');
Route::post('/user/register', [RegisterUserController::class, 'register'])->name('user.register');
Route::get('/user/login', [AuthenticatedUserController::class, 'loginForm'])->name('login');
Route::post('/user/login', [AuthenticatedUserController::class, 'login'])->name('user.login')->middleware('throttle:auth');
Route::get('/user/logout', [AuthenticatedUserController::class, 'logout'])->name('logout');

