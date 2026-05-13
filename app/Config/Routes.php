<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/testdb', 'Home::testdb');


$routes->group('auth', static function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->get('logout', 'AuthController::logout');
});

$routes->group('employe', static function ($routes) {
	$routes->get('/', 'EmployeController::dashboard');
	$routes->get('dashboard', 'EmployeController::dashboard');
	$routes->get('conges', 'EmployeController::conges');
	$routes->get('conges/create', 'EmployeController::congeCreate');
	$routes->get('profil', 'EmployeController::profil');
});

$routes->group('admin', static function ($routes) {
	$routes->get('/', 'AdminController::dashboard');
	$routes->get('dashboard', 'AdminController::dashboard');
	$routes->get('demandes', 'AdminController::demandes');
	$routes->get('employes', 'AdminController::employes');
});

$routes->group('rh', static function ($routes) {
	$routes->get('/', 'RhController::dashboard');
	$routes->get('dashboard', 'RhController::dashboard');
	$routes->get('demandes', 'RhController::demandes');
});
