<?php

$router->get('/', 'AuthController@getLogin');

$router->post('/admin/login', 'AuthController@postLogin');
$router->post('/admin/logout', 'AuthController@logout');

$router->get('/test-koneksi', 'TestKoneksiController@test_koneksi');
$router->get('/admin/dashboard', 'DashboardController@index');

$router->get('/admin/tim-kreatif', 'TimKreatifController@index');
$router->get('/admin/tim-kreatif/{id}', 'TimKreatifController@edit');
$router->post('/admin/tim-kreatif/store', 'TimKreatifController@store');
$router->put('/admin/tim-kreatif/update/{id}', 'TimKreatifController@update');
$router->delete('/admin/tim-kreatif/delete/{id}', 'TimKreatifController@destroy');

$router->get('/admin/users', 'UsersController@index');
$router->get('/admin/users/{id}', 'UsersController@edit');
$router->post('/admin/users/store', 'UsersController@store');
$router->put('/admin/users/update/{id}', 'UsersController@update');
$router->delete('/admin/users/delete/{id}', 'UsersController@destroy');