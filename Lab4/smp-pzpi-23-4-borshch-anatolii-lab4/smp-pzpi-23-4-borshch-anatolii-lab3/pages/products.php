<?php
include 'session.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $qty = (int)$qty;
        if (isset($products[$id])) {
            if ($qty < 0 || $qty > 100) {
                $error = "Помилка: кількість товару #$id має бути від 1 до 100.";
                break;
            }

            if ($qty == 0) continue;

            $cart[$id] = ($cart[$id] ?? 0) + $qty;
        }
    }

    if (empty($error)) {
        $_SESSION['cart'] = $cart;
        header("Location: /cart");
        exit;
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
        .error {
            color: red;
            text-align: center;
            margin-top: 1em;
        }
    </style>    
</head>
<body>
<main>
    <div class="container text-center">
        <form method="post">
            <table class="table table-bordered w-75 mx-auto">
                <thead class="table-light">
                </thead>
                <tbody>
                    <?php foreach ($products as $id => $p): ?>
                    <tr>
                        <td><?= $id ?></td>
                        <td><?= $p['name'] ?></td>
                        <td><?= $p['price'] ?> грн</td>
                        <td><input type="number" name="qty[<?= $id ?>]" value="0" min="0" max="100" class="form-control" style="width:80px;margin:auto;" required></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" name="add_to_cart" class="btn btn-success mt-3">Send</button>
        </form>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
