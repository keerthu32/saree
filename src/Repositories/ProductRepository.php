<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ProductRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(): array
    {
        return $this->pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findBySku(string $sku): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE sku = :sku');
        $stmt->execute(['sku' => $sku]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO products (sku, name, description, category, color, fabric, price, stock_quantity) 
             VALUES (:sku, :name, :description, :category, :color, :fabric, :price, :stock_quantity)'
        );

        $stmt->execute([
            'sku' => $data['sku'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'category' => $data['category'] ?? null,
            'color' => $data['color'] ?? null,
            'fabric' => $data['fabric'] ?? null,
            'price' => $data['price'],
            'stock_quantity' => $data['stock_quantity'] ?? 0,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE products SET
                name = :name,
                description = :description,
                category = :category,
                color = :color,
                fabric = :fabric,
                price = :price,
                stock_quantity = :stock_quantity,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'category' => $data['category'] ?? null,
            'color' => $data['color'] ?? null,
            'fabric' => $data['fabric'] ?? null,
            'price' => $data['price'],
            'stock_quantity' => $data['stock_quantity'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function adjustStock(int $productId, int $delta): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE products 
             SET stock_quantity = stock_quantity + :delta, updated_at = CURRENT_TIMESTAMP
             WHERE id = :id AND stock_quantity + :delta >= 0'
        );
        return $stmt->execute(['delta' => $delta, 'id' => $productId]) && $stmt->rowCount() > 0;
    }
}
