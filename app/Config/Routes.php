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
    $routes->get('operator/login', 'AuthController::login');
    $routes->post('operator/loginProcess', 'AuthController::loginProcess');
    $routes->get('operator/logout', 'AuthController::logout');

    $routes->get('operator', 'OperateurController::index');
    $routes->get('operator/gains', 'OperateurController::gains');
    $routes->get('operator/clients', 'OperateurController::clients');
    $routes->get('operator/prefixes', 'OperateurController::prefixes');
    $routes->get('operator/baremes', 'OperateurController::baremes');
    $routes->get('operator/commissions', 'OperateurController::commissions');
    $routes->get('operator/decompte', 'OperateurController::decompte');
    $routes->post('operator/addPrefix', 'OperateurController::addPrefix');
    $routes->post('operator/saveBareme', 'OperateurController::saveBareme');
    $routes->post('operator/saveCommission', 'OperateurController::saveCommission');
    $routes->post('operator/marquerEnvoye', 'OperateurController::marquerEnvoye');
});


$routes->group('', ['namespace' => 'App\Controllers\Client'], static function ($routes) {
    $routes->get('/', 'AuthController::login');
    $routes->post('client/loginProcess', 'AuthController::loginProcess');
    $routes->get('client/logout', 'AuthController::logout');

    // Espace Client
    $routes->group('client', function($routes) {
        $routes->get('dashboard', 'DashboardController::index');
        
        // Opérations
        $routes->post('depot', 'OperationController::depot');
        $routes->post('retrait', 'OperationController::retrait');
        $routes->post('transfert', 'OperationController::transfert');
        $routes->post('transfertMultiple', 'OperationController::transfertMultiple');
        
        // Historique
        $routes->get('history', 'HistoryController::index');
    });
});
$routes->post('client/transfert-multiple', 'Client\OperationController::transfertMultiple', ['filter' => 'auth']);
