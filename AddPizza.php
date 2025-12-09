<?php
include 'Pizza.php';
include 'Enums.php';
session_start();

if (!isset($_POST["base"]) || !isset($_POST["toppingsdata"])) {
    connection_aborted();
}
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}

$pizza = new Pizza();
$pizza->base = Enums\Base::tryFromName($_POST["base"]);
$topping = array();
$topdata = json_decode($_POST["toppingsdata"]);
foreach ($topdata as $top) {
    $topping[] = Enums\Topping::tryFromName($top);
}
$pizza->toppings = $topping;
$_SESSION["cart"][] = $pizza;
echo "Pizza added succesfully!";
?>

<a href="/">Back to main page</a>
