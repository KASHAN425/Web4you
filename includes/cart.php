<?php
require_once __DIR__ . '/helpers.php';

$userId = current_user_id();
$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

if (!$userId) {
    json_response(['success' => false, 'message' => 'Login required for server cart actions.'], 401);
}

if ($action === 'add') {
    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

    $check = mysqli_prepare($conn, 'SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? LIMIT 1');
    mysqli_stmt_bind_param($check, 'ii', $userId, $productId);
    mysqli_stmt_execute($check);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($check));

    if ($row) {
        $newQty = (int) $row['quantity'] + $quantity;
        $update = mysqli_prepare($conn, 'UPDATE cart SET quantity = ? WHERE id = ?');
        mysqli_stmt_bind_param($update, 'ii', $newQty, $row['id']);
        mysqli_stmt_execute($update);
    } else {
        $insert = mysqli_prepare($conn, 'INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)');
        mysqli_stmt_bind_param($insert, 'iii', $userId, $productId, $quantity);
        mysqli_stmt_execute($insert);
    }

    json_response(['success' => true, 'message' => 'Item added to cart.']);
}

if ($action === 'remove') {
    $cartId = (int) ($_POST['cart_id'] ?? 0);
    $stmt = mysqli_prepare($conn, 'DELETE FROM cart WHERE id = ? AND user_id = ?');
    mysqli_stmt_bind_param($stmt, 'ii', $cartId, $userId);
    mysqli_stmt_execute($stmt);
    json_response(['success' => true, 'message' => 'Item removed.']);
}

if ($action === 'update') {
    $cartId = (int) ($_POST['cart_id'] ?? 0);
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
    $stmt = mysqli_prepare($conn, 'UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?');
    mysqli_stmt_bind_param($stmt, 'iii', $quantity, $cartId, $userId);
    mysqli_stmt_execute($stmt);
    json_response(['success' => true, 'message' => 'Cart updated.']);
}

$stmt = mysqli_prepare($conn, 'SELECT c.id, c.quantity, p.title, p.price FROM cart c JOIN products p ON p.id = c.product_id WHERE c.user_id = ?');
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$items = [];
$total = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $row['subtotal'] = $row['quantity'] * $row['price'];
    $total += $row['subtotal'];
    $items[] = $row;
}

json_response(['success' => true, 'items' => $items, 'total' => $total]);
?>
