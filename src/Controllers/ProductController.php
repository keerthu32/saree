<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ProductRepository;
use App\Response;
use App\Support\Request;

final class ProductController
{
    public function __construct(private readonly ProductRepository $products)
    {
    }

    public function index(): void
    {
        Response::success($this->products->all());
    }

    public function show(int $id): void
    {
        $product = $this->products->find($id);
        if (!$product) {
            Response::error('Product not found', 404);
            return;
        }
        Response::success($product);
    }

    public function store(): void
    {
        $body = Request::jsonBody();
        foreach (['sku', 'name', 'price'] as $field) {
            if (!isset($body[$field])) {
                Response::error("Missing field: {$field}", 422);
                return;
            }
        }

        if ($this->products->findBySku((string) $body['sku'])) {
            Response::error('SKU already exists', 409);
            return;
        }

        $id = $this->products->create($body);
        Response::success($this->products->find($id), 201);
    }

    public function update(int $id): void
    {
        if (!$this->products->find($id)) {
            Response::error('Product not found', 404);
            return;
        }

        $body = Request::jsonBody();
        foreach (['name', 'price', 'stock_quantity'] as $field) {
            if (!isset($body[$field])) {
                Response::error("Missing field: {$field}", 422);
                return;
            }
        }

        $this->products->update($id, $body);
        Response::success($this->products->find($id));
    }

    public function destroy(int $id): void
    {
        if (!$this->products->find($id)) {
            Response::error('Product not found', 404);
            return;
        }
        $this->products->delete($id);
        Response::success(['deleted' => true]);
    }
}
