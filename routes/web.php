<?php

$router->get('/', 'LandingPageController@index');

$router->get('/admin/login', 'LoginController@getLogin');

$router->get('/admin/users', 'UsersController@index');

$router->get('/artikel-berita', 'LandingPageController@getArtikelBerita');

$router->get('/publikasi-ilmiah', 'LandingPageController@getPublikasiIlmiah');

$router->get('/event-highlight', 'LandingPageController@getEventHighlight');