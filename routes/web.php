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

$router->get('/admin/pegawai', 'PegawaiController@index');
$router->get('/admin/pegawai/{id_pegawai}', 'PegawaiController@edit');
$router->post('/admin/pegawai/store', 'PegawaiController@store');
$router->put('/admin/pegawai/update/{id_pegawai}', 'PegawaiController@update');
$router->delete('/admin/pegawai/delete/{id_pegawai}', 'PegawaiController@destroy');

$router->get('/admin/tim', 'TimController@index');
$router->get('/admin/tim/{id_tim}', 'TimController@edit');
$router->post('/admin/tim/store', 'TimController@store');
$router->put('/admin/tim/update/{id_tim}', 'TimController@update');
$router->delete('/admin/tim/delete/{id_tim}', 'TimController@destroy');

$router->get('/admin/status', 'StatusController@index');
$router->get('/admin/status/{id_status}', 'StatusController@edit');
$router->post('/admin/status/store', 'StatusController@store');
$router->put('/admin/status/update/{id_status}', 'StatusController@update');
$router->delete('/admin/status/delete/{id_status}', 'StatusController@destroy');

$router->get('/admin/proyek', 'ProyekController@index');
$router->get('/admin/proyek/{id_proyek}', 'ProyekController@edit');
$router->post('/admin/proyek/store', 'ProyekController@store');
$router->put('/admin/proyek/update/{id_proyek}', 'ProyekController@update');
$router->delete('/admin/proyek/delete/{id_proyek}', 'ProyekController@destroy');

$router->get('/admin/tugas', 'TugasController@index');
$router->get('/admin/tugas/{id_tugas}', 'TugasController@edit');
$router->post('/admin/tugas/store', 'TugasController@store');
$router->put('/admin/tugas/update/{id_tugas}', 'TugasController@update');
$router->delete('/admin/tugas/delete/{id_tugas}', 'TugasController@destroy');
$router->get('/admin/tugas/get-tim-by-proyek/{id_proyek}', 'TugasController@getTimByProyek');

$router->get('/admin/users', 'UsersController@index');
$router->get('/admin/users/{id}', 'UsersController@edit');
$router->post('/admin/users/store', 'UsersController@store');
$router->put('/admin/users/update/{id}', 'UsersController@update');
$router->delete('/admin/users/delete/{id}', 'UsersController@destroy');
