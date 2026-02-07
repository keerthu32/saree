<?php
function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect($path)
{
    header("Location: {$path}");
    exit;
}

function app_base_path()
{
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $parts = explode('/', trim($script, '/'));
    if (!$parts || $parts[0] === '') {
        return '';
    }
    array_pop($parts);
    if ($parts && in_array(end($parts), ['admin', 'customer'], true)) {
        array_pop($parts);
    }
    $base = '/' . implode('/', $parts);
    return $base === '/' ? '' : $base;
}

function url($path)
{
    $base = rtrim(getenv('BASE_URL') ?: app_base_path(), '/');
    $path = ltrim($path, '/');
    if ($base === '') {
        return '/' . $path;
    }
    return $base . '/' . $path;
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function require_login()
{
    if (!current_user()) {
        redirect(url('login.php'));
    }
}

function require_admin()
{
    require_login();
    if (current_user()['role'] !== 'admin') {
        redirect(url('customer/products.php'));
    }
}

function require_customer()
{
    require_login();
    if (current_user()['role'] !== 'customer') {
        redirect(url('admin/dashboard.php'));
    }
}

function cart_items()
{
    return $_SESSION['cart'] ?? [];
}

function add_to_cart($productId, $qty)
{
    $cart = cart_items();
    $cart[$productId] = ($cart[$productId] ?? 0) + $qty;
    $_SESSION['cart'] = $cart;
}

function update_cart($productId, $qty)
{
    $cart = cart_items();
    if ($qty <= 0) {
        unset($cart[$productId]);
    } else {
        $cart[$productId] = $qty;
    }
    $_SESSION['cart'] = $cart;
}
