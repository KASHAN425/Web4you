<?php
require_once __DIR__ . '/helpers.php';

$action = $_POST['action'] ?? '';

if ($action === 'register') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$name || !$email || strlen($password) < 6) {
        json_response(['success' => false, 'message' => 'Please provide valid details.'], 422);
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, 'INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hash);

    if (mysqli_stmt_execute($stmt)) {
        json_response(['success' => true, 'message' => 'Registration successful.']);
    }

    json_response(['success' => false, 'message' => 'Email already exists or invalid data.'], 409);
}

if ($action === 'login') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, 'SELECT id, name, password FROM users WHERE email = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
        json_response(['success' => true, 'message' => 'Login successful.']);
    }

    json_response(['success' => false, 'message' => 'Invalid credentials.'], 401);
}

if ($action === 'admin_login') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, 'SELECT id, password FROM admins WHERE username = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['admin_name'] = $username;
        json_response(['success' => true, 'message' => 'Admin login successful.']);
    }

    json_response(['success' => false, 'message' => 'Invalid admin credentials.'], 401);
}

if ($action === 'logout') {
    session_unset();
    session_destroy();
    json_response(['success' => true, 'message' => 'Logged out.']);
}

json_response(['success' => false, 'message' => 'Unsupported action.'], 400);
?>
