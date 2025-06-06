<?php
session_start();

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$page = $request === '/' ? '/' : rtrim($request, '/');

$allowed = ['/', '/login'];

require_once("pages/static/header.php");

$isUserLoggedIn = isset($_SESSION['username']) && !empty($_SESSION['username']);

if (!$isUserLoggedIn && !in_array($page, $allowed)) {
    require_once("pages/page404.php");
    require_once("pages/static/footer.php");
    exit;
}

switch ($page) {
    case '/':
        require_once("pages/home.php");
        break;
    case '/login':
        require_once("pages/login.php");
        break;
    case '/logout':
        require_once("logout.php");
        break;
    case '/products':
        require_once("pages/products.php");
        break;
    case '/cart':
        require_once("pages/cart.php");
        break;
    case '/profile':
        require_once("pages/profile.php");
        break;
    default:
        require_once("pages/page404.php");
        break;
}

require_once("pages/static/footer.php");
?>
