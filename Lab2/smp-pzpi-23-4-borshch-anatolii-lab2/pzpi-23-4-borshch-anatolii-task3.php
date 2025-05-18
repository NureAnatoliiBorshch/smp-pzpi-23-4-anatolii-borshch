<?php
require_once 'constants.php';
$cart = [];

function visual_length($str) {
    preg_match_all('/./u', $str, $matches);
    return count($matches[0]);
}

function select_product() {
    global $products, $cart;

    while (true) {
        echo "№  НАЗВА                 ЦІНА\n";

        foreach ($products as $id => $product) {
            $name = $product['name'];
            $price = $product['price'];
            $length = visual_length($name);
            $padding = 22 - $length;
            if ($padding < 0) $padding = 0;
        
            echo $id . '  ' . $name . str_repeat(' ', $padding) . $price . "\n";
        }

        echo "   -----------\n";
        echo "0  ПОВЕРНУТИСЯ\n";
        echo "Виберіть товар: ";

        $productChoice = (int)trim(fgets(STDIN));
        if ($productChoice === 0) break;

        if (!isset($products[$productChoice])) {
            echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n";
            continue;
        }

        echo "Вибрано: " . $products[$productChoice]['name'] . "\n";
        echo "Введіть кількість, штук: ";
        $qty = (int)trim(fgets(STDIN));

        if ($qty === 0) {
            unset($cart[$productChoice]);
            echo "ВИДАЛЕНО З КОШИКА\n";
        } elseif ($qty > 0 && $qty <= 99) {
            $cart[$productChoice] = $qty;
            echo "У КОШИКУ:\n";
            echo "НАЗВА                 КІЛЬКІСТЬ\n";
            foreach ($cart as $id => $count) {
                $name = $products[$id]['name'];
                $length = visual_length($name);
                $padding = 22 - $length;
                if ($padding < 0) $padding = 0;

                echo $name . str_repeat(' ', $padding) . "$count\n";
            }
            echo "\n";
        } else {
            echo "ПОМИЛКА! НЕКОРЕКТНА КІЛЬКІСТЬ\n";
        }
    }

    ;
}

function print_receipt() {
    global $cart, $products;
    
    if (empty($cart)) {
        echo "КОШИК ПОРОЖНІЙ\n";
        return;
    }

    echo "№  НАЗВА                 ЦІНА  КІЛЬКІСТЬ  ВАРТІСТЬ\n";
    $total = 0;
    $num = 0;
    foreach ($cart as $id => $qty) {
        $name = $products[$id]['name'];
        $price = $products[$id]['price'];
        $cost = $price * $qty;
        $total += $cost;

        $length = visual_length($name);
        $padding = 22 - $length;
        if ($padding < 0) $padding = 0;

        echo sprintf("%-3d%s%s%-5d %-10d %-9d\n",
            $num,
            $name,
            str_repeat(' ', $padding),
            $price,
            $qty,
            $cost
        );
        $num++;
    }
    echo "РАЗОМ ДО CПЛАТИ: $total\n";
}

function setup_profile() {
    global $user;

    while (true) {
        echo "Ваше імʼя: ";
        $input = trim(fgets(STDIN));

        if (!preg_match("/^[А-Яа-яЁёЇїІіЄєҐґA-Za-z\s\-\x27]+$/u", $input)) {
            echo "ПОМИЛКА! Імʼя може містити лише літери, апостроф ‘ʼ’, дефіс ‘-’, пробіл\n\n";
            continue;
        }

        break;
    }

    while (true) {
        echo "Ваш вік: ";
        $input_age = fgets(STDIN);
        $age = (int)preg_replace('/\D+/', '', trim($input_age));

        if ($age < 7 || $age > 150) {
            echo "ПОМИЛКА! Користувач повинен мати від 7 до 150.\n";
            echo "\n";
        } else {
            break;
        }
    }

    echo "\n";
}

print_header();

while (true) {
    ptint_menu_options();
    $choice = trim(fgets(STDIN));

    switch ($choice) {
        case "1":
            select_product();
            break;

        case "2":
            print_receipt();
            break;

        case "3":
            setup_profile();
            break;

        case "0":
            exit(0);

        default:
            echo "ПОМИЛКА! Введіть правильну команду\n";
            break;
    }
}
