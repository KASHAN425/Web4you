<?php
require_once __DIR__ . '/helpers.php';

if (!is_admin()) {
    json_response(['success' => false, 'message' => 'Admin access required.'], 403);
}

$action = $_POST['action'] ?? $_GET['action'] ?? 'stats';

if ($action === 'add_product') {
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $image = sanitize($_POST['image'] ?? 'images/default.jpg');
    $demo = sanitize($_POST['demo_link'] ?? '#');
    $categoryId = (int) ($_POST['category_id'] ?? 1);

    $stmt = mysqli_prepare($conn, 'INSERT INTO products (title, description, price, image, demo_link, category_id) VALUES (?, ?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssdssi', $title, $description, $price, $image, $demo, $categoryId);
    mysqli_stmt_execute($stmt);
    json_response(['success' => true, 'message' => 'Product added.']);
}

if ($action === 'delete_product') {
    $id = (int) ($_POST['id'] ?? 0);
    $stmt = mysqli_prepare($conn, 'DELETE FROM products WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    json_response(['success' => true, 'message' => 'Product deleted.']);
}

if ($action === 'update_order_status') {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $status = sanitize($_POST['status'] ?? 'Pending');
    $stmt = mysqli_prepare($conn, 'UPDATE orders SET status = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'si', $status, $orderId);
    mysqli_stmt_execute($stmt);
    json_response(['success' => true, 'message' => 'Order status updated.']);
}

$stats = [
    'users' => mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS total FROM users'))['total'] ?? 0,
    'orders' => mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS total FROM orders'))['total'] ?? 0,
    'sales' => mysqli_fetch_assoc(mysqli_query($conn, 'SELECT IFNULL(SUM(total),0) AS total FROM orders'))['total'] ?? 0,
    'products' => mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS total FROM products'))['total'] ?? 0,
];

json_response(['success' => true, 'stats' => $stats]);
?>
