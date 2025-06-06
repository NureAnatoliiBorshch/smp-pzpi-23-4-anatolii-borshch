<?php
if (!isset($_SESSION['username'])) {
    header('Location: /login');
    exit;
}

$profileFile = __DIR__ . '/../profile_data.php';
$profile = file_exists($profileFile) ? include $profileFile : [];

$error = '';
$success = '';

function utf8_strlen($string) {
    return preg_match_all('/./us', $string, $matches);
}

function isValidName($name) {
    return preg_match("/^[\p{L} '\’ʼ-]+$/u", $name);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $birthDate = $_POST['birth_date'] ?? '';
    $about = trim($_POST['about'] ?? '');

    if ($firstName === '' || $lastName === '' || $birthDate === '' || $about === '') {
        $error = 'Усі поля повинні бути заповнені.';
    } 
    elseif (!isValidName($firstName) || !isValidName($lastName)) {
        $error = 'Ім’я та прізвище можуть містити лише літери, пробіли та апострофи.';
    } 
    elseif (utf8_strlen($about) > 2200) {
        $error = 'Опис не повинен перевищувати 2200 символів.';
    } 
    else {
        $birthDateTime = DateTime::createFromFormat('Y-m-d', $birthDate);
        if (!$birthDateTime) {
            $error = 'Невірний формат дати народження.';
        } 
        else {
            $age = (new DateTime())->diff($birthDateTime)->y;
            if ($age < 7 || $age > 150) {
                $error = 'Користувач повинен бути у віці від 7 до 150 років.';
            }
        }
    }

    $newPhotoPath = $profile['photo'];
    if (!$error && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExtensions)) {
            $error = 'Дозволені формати фото: JPG, JPEG, PNG.';
        } 
        else {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $uploadDir = __DIR__ . '/../photo/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid('photo_', true) . '.' . $ext;
            $fullPath = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $fullPath)) {
                $error = 'Помилка завантаження файлу.';
            } 
            else {
                if (!empty($profile['photo']) && file_exists($profile['photo']) && $profile['photo'] !== $profileFile) {
                    unlink($profile['photo']);
                }
                $newPhotoPath = 'photo/' . $fileName;
            }
        }
    }

    if (!$error) {
        $profile = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'birth_date' => $birthDate,
            'about' => $about,
            'photo' => $newPhotoPath,
        ];

        $code = "<?php\nreturn " . var_export($profile, true) . ";\n";
        if (file_put_contents($profileFile, $code) === false) {
            $error = 'Помилка збереження даних.';
        } 
        else {
            $success = 'Профіль успішно оновлено.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8" />
    <title>Профіль користувача</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
<main class="container mt-4">
    <form method="post" enctype="multipart/form-data" novalidate>
        <div class="row">
            <div class="col-md-4 text-center">
            <img src="<?= htmlspecialchars($profile['photo'] ?? 'photo/default.png') ?>" alt="Фото профілю" class="img-fluid rounded mb-3" style="max-height: 250px;">
                <input type="file" id="photoInput" name="photo" accept="image/*" style="display: none;">
                <button type="button" class="btn btn-primary w-100" onclick="document.getElementById('photoInput').click();">Upload</button>
            </div>

            <div class="col-md-8">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="first_name" class="form-label">Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required minlength="2" value="<?= htmlspecialchars($profile['first_name']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="last_name" class="form-label">Surname</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" required minlength="2" value="<?= htmlspecialchars($profile['last_name']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="birth_date" class="form-label">Date of birth</label>
                        <input type="date" name="birth_date" id="birth_date" class="form-control" required value="<?= htmlspecialchars($profile['birth_date']) ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="about" class="form-label">Brief description</label>
                    <textarea name="about" id="about" class="form-control" required minlength="50" rows="6"><?= htmlspecialchars($profile['about']) ?></textarea>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <div class="text-end">
                    <button type="submit" name="save_profile" class="btn btn-success">Сохранить</button>
                </div>
            </div>
        </div>
    </form>
</main>
<script>
    document.getElementById('photoInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.querySelector('img[alt="Фото профілю"]');
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
</body>
</html>
