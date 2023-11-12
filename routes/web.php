<?php

use App\Livewire\Addresses\Show;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/documentation', function () {
    return view('docs.index');
});


Route::get('/test-notif', function () {
    $admin = App\Models\User::find(1);
    $admin->notify(
        Notification::make()
            ->title('New User')
            ->body('A new user has registered.')
            ->success()
            ->send()
            ->toDatabase()
    );
});
