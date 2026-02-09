<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class OrderRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(): array
    {
        $orders = $this->pdo->query('SELECT * FROM orders ORDER BY id DESC')->fetchAll();
        foreach ($orders as &$order) {
            $order['items'] = $this->items((int) $order['id']);
        }
        return $orders;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();
        if (!$order) {
            return null;
        }
        $order['items'] = $this->items($id);
        return $order;
    }

    public function create(array $order, array $items): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO orders (customer_name, customer_phone, customer_address, status, total_amount)
             VALUES (:customer_name, :customer_phone, :customer_address, :status, :total_amount)'
        );
        $stmt->execute($order);

        $orderId = (int) $this->pdo->lastInsertId();
        $itemStmt = $this->pdo->prepare(
            'INSERT INTO order_items (order_id, product_id, quantity, unit_price, line_total)
             VALUES (:order_id, :product_id, :quantity, :unit_price, :line_total)'
        );

        foreach ($items as $item) {
            $itemStmt->execute([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'line_total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return $orderId;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare('UPDATE orders SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    private function items(int $orderId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT oi.*, p.sku, p.name AS product_name
             FROM order_items oi
             JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = :order_id'
        );
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }
}
