<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\OrderRepository;
use App\Response;
use App\Services\OrderService;
use App\Support\Request;
use RuntimeException;

final class OrderController
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly OrderService $orderService
    ) {
    }

    public function index(): void
    {
        Response::success($this->orders->all());
    }

    public function show(int $id): void
    {
        $order = $this->orders->find($id);
        if (!$order) {
            Response::error('Order not found', 404);
            return;
        }

        Response::success($order);
    }

    public function store(): void
    {
        $payload = Request::jsonBody();
        if (!isset($payload['customer_name'])) {
            Response::error('Missing field: customer_name', 422);
            return;
        }

        try {
            $order = $this->orderService->createOrder($payload);
            Response::success($order, 201);
        } catch (RuntimeException $e) {
            Response::error($e->getMessage(), 422);
        }
    }

    public function updateStatus(int $id): void
    {
        $order = $this->orders->find($id);
        if (!$order) {
            Response::error('Order not found', 404);
            return;
        }

        $payload = Request::jsonBody();
        $status = (string) ($payload['status'] ?? '');
        if ($status === '') {
            Response::error('Missing field: status', 422);
            return;
        }

        try {
            $this->orderService->updateStatus($order, $status);
            Response::success($this->orders->find($id));
        } catch (RuntimeException $e) {
            Response::error($e->getMessage(), 422);
        }
    }
}
