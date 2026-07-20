<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
use App\Controllers\operator\OperateurController;


use App\Controllers\Client\AuthController;
use App\Controllers\Client\DashboardController;
use App\Controllers\Client\OperationController;
use App\Controllers\Client\HistoryController;

$routes->group('', ['namespace' => 'App\Controllers\operator'], static function ($routes) {
$routes->get('operator', 'OperateurController::index');
$routes->post('operator/addPrefix', 'OperateurController::addPrefix');
$routes->post('operator/saveBareme', 'OperateurController::saveBareme');
});
// Connexion / Déconnexion
$routes->get('/', [AuthController::class, 'login']);
$routes->post('client/loginProcess', [AuthController::class, 'loginProcess']);
$routes->get('client/logout', [AuthController::class, 'logout']);

// Espace Client
$routes->group('client', function($routes) {
    $routes->get('dashboard', [DashboardController::class, 'index']);
    
    // Opérations
    $routes->post('depot', [OperationController::class, 'depot']);
    $routes->post('retrait', [OperationController::class, 'retrait']);
    $routes->post('transfert', [OperationController::class, 'transfert']);
    
    // Historique
    $routes->get('history', [HistoryController::class, 'index']);
});