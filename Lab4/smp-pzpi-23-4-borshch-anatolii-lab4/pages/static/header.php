<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<header class="bg-light py-3 mb-4 shadow">
    <div class="container-fluid">
        <div class="row text-center">
            <div class="col text-start">
                <a href="/" class="text-decoration-none fs-5"><i class="bi bi-house-door"></i> Home</a>
            </div>
            <div class="col text-start">
                    <span class="ms-1">|</span>
            </div>
            <div class="col text-center">
                <a href="/products" class="text-decoration-none fs-5"><i class="bi bi-list"></i> Products</a>
            </div>
            <div class="col text-end">
                    <span class="ms-1">|</span>
            </div>
            <div class="col text-end">
                <a href="/cart" class="text-decoration-none fs-5"><i class="bi bi-cart"></i> Cart</a>
            </div>
            <div class="col text-start">
                    <span class="ms-1">|</span>
            </div>
            <?php if (!isset($_SESSION['username'])) : ?>
            <div class="col text-end">
                <a href="/login" class="text-decoration-none fs-5"><i class="bi bi-person-circle"></i> LogIn</a>
            </div>
            <?php else : ?>
            <div class="col text-end">
                <a href="/profile" class="text-decoration-none fs-5"><i class="bi bi-person-circle"></i> Profile</a>
            </div>
            <div class="col text-start">
                    <span class="ms-1">|</span>
            </div>
            <div class="col text-end">
                <a href="/logout" class="text-decoration-none fs-5"><i class="bi bi-person-dash"></i> LogOut</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</header>
