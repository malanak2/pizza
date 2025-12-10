<?php
include 'Enums.php';
include 'Pizza.php';
?>
<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['order_errors'] = ['Cart is empty. Add at least one pizza before ordering.'];
    header('Location: Cart.php');
    exit;
}

$input = [
    'customer_name' => trim($_POST['customer_name'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'phone' => trim($_POST['phone'] ?? ''),
    'street' => trim($_POST['street'] ?? ''),
    'city' => trim($_POST['city'] ?? ''),
    'postal' => trim($_POST['postal'] ?? ''),
];

$errors = [];

if (strlen($input['customer_name']) < 2) {
    $errors[] = 'Name must have at least 2 characters.';
}
if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address.';
}
if (strlen($input['street']) < 5) {
    $errors[] = 'Street and number is too short.';
}
if (strlen($input['city']) < 2) {
    $errors[] = 'City is too short.';
}
if (!preg_match('/^[0-9A-Za-z \-]{3,10}$/', $input['postal'])) {
    $errors[] = 'Postal code is invalid (3–10 letters/numbers allowed).';
}
if ($input['phone'] !== '' && !preg_match('/^[0-9+\-() ]{6,20}$/', $input['phone'])) {
    $errors[] = 'Phone number format is invalid.';
}

if (!empty($errors)) {
    $_SESSION['order_errors'] = $errors;
    $_SESSION['order_old'] = $input;
    header('Location: Cart.php');
    exit;
}

unset($_SESSION['cart']);
$_SESSION['order_success'] = 'Order placed successfully. Thank you!';

header('Location: index.php');
exit;
