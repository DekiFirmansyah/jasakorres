<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::group(['middleware' => 'auth', 'namespace' => 'App\Http\Controllers'], function () {
	Route::resource('user', UserController::class);
    Route::resource('letters', LetterController::class);
    Route::resource('notebooks', NotebookController::class);
	Route::controller(NotebookController::class)->group(function () {
        Route::get('notebooks/filter', 'filter')->name('notebooks.filter');
        Route::get('/export-pdf', 'exportPDF')->name('notebooks.export_pdf');
        Route::get('/export_excel', 'exportExcel')->name('notebooks.export_excel');
        Route::post('/notebooks/select-letters', 'selectLetters')->name('notebooks.selectLetters');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('users/{id}/edit-password', 'editPassword')->name('admin.users.editPassword');
        Route::put('users/{id}/update-password', 'updatePassword')->name('admin.users.updatePassword');
    });
    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'edit')->name('profile.edit');
        Route::put('profile', 'update')->name('profile.update');
		Route::put('profile/password', 'password')->name('profile.password');
    });
    Route::controller(ValidationController::class)->group(function () {
        Route::get('/validations', 'index')->name('validations.index');
        Route::get('/letter-valid', 'letterValid')->name('validations.valid');
        Route::put('/validations/{letter}/validate', 'validateLetter')->name('validations.validate');
        Route::post('/validations/{document}/update-code', 'updateCode')->name('letters.updateCode');
    });
    Route::controller(NotificationController::class)->group(function () {
        Route::post('/notifications/mark-as-read', 'markAsRead')->name('notifications.markAsRead');
    });
    Route::controller(ArchiveController::class)->group(function () {
        Route::get('archives', 'index')->name('archives.index');
        Route::get('archives/{letter}/edit', 'edit')->name('archives.edit');
        Route::put('archives/{letter}/update', 'update')->name('archives.update');
        Route::get('archives/{id}/title', 'getTitle')->name('archives.getTitle');

    });
    
	Route::get('{page}', 'PageController@index')->name('page.index');
});