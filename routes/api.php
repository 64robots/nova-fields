<?php

use Illuminate\Support\Facades\Route;

// Associatable Resources...
Route::get('/{resource}/associatable/{field}', 'AssociatableController@index');

// Computed field
Route::post('/{resource}/computed/{field}', 'ComputedController@index');

// Download file inside of Row field
Route::get('/{resource}/{resourceId}/download/{field}', 'FieldDownloadController@show');

Route::get('/options/{resource}', 'OptionsController@index');


/** FIle Manager Routes */
Route::get('data', 'FilemanagerToolController@getData');
Route::get('{resource}/{attribute}/data', 'FilemanagerToolController@getDataField');
Route::post('actions/move', 'FilemanagerToolController@move');
Route::post('actions/create-folder', 'FilemanagerToolController@createFolder');
Route::post('actions/delete-folder', 'FilemanagerToolController@deleteFolder');
Route::post('actions/get-info', 'FilemanagerToolController@getInfo');
Route::post('actions/remove-file', 'FilemanagerToolController@removeFile');
Route::post('actions/rename-file', 'FilemanagerToolController@renameFile');
Route::get('actions/download-file', 'FilemanagerToolController@downloadFile');
Route::post('actions/rename', 'FilemanagerToolController@rename');

Route::post('events/folder', 'FilemanagerToolController@folderUploadedEvent');

Route::post('uploads/add', 'FilemanagerToolController@upload');
Route::get('uploads/update', 'FilemanagerToolController@updateFile');