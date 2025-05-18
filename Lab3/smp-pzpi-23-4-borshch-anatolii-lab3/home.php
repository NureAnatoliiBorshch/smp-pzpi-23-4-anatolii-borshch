<?php include 'session.php'; ?>
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
<?php include 'header.php'; ?>
<main>
    <div class="container text-center mt-5">
        <h2>ПРОДОВОЛЬЧИЙ МАГАЗИН "ВЕСНА"</h2>
        <a href="products.php" class="btn btn-primary btn-lg">Перейти до покупок</a>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
