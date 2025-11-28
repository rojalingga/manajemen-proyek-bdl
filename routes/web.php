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

$router->get('/admin/tugas', 'TugasController@index');
$router->post('/admin/tugas/store', 'TugasController@store');
$router->get('/admin/tugas/edit/{id}', 'TugasController@edit');
$router->put('/admin/tugas/update/{id}', 'TugasController@update');
$router->delete('/admin/tugas/delete/{id}', 'TugasController@destroy');

$router->get('/admin/status', 'StatusController@index');
$router->post('/admin/status/store', 'StatusController@store');
$router->get('/admin/status/edit/{id}', 'StatusController@edit');
$router->put('/admin/status/update/{id}', 'StatusController@update');
$router->delete('/admin/status/delete/{id}', 'StatusController@destroy');

$router->get('/admin/tim', 'TimController@index');
$router->post('/admin/tim/store', 'TimController@store');
$router->get('/admin/tim/edit/{id}', 'TimController@edit');
$router->put('/admin/tim/update/{id}', 'TimController@update');
$router->delete('/admin/tim/delete/{id}', 'TimController@destroy');

$router->get('/admin/pegawai', 'PegawaiController@index');
$router->post('/admin/pegawai/store', 'PegawaiController@store');
$router->get('/admin/pegawai/edit/{id}', 'PegawaiController@edit');
$router->put('/admin/pegawai/update/{id}', 'PegawaiController@update');
$router->delete('/admin/pegawai/delete/{id}', 'PegawaiController@destroy');

$router->get('/admin/proyek', 'ProyekController@index');
$router->post('/admin/proyek/store', 'ProyekController@store');
$router->get('/admin/proyek/edit/{id}', 'ProyekController@edit');
$router->put('/admin/proyek/update/{id}', 'ProyekController@update');
$router->delete('/admin/proyek/delete/{id}', 'ProyekController@destroy');

$router->get('/admin/proyek_tim', 'ProyekTimController@index');
$router->post('/admin/proyek_tim/store', 'ProyekTimController@store');
$router->get('/admin/proyek_tim/edit/{id}', 'ProyekTimController@edit');
$router->put('/admin/proyek_tim/update/{id}', 'ProyekTimController@update');
$router->delete('/admin/proyek_tim/delete/{id}', 'ProyekTimController@destroy');

$router->get('/admin/proyek_klien', 'ProyekKlienController@index');
$router->post('/admin/proyek_klien/store', 'ProyekKlienController@store');
$router->get('/admin/proyek_klien/edit/{id}', 'ProyekKlienController@edit');
$router->put('/admin/proyek_klien/update/{id}', 'ProyekKlienController@update');
$router->delete('/admin/proyek_klien/delete/{id}', 'ProyekKlienController@destroy');

$router->get('/admin/anggota_tim', 'AnggotaTimController@index');
$router->post('/admin/anggota_tim/store', 'AnggotaTimController@store');
$router->get('/admin/anggota_tim/edit/{id}', 'AnggotaTimController@edit');
$router->put('/admin/anggota_tim/update/{id}', 'AnggotaTimController@update');
$router->delete('/admin/anggota_tim/delete/{id}', 'AnggotaTimController@destroy');