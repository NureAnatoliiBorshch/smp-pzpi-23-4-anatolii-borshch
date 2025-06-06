<?php
include 'session.php';

$showThankYou = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        $id = (int)$_POST['remove'];
        unset($cart[$id]);
        $_SESSION['cart'] = $cart;
        header("Location: /cart");
        exit;
    }

    if (isset($_POST['purchase'])) {
        $cart = [];
        $_SESSION['cart'] = [];
        $showThankYou = true;
    }

    if (isset($_POST['cancel'])) {
        $cart = [];
        $_SESSION['cart'] = [];
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Продовольчий магазин "Весна"</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>  
</head>
<body>
<main>
    <div class="container text-center">
        <h2 class="mb-4">Your cart</h2>

        <?php if ($showThankYou): ?>
            <div class="alert alert-success">
                <h3>Дякуємо за покупку!</h3>
                <a href="products.php" class="btn btn-primary mt-3">Продовжити покупки</a>
            </div>
        <?php elseif (empty($cart)): ?>
            <h3>Empty</h3>
            <a href="products.php" class="btn btn-primary btn-lg">Перейти до покупок</a>
        <?php else: ?>
            <form method="post">
                <table class="table table-bordered w-75 mx-auto">
                    <thead class="table-light">
                        <tr><th>id</th><th>Name</th><th>Price</th><th>Count</th><th>Sum</th><th></th></tr>
                    </thead>
                    <tbody>
                    <?php $total = 0; foreach ($cart as $id => $qty):
                        $product = $products[$id];
                        $sum = $qty * $product['price'];
                        $total += $sum;
                    ?>
                    <tr>
                        <td><?= $id ?></td>
                        <td><?= $product['name'] ?></td>
                        <td><?= $product['price'] ?> грн</td>
                        <td><?= $qty ?></td>
                        <td><?= $sum ?> грн</td>
                        <td><button name="remove" value="<?= $id ?>" class="btn btn-sm btn-danger">X</button></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?= $total ?> грн</td>
                        <td></td>
                    </tr>   
                    </tbody>
                </table>
                <button name="purchase" class="btn btn-success mt-4 me-2">Pay</button>
                <button name="cancel" class="btn btn-warning mt-4">Cancel all</button>
            </form>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
