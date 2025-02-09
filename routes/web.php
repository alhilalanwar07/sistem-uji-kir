<?php

use Illuminate\Support\Facades\{Route, Auth};

// disable register, reset password
Auth::routes(['register' => false, 'reset' => false]);

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::view('kendaraan', 'kendaraan')->name('kendaraan');
    Route::view('dashboard', 'dashboard')->name('home');
    Route::view('manajemen-user', 'manajemen-user')->name('admin.manajemen-user');
    Route::view('profil', 'profil')->name('profil');
    Route::view('kriteria', 'kriteria')->name('kriteria');
    Route::view('uji', 'uji')->name('uji');
    Route::view('parameter', 'parameter')->name('parameter');
    Route::view('hasiluji', 'hasiluji')->name('hasiluji');
    Route::view('aturan', 'aturan')->name('aturan');
});