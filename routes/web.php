<?php

$router->get('/', 'LandingPageController@index');

$router->get('/admin/login', 'LoginController@getLogin');
$router->get('/admin/users', 'UsersController@index');