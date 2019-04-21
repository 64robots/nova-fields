<?php

use Illuminate\Support\Facades\Route;

// Associatable Resources...
Route::get('/{resource}/associatable/{field}', 'AssociatableController@index');

// Computed field
Route::post('/{resource}/computed/{field}', 'ComputedController@index');
