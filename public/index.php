<?php

declare(strict_types=1);

use App\Controllers\InventoryController;
use App\Controllers\OrderController;
use App\Controllers\ProductController;
use App\Database;
use App\Repositories\InventoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Response;
use App\Router;
use App\Services\OrderService;

require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Response.php';
require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/Support/Request.php';
require_once __DIR__ . '/../src/Repositories/ProductRepository.php';
require_once __DIR__ . '/../src/Repositories/InventoryRepository.php';
require_once __DIR__ . '/../src/Repositories/OrderRepository.php';
require_once __DIR__ . '/../src/Services/OrderService.php';
require_once __DIR__ . '/../src/Controllers/ProductController.php';
require_once __DIR__ . '/../src/Controllers/InventoryController.php';
require_once __DIR__ . '/../src/Controllers/OrderController.php';

$pdo = Database::connection();
$productRepository = new ProductRepository($pdo);
$inventoryRepository = new InventoryRepository($pdo);
$orderRepository = new OrderRepository($pdo);
$orderService = new OrderService($pdo, $productRepository, $orderRepository, $inventoryRepository);

$productController = new ProductController($productRepository);
$inventoryController = new InventoryController($productRepository, $inventoryRepository);
$orderController = new OrderController($orderRepository, $orderService);

$router = new Router();


$router->add('GET', '/', static function (): void {
    header('Content-Type: text/html; charset=utf-8');
    readfile(__DIR__ . '/index.html');
});

$router->add('GET', '/admin', static function (): void {
    header('Content-Type: text/html; charset=utf-8');
    readfile(__DIR__ . '/admin.html');
});

$router->add('GET', '/health', static fn() => Response::success(['status' => 'ok']));

$router->add('GET', '/products', static fn() => $productController->index());
$router->add('GET', '/products/{id}', static fn($params) => $productController->show((int) $params['id']));
$router->add('POST', '/products', static fn() => $productController->store());
$router->add('PUT', '/products/{id}', static fn($params) => $productController->update((int) $params['id']));
$router->add('DELETE', '/products/{id}', static fn($params) => $productController->destroy((int) $params['id']));

$router->add('POST', '/inventory/adjust', static fn() => $inventoryController->adjust());
$router->add('GET', '/inventory/transactions', static fn() => $inventoryController->transactions());

$router->add('GET', '/orders', static fn() => $orderController->index());
$router->add('GET', '/orders/{id}', static fn($params) => $orderController->show((int) $params['id']));
$router->add('POST', '/orders', static fn() => $orderController->store());
$router->add('PATCH', '/orders/{id}/status', static fn($params) => $orderController->updateStatus((int) $params['id']));

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
