<?php
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