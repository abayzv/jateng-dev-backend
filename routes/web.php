<?php

use App\Events\AnalyticsEvent;
use App\Livewire\Addresses\Show;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use Illuminate\Support\Facades\Log;

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
    $event = [
        "message" => "user logged in"
    ];
    AnalyticsEvent::dispatch($event);

    return view('welcome');
});

Route::get('/api/documentation', function () {
    return view('docs.index');
});

Route::get('/user-leave', function (Request $request) {
    $userSession = $request->timeSpent;
    $message = "user left after {$userSession} seconds";
    $event = [
        "message" => $message
    ];
    AnalyticsEvent::dispatch($event);
});
