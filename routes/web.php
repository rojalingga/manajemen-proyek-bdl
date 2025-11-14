<?php

$router->get('/', 'LandingPageController@index');

$router->get('/admin/login', 'AuthController@getLogin');
$router->post('/admin/login', 'AuthController@postLogin');
$router->post('/admin/logout', 'AuthController@logout');

$router->get('/test-koneksi', 'TestKoneksiController@test_koneksi');
$router->get('/admin/dashboard', 'DashboardController@index');

$router->get('/admin/users', 'UsersController@index');
$router->get('/admin/users/{id}', 'UsersController@edit');
$router->post('/admin/users/store', 'UsersController@store');
$router->put('/admin/users/update/{id}', 'UsersController@update');
$router->delete('/admin/users/delete/{id}', 'UsersController@destroy');

$router->get('/profile-lab', 'LandingPageController@getProfileLaboratorium');

$router->get('/artikel-berita', 'LandingPageController@getArtikelBerita');
$router->get('/publikasi-ilmiah', 'LandingPageController@getPublikasiIlmiah');
$router->get('/event-highlight', 'LandingPageController@getEventHighlight');

$router->get('/proyek-digital', 'LandingPageController@getProyekDigital');
$router->get('/proyek-digital/{id}', 'LandingPageController@getDetailProyekDigital');