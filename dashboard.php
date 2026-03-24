<?php
require_once __DIR__ . '/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
$userId = (int) $_SESSION['user_id'];
$name = $_SESSION['user_name'] ?? 'User';
$orders = mysqli_query($conn, "SELECT id,total,status,created_at FROM orders WHERE user_id={$userId} ORDER BY id DESC");
?>
<!doctype html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>User Dashboard</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="css/style.css"></head>
<body><div class="container py-5"><div class="d-flex justify-content-between"><h1>Welcome, <?php echo htmlspecialchars($name); ?></h1><button onclick="logout()" class="btn btn-outline-danger">Logout</button></div>
<div class="row g-4 mt-2"><div class="col-md-6"><div class="card p-4"><h4>Profile</h4><p>Manage your purchased websites and favorites.</p></div></div><div class="col-md-6"><div class="card p-4"><h4>Notifications</h4><p>Track order status updates from here.</p></div></div></div>
<h3 class="mt-4">Order History</h3><table class="table"><thead><tr><th>ID</th><th>Total</th><th>Status</th><th>Date</th></tr></thead><tbody><?php while($o=mysqli_fetch_assoc($orders)): ?><tr><td>#<?php echo $o['id']; ?></td><td>$<?php echo $o['total']; ?></td><td><?php echo htmlspecialchars($o['status']); ?></td><td><?php echo $o['created_at']; ?></td></tr><?php endwhile; ?></tbody></table>
</div><script src="https://code.jquery.com/jquery-3.7.1.min.js"></script><script src="js/app.js"></script></body></html>
