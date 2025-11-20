<?php

$router->get('/', 'LandingPageController@index');

$router->get('/admin/login', 'AuthController@getLogin');
$router->post('/admin/login', 'AuthController@postLogin');
$router->post('/admin/logout', 'AuthController@logout');

$router->get('/test-koneksi', 'TestKoneksiController@test_koneksi');
$router->get('/admin/dashboard', 'DashboardController@index');

$router->get('/admin/profil-web', 'ProfilWebController@index');
$router->post('/admin/profil-web/update', 'ProfilWebController@update');

$router->get('/admin/tim-kreatif', 'TimKreatifController@index');
$router->get('/admin/tim-kreatif/{id}', 'TimKreatifController@edit');
$router->post('/admin/tim-kreatif/store', 'TimKreatifController@store');
$router->put('/admin/tim-kreatif/update/{id}', 'TimKreatifController@update');
$router->delete('/admin/tim-kreatif/delete/{id}', 'TimKreatifController@destroy');

$router->get('/admin/partner-kolaborator', 'PartnerKolaboratorController@index');
$router->get('/admin/partner-kolaborator/{id}', 'PartnerKolaboratorController@edit');
$router->post('/admin/partner-kolaborator/store', 'PartnerKolaboratorController@store');
$router->put('/admin/partner-kolaborator/update/{id}', 'PartnerKolaboratorController@update');
$router->delete('/admin/partner-kolaborator/delete/{id}', 'PartnerKolaboratorController@destroy');

$router->get('/admin/artikel-berita', 'ArtikelBeritaController@index');
$router->get('/admin/artikel-berita/{id}', 'ArtikelBeritaController@edit');
$router->post('/admin/artikel-berita/store', 'ArtikelBeritaController@store');
$router->put('/admin/artikel-berita/update/{id}', 'ArtikelBeritaController@update');
$router->delete('/admin/artikel-berita/delete/{id}', 'ArtikelBeritaController@destroy');

$router->get('/admin/event-highlight', 'EventHighlightController@index');
$router->get('/admin/event-highlight/{id}', 'EventHighlightController@edit');
$router->post('/admin/event-highlight/store', 'EventHighlightController@store');
$router->put('/admin/event-highlight/update/{id}', 'EventHighlightController@update');
$router->delete('/admin/event-highlight/delete/{id}', 'EventHighlightController@destroy');

$router->get('/admin/publikasi-ilmiah', 'PublikasiIlmiahController@index');
$router->get('/admin/publikasi-ilmiah/{id}', 'PublikasiIlmiahController@edit');
$router->post('/admin/publikasi-ilmiah/store', 'PublikasiIlmiahController@store');
$router->put('/admin/publikasi-ilmiah/update/{id}', 'PublikasiIlmiahController@update');
$router->delete('/admin/publikasi-ilmiah/delete/{id}', 'PublikasiIlmiahController@destroy');

$router->get('/admin/users', 'UsersController@index');
$router->get('/admin/users/{id}', 'UsersController@edit');
$router->post('/admin/users/store', 'UsersController@store');
$router->put('/admin/users/update/{id}', 'UsersController@update');
$router->delete('/admin/users/delete/{id}', 'UsersController@destroy');

$router->get('/profile-lab', 'LandingPageController@getProfileLaboratorium');
$router->get('/profile-lab/tim-kreatif/{id}', 'LandingPageController@getTimKreatifDetail');
$router->get('/profile-lab/partner-kolaborator/{id}', 'LandingPageController@getPartnerKolaboratorDetail');

$router->get('/proyek-digital', 'LandingPageController@getProyekDigital');
$router->get('/proyek-digital/detail-proyek/{id}', 'LandingPageController@getDetailProyekDigital');

$router->get('/publikasi-kegiatan', 'LandingPageController@getPublikasiKegiatan');