<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'locations' => LocationController::class,
    'events' => EventController::class,
]);
