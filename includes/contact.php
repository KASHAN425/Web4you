<?php
require_once __DIR__ . '/helpers.php';

$name = sanitize($_POST['name'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$message = sanitize($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    json_response(['success' => false, 'message' => 'All fields are required.'], 422);
}

$stmt = mysqli_prepare($conn, 'INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $message);
mysqli_stmt_execute($stmt);

json_response(['success' => true, 'message' => 'Thanks! We will contact you soon.']);
?>
