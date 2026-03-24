<?php
require_once __DIR__ . '/helpers.php';

$search = '%' . sanitize($_GET['search'] ?? '') . '%';
$category = (int) ($_GET['category'] ?? 0);
$minPrice = (float) ($_GET['min_price'] ?? 0);
$maxPrice = (float) ($_GET['max_price'] ?? 1000000);

$query = 'SELECT p.id, p.title, p.description, p.price, p.image, p.demo_link, c.name AS category
          FROM products p
          LEFT JOIN categories c ON c.id = p.category_id
          WHERE p.title LIKE ? AND p.price BETWEEN ? AND ?';
$params = [$search, $minPrice, $maxPrice];
$types = 'sdd';

if ($category > 0) {
    $query .= ' AND p.category_id = ?';
    $params[] = $category;
    $types .= 'i';
}

$query .= ' ORDER BY p.created_at DESC';
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

json_response(['success' => true, 'products' => $products]);
?>
