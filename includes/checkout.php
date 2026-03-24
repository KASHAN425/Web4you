<?php
require_once __DIR__ . '/helpers.php';

$userId = current_user_id();
if (!$userId) {
    json_response(['success' => false, 'message' => 'Please login first.'], 401);
}

$method = sanitize($_POST['payment_method'] ?? 'Cash on Delivery');

$cartQuery = mysqli_prepare($conn, 'SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON p.id = c.product_id WHERE c.user_id = ?');
mysqli_stmt_bind_param($cartQuery, 'i', $userId);
mysqli_stmt_execute($cartQuery);
$res = mysqli_stmt_get_result($cartQuery);
$items = mysqli_fetch_all($res, MYSQLI_ASSOC);

if (!$items) {
    json_response(['success' => false, 'message' => 'Cart is empty.'], 400);
}

$total = 0;
foreach ($items as $item) {
    $total += $item['quantity'] * $item['price'];
}

$status = 'Pending - ' . $method;
$orderStmt = mysqli_prepare($conn, 'INSERT INTO orders (user_id, total, status) VALUES (?, ?, ?)');
mysqli_stmt_bind_param($orderStmt, 'ids', $userId, $total, $status);
mysqli_stmt_execute($orderStmt);
$orderId = mysqli_insert_id($conn);

$itemStmt = mysqli_prepare($conn, 'INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
foreach ($items as $item) {
    mysqli_stmt_bind_param($itemStmt, 'iiid', $orderId, $item['product_id'], $item['quantity'], $item['price']);
    mysqli_stmt_execute($itemStmt);
}

$clearStmt = mysqli_prepare($conn, 'DELETE FROM cart WHERE user_id = ?');
mysqli_stmt_bind_param($clearStmt, 'i', $userId);
mysqli_stmt_execute($clearStmt);

json_response(['success' => true, 'message' => 'Order placed successfully.', 'order_id' => $orderId]);
?>
