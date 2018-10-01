<?php

use Illuminate\Support\Facades\Route;

// Associatable Resources...
Route::get('/{resource}/associatable/{field}', 'AssociatableController@index');