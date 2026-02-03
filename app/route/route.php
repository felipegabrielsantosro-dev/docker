<?php

use app\controller\User;
use app\controller\cliente;
use app\controller\Empresa;
use app\controller\Home;
use app\controller\Fornecedor;
use app\middleware\Middleware;
use Slim\Routing\RouteCollectorProxy;
use app\controller\PaymentTerms;


$app->get('/', Home::class . ':home');
$app->get('/home', Home::class . ':home');
/*$app->get('/login', Login::class . ':login');*/

$app->group('/home', function (RouteCollectorProxy $group) {
    #$group->post('/tema', Home::class . ':tema');
});
$app->group('/venda', function (RouteCollectorProxy $group) {
    $group->get('/lista', Sale::class . ':lista');
    $group->get('/cadastro', Sale::class . ':cadastro');
});
$app->group('/usuario', function (RouteCollectorProxy $group) {
    $group->get('/lista', User::class . ':lista');
    $group->get('/cadastro', User::class . ':cadastro');
    $group->get('/alterar/{id}', User::class . ':alterar');
    $group->get('/print', User::class . ':print');
    $group->post('/insert', User::class . ':insert');
    $group->post('/update', User::class . ':update');
});

$app->group('/cliente', function (RouteCollectorProxy $group) {
    $group->get('/lista', Cliente::class . ':lista')->add(Middleware::authentication());
    $group->get('/cadastro', Cliente::class . ':cadastro')->add(Middleware::authentication());
    $group->post('/listacliente', Cliente::class . ':listacliente');
    $group->post('/update', Cliente::class . ':update');
    $group->post('/insert', Cliente::class . ':insert');
    $group->get('/print', Cliente::class . ':print');
    $group->get('/alterar/{id}', Cliente::class . ':alterar')->add(Middleware::authentication());
    $group->post('/delete', Cliente::class . ':delete');
});
$app->group('/empresa', function (RouteCollectorProxy $group) {
    $group->get('/lista', Empresa::class . ':lista')->add(Middleware::authentication());
    $group->get('/cadastro', Empresa::class . ':cadastro')->add(Middleware::authentication());
    $group->post('/listaempresa', Empresa::class . ':listaempresa');
    $group->post('/update', Empresa::class . ':update');
    $group->get('/print', Empresa::class . ':print');
    $group->post('/insert', Empresa::class . ':insert');
    $group->get('/alterar/{id}', Empresa::class . ':alterar')->add(Middleware::authentication());
    $group->post('/delete', Empresa::class . ':delete');
});

$app->group('/fornecedor', function (RouteCollectorProxy $group) {
    $group->get('/lista', Fornecedor::class . ':lista')->add(Middleware::authentication());
    $group->get('/cadastro', Fornecedor::class . ':cadastro')->add(Middleware::authentication());
    $group->post('/listafornecedor', Fornecedor::class . ':listafornecedor');
    $group->post('/update', Fornecedor::class . ':update');
    $group->post('/insert', Fornecedor::class . ':insert');
    $group->get('/print', Fornecedor::class . ':print');
    $group->get('/alterar/{id}', Fornecedor::class . ':alterar')->add(Middleware::authentication());
    $group->post('/delete', Fornecedor::class . ':delete');
});

$app->group('/PaymentTerms', function (RouteCollectorProxy $group) {
    $group->get('/lista', PaymentTerms::class . ':lista'); #->add(Middleware::authentication());
    $group->get('/cadastro', PaymentTerms::class . ':cadastro'); #->add(Middleware::authentication());
    $group->post('/alterar/{id}', PaymentTerms::class . ':alterar'); #->add(Middleware::authentication());
    $group->post('/insert', PaymentTerms::class . ':insert'); #->add(Middleware::authentication());
    
});
