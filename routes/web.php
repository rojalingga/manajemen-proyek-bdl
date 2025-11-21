<?php

$router->get('/', 'AuthController@getLogin');

$router->post('/admin/login', 'AuthController@postLogin');
$router->post('/admin/logout', 'AuthController@logout');

$router->get('/test-koneksi', 'TestKoneksiController@test_koneksi');
$router->get('/admin/dashboard', 'DashboardController@index');

$router->get('/admin/klien', 'KlienController@index');
$router->get('/admin/klien/{id_klien}', 'KlienController@edit');
$router->post('/admin/klien/store', 'KlienController@store');
$router->put('/admin/klien/update/{id_klien}', 'KlienController@update');
$router->delete('/admin/klien/delete/{id_klien}', 'KlienController@destroy');

$router->get('/admin/users', 'UsersController@index');
$router->get('/admin/users/{id}', 'UsersController@edit');
$router->post('/admin/users/store', 'UsersController@store');
$router->put('/admin/users/update/{id}', 'UsersController@update');
$router->delete('/admin/users/delete/{id}', 'UsersController@destroy');