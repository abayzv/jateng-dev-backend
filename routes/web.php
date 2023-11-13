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

Route::get('/', function (Request $request) {
    // get session time
    // $startTime = $request->session()->get('start_time');
    // $endTime = now();
    // $timeSpent = $endTime->diffInSeconds($startTime);
    // dd($timeSpent);

    // get IP address
    $ip = $request->ip();
    $userAgent = $request->userAgent();
    dd($userAgent);

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
    // get session time
    $startTime = $request->session()->get('start_time');
    $endTime = now();
    $timeSpent = $endTime->diffInSeconds($startTime);
    $message = "user left after $timeSpent seconds";

    $event = [
        "message" => $message
    ];
    AnalyticsEvent::dispatch($event);
});
