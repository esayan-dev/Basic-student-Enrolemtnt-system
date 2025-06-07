<?php
require_once 'db.php';

$name = $_POST['name'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$email = $_POST['email'] ?? '';
$gender = $_POST['gender'] ?? '';
date_default_timezone_set('Asia/Kolkata');
$timestamp = date('Y-m-d H:i:s');

if ($name && $mobile && $email && $gender) {
    $stmt = $db->prepare("INSERT INTO students (name, mobile, email, gender,enrolled_at) VALUES (?, ?, ?, ?, ?)");
  
    $stmt->execute([$name, $mobile, $email, $gender, $timestamp]);
    // Redirect to the admission list page
    echo "✅ Admission Successfully.";
} else {
    echo "❌ All fields are required.";
}
?>
