<?php

use Illuminate\Support\Facades\Route;
use Spatie\CoffeeTile\Http\CoffeeController;

Route::post('/coffee', CoffeeController::class);
