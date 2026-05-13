<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/testdb', 'Home::testdb');


$routes->group('auth', static function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::login');
    $routes->get('logout', 'AuthController::logout');
});

$routes->group('employe', ['filter' => 'auth:employe'], static function ($routes) {
	$routes->get('/', 'EmployeController::dashboard');
	$routes->get('dashboard', 'EmployeController::dashboard');
	$routes->get('conges', 'EmployeController::conges');
	$routes->get('conges/create', 'EmployeController::congeCreate');
	$routes->post('conges/store', 'EmployeController::congeStore');
	$routes->post('conges/annuler/(:num)', 'EmployeController::congeAnnuler/$1');
	$routes->get('profil', 'EmployeController::profil');
	$routes->post('profil/update', 'EmployeController::profilUpdate');
	$routes->post('profil/password', 'EmployeController::profilPassword');
});

$routes->group('admin', ['filter' => 'auth:admin'], static function ($routes) {
	$routes->get('/', 'AdminController::dashboard');
	$routes->get('dashboard', 'AdminController::dashboard');
	$routes->get('demandes', 'AdminController::demandes');
	$routes->get('employes', 'AdminController::employes');
});

$routes->group('rh', ['filter' => 'auth:rh'], static function ($routes) {
	$routes->get('/', 'RhController::dashboard');
	$routes->get('dashboard', 'RhController::dashboard');
	$routes->get('demandes', 'RhController::demandes');
});
