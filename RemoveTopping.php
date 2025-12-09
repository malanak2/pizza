<?php
include 'Enums.php';
include 'Pizza.php';
?>
<?php
session_start();

if (!isset($_POST) || !isset($_SESSION['cart'])) {
    connection_aborted();
}

if (!isset($_POST['pizza'])) {
    connection_aborted();
}

if (!isset($_POST['topping'])) {
    // Source - https://stackoverflow.com/a
    // Posted by Simone, modified by community. See post 'Timeline' for change history
    // Retrieved 2025-12-09, License - CC BY-SA 3.0
    unset($_SESSION["cart"][$_POST['pizza']]);
    $_SESSION["cart"] = array_values($_SESSION["cart"]);
    return;
}
var_dump($_SESSION["cart"]);
var_dump($_POST["pizza"]);
var_dump($_POST["topping"]);
unset($_SESSION["cart"][$_POST['pizza']]->toppings[$_POST['topping']]);
$_SESSION["cart"][$_POST['pizza']]->toppings = array_values($_SESSION["cart"][$_POST['pizza']]->toppings);
return;
