<?php

use Illuminate\Support\Facades\Route;
use R64\NovaFields\Http\Controllers\FilemanagerToolController;

// Associatable Resources...
Route::get('/{resource}/associatable/{field}', 'AssociatableController@index');

// Computed field
Route::post('/{resource}/computed/{field}', 'ComputedController@index');

// Download file inside of Row field
Route::get('/{resource}/{resourceId}/download/{field}', 'FieldDownloadController@show');

Route::get('/options/{resource}', 'OptionsController@index');


/** FIle Manager Routes */
Route::get('data', FilemanagerToolController::class.'@getData');
Route::get('{resource}/{attribute}/data', FilemanagerToolController::class.'@getDataField');
Route::post('actions/move', FilemanagerToolController::class.'@move');
Route::post('actions/create-folder', FilemanagerToolController::class.'@createFolder');
Route::post('actions/delete-folder', FilemanagerToolController::class.'@deleteFolder');
Route::post('actions/get-info', FilemanagerToolController::class.'@getInfo');
Route::post('actions/remove-file', FilemanagerToolController::class.'@removeFile');
Route::post('actions/rename-file', FilemanagerToolController::class.'@renameFile');
Route::get('actions/download-file', FilemanagerToolController::class.'@downloadFile');
Route::post('actions/rename', FilemanagerToolController::class.'@rename');

Route::post('events/folder', FilemanagerToolController::class.'@folderUploadedEvent');

Route::post('uploads/add', FilemanagerToolController::class.'@upload');
Route::get('uploads/update', FilemanagerToolController::class.'@updateFile');