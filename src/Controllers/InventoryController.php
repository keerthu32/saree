<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\InventoryRepository;
use App\Repositories\ProductRepository;
use App\Response;
use App\Support\Request;

final class InventoryController
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly InventoryRepository $inventory
    ) {
    }

    public function adjust(): void
    {
        $body = Request::jsonBody();
        $productId = (int) ($body['product_id'] ?? 0);
        $delta = (int) ($body['quantity_delta'] ?? 0);
        $reason = (string) ($body['reason'] ?? 'manual_adjustment');

        if ($productId <= 0 || $delta === 0) {
            Response::error('product_id and non-zero quantity_delta are required', 422);
            return;
        }

        if (!$this->products->find($productId)) {
            Response::error('Product not found', 404);
            return;
        }

        if (!$this->products->adjustStock($productId, $delta)) {
            Response::error('Stock adjustment failed (insufficient stock)', 409);
            return;
        }

        $this->inventory->logTransaction($productId, $delta, $reason);
        Response::success($this->products->find($productId));
    }

    public function transactions(): void
    {
        Response::success($this->inventory->allTransactions());
    }
}
