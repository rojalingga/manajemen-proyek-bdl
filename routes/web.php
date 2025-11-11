<?php

$router->get('/', 'LandingPageController@index');

$router->get('/admin/login', 'AuthController@getLogin');
$router->post('/admin/login', 'AuthController@postLogin');
$router->post('/admin/logout', 'AuthController@logout');

$router->get('/test-koneksi', 'TestKoneksiController@test_koneksi');
$router->get('/admin/users', 'UsersController@index');
$router->get('/admin/dashboard', 'DashboardController@index');
