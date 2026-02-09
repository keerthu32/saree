<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\InventoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use PDO;
use RuntimeException;

final class OrderService
{
    private const CANCELLABLE = ['pending', 'paid', 'packed'];

    public function __construct(
        private readonly PDO $pdo,
        private readonly ProductRepository $products,
        private readonly OrderRepository $orders,
        private readonly InventoryRepository $inventory
    ) {
    }

    public function createOrder(array $payload): array
    {
        $items = $payload['items'] ?? [];
        if ($items === []) {
            throw new RuntimeException('Order must include at least one item.');
        }

        $this->pdo->beginTransaction();
        try {
            $total = 0.0;

            foreach ($items as $item) {
                $productId = (int) ($item['product_id'] ?? 0);
                $qty = (int) ($item['quantity'] ?? 0);
                $price = (float) ($item['unit_price'] ?? 0);

                if ($productId <= 0 || $qty <= 0 || $price < 0) {
                    throw new RuntimeException('Invalid order item payload.');
                }

                $product = $this->products->find($productId);
                if (!$product) {
                    throw new RuntimeException("Product {$productId} not found.");
                }

                if (!$this->products->adjustStock($productId, -$qty)) {
                    throw new RuntimeException("Insufficient stock for product {$product['name']}.");
                }

                $this->inventory->logTransaction($productId, -$qty, 'order_placed');
                $total += $qty * $price;
            }

            $orderId = $this->orders->create([
                'customer_name' => $payload['customer_name'],
                'customer_phone' => $payload['customer_phone'] ?? null,
                'customer_address' => $payload['customer_address'] ?? null,
                'status' => 'pending',
                'total_amount' => $total,
            ], $items);

            $this->pdo->commit();
            return $this->orders->find($orderId) ?? [];
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new RuntimeException($e->getMessage());
        }
    }

    public function updateStatus(array $order, string $newStatus): void
    {
        $allowed = ['pending', 'paid', 'packed', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($newStatus, $allowed, true)) {
            throw new RuntimeException('Invalid status value.');
        }

        if ($newStatus === 'cancelled' && in_array($order['status'], self::CANCELLABLE, true)) {
            $this->pdo->beginTransaction();
            try {
                foreach ($order['items'] as $item) {
                    $this->products->adjustStock((int) $item['product_id'], (int) $item['quantity']);
                    $this->inventory->logTransaction((int) $item['product_id'], (int) $item['quantity'], 'order_cancelled');
                }
                $this->orders->updateStatus((int) $order['id'], $newStatus);
                $this->pdo->commit();
                return;
            } catch (\Throwable $e) {
                $this->pdo->rollBack();
                throw new RuntimeException($e->getMessage());
            }
        }

        $this->orders->updateStatus((int) $order['id'], $newStatus);
    }
}
