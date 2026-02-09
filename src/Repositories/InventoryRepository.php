<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class InventoryRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function logTransaction(int $productId, int $delta, string $reason): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO inventory_transactions (product_id, quantity_delta, reason) 
             VALUES (:product_id, :quantity_delta, :reason)'
        );
        $stmt->execute([
            'product_id' => $productId,
            'quantity_delta' => $delta,
            'reason' => $reason,
        ]);
    }

    public function allTransactions(): array
    {
        $sql = 'SELECT it.*, p.sku, p.name AS product_name 
                FROM inventory_transactions it
                JOIN products p ON p.id = it.product_id
                ORDER BY it.id DESC';
        return $this->pdo->query($sql)->fetchAll();
    }
}
