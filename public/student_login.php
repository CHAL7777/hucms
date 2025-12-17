<?php
session_start();
require '../config/config.php';

// Only logged-in staff can change their password
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";
$status_class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['staff_id'];

    // 1. Verify the current password first
    $stmt = $pdo->prepare("SELECT password FROM admin_users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user && $current_password === $user['password']) {
        // 2. Check if new passwords match
        if ($new_password === $confirm_password) {
            // 3. Update the database
            $update = $pdo->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
            if ($update->execute([$new_password, $user_id])) {
                $msg = "✅ Password updated successfully!";
                $status_class = "success";
            } else {
                $msg = "❌ Error updating password.";
                $status_class = "error";
            }
        } else {
            $msg = "❌ New passwords do not match.";
            $status_class = "error";
        }
    } else {
        $msg = "❌ Current password is incorrect.";
        $status_class = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password | Cafe Manager</title>
    <style>
        /* Reusing your existing style from login.php */
        body { font-family: 'Segoe UI', sans-serif; background: #764ba2; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 12px; width: 100%; max-width: 400px; text-align: center; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 14px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .msg { padding: 10px; margin-bottom: 15px; border-radius: 5px; font-size: 14px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="card">
        <h2>🔑Change Password</h2>
        <?php if($msg): ?>
            <div class="msg <?= $status_class ?>"><?= $msg ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="password" name="current_password" placeholder="Current Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            <button type="submit">Update Password</button>
        </form>
        <p><a href="dashboard.php" style="color: #666; font-size: 13px; text-decoration: none;">🏠 Back to Dashboard</a></p>
    </div>
</body>
</html>