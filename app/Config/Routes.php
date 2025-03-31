<?php
namespace Config;

use CodeIgniter\Router\RouteCollection;

$routes->get('logout', 'AuthController::logout');
$routes->group('', ['filter' => 'guest'], function ($routes) {
    $routes->get('/', 'AuthController::login');
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('forgot-password', 'AuthController::showForgotPassword');
    $routes->post('forgot-password', 'AuthController::processForgotPassword');
    $routes->get('reset-password/(:any)', 'AuthController::showResetPassword/$1');
    $routes->post('reset-password', 'AuthController::processResetPassword');
});

// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('/', 'AdminController::dashboard');

    // Customer CRUD
    $routes->get('customers', 'CustomerController::index');
    $routes->get('customers/create', 'CustomerController::create');
    $routes->post('customers/store', 'CustomerController::store');
    $routes->get('customers/edit/(:num)', 'CustomerController::edit/$1');
    $routes->post('customers/update/(:num)', 'CustomerController::update/$1');
    $routes->get('customers/delete/(:num)', 'CustomerController::delete/$1');

    // Banner CRUD
    $routes->get('banners', 'BannerController::index');
    $routes->get('banners/create', 'BannerController::create');
    $routes->post('banners/store', 'BannerController::store');
    $routes->get('banners/edit/(:num)', 'BannerController::edit/$1');
    $routes->post('banners/update/(:num)', 'BannerController::update/$1');
    $routes->get('banners/delete/(:num)', 'BannerController::delete/$1');
    $routes->get('banners/toggle/(:num)', 'BannerController::toggle/$1');
});

// Banner Embed
$routes->get('banner.js/(:segment)', 'BannerController::generateJS/$1');
$routes->get('api/banner/(:segment)', 'BannerController::getBanner/$1');
