<?php

use Illuminate\Support\Facades\Storage;

Route::get('/', 'PageController@welcome');
Route::post('/', 'PageController@password');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/sitemap.xml', 'PageController@sitemap');

Route::get('/projects/list', 'ProjectController@list')->name('projects.list');
Route::patch('/projects/{project}/trash/', 'ProjectController@trash')->name('projects.trash');
Route::patch('/projects/{project}/restore/', 'ProjectController@restore')->name('projects.restore');
Route::get('/projects/{project}/media/{collection?}', 'ProjectController@media');
Route::resource('/projects', 'ProjectController');
Route::get('/proposals/list', 'ProposalController@list')->name('proposals.list');
Route::post('/proposals/{proposal}/password', 'ProposalController@password')->name('proposals.password');
Route::resource('/proposals', 'ProposalController');

Route::get('/users/list', 'UserController@list')->name('users.list');
Route::resource('/users', 'UserController');

Route::get('/assets/{asset}/download', 'AssetController@download')->name('asset.download');
Route::resource('/assets', 'AssetController');


Route::get('/show-job-email', function () {
    dump((bool)file_exists(base_path('email')));
})->middleware(['auth']);

Route::get('/create-job-email', function () {
    dump((bool)touch(base_path('email')));
})->middleware(['auth']);

Route::get('/delete-job-email', function () {
    dump((bool)unlink(base_path('email')));
})->middleware(['auth']);

Route::get('/start-job', function () {
    dump((bool)touch(base_path('job')));
})->middleware(['auth']);

Route::get('/show-job', function () {
    dump((bool)file_exists(base_path('job')));
})->middleware(['auth']);

Route::get('/stop-job', function () {
    dump((bool)unlink(base_path('job')));
})->middleware(['auth']);

Route::get('/create-s3-file', function () {
    $uuid = \Illuminate\Support\Str::uuid();
    dump($uuid);
    return Storage::disk('s3')->put('files/' . $uuid . '.txt', 'test - ' . $uuid);
})->middleware(['auth']);

Route::get('/show-s3-file/{uuid}', function ($uuid) {
    return Storage::disk('s3')->get('files/' . $uuid . '.txt');
})->middleware(['auth']);

Route::get('/delete-s3-file/{uuid}', function ($uuid) {
    return Storage::disk('s3')->delete('files/' . $uuid . '.txt');
})->middleware(['auth']);