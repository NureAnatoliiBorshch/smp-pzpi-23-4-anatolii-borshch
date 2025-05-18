<?php
session_start();

require_once 'context.php';
$pdo = getDbConnection();

$products = [];
$stmt = $pdo->query("SELECT id, name, price FROM products");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $products[$row['id']] = ['name' => $row['name'], 'price' => $row['price']];
}

$cart = $_SESSION['cart'] ?? [];

?>
