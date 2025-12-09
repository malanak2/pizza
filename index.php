<?php
include 'Enums.php';
?>
<?php session_start();
// Read flash messages set by order.php / other pages
$order_success = $_SESSION['order_success'] ?? null;
$order_errors = $_SESSION['order_errors'] ?? null;
$order_old = $_SESSION['order_old'] ?? null;

// If there are validation errors, show them on the Cart page per spec
if (!empty($order_errors)) {
header('Location: Cart.php');
exit;
}

// Clear ephemeral session flash values so they don't persist
unset($_SESSION['order_success'], $_SESSION['order_errors'], $_SESSION['order_old']);
?>


<!DOCTYPE html>
<html>
    <head>
        <title>
            Pizzeria Mario
        </title>


        <style>
            body {
                background-color: #f7f5e8; /* Light cream/parchment color */
                font-family: 'Inter', sans-serif;
                min-height: 100vh;
            }

            /* Custom style for the main application container */
            .pizzeria-container {
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                border: 4px solid #CD212A; /* Italian Red border */
                max-width: 500px; /* Kept max-width for the builder */
                margin: 0 auto; /* Removed top/bottom margin, controlled by outer div */
                padding: 2rem;
                flex-grow: 0; /* Prevents it from stretching in the flex layout */
            }

            /* Custom style for the rustic title */
            .rustic-title {
                font-family: 'Homemade Apple', cursive;
                font-size: 2.5rem;
                color: #008C45; /* Italian Green */
                text-shadow: 2px 2px 0 #CD212A; /* Red outline effect */
                line-height: 1.2;
                margin-bottom: 1.5rem;
            }

            /* Styling for the topping pills (Red/Green/White) */
            .topping-pill {
                display: inline-flex;
                align-items: center;
                background-color: #CD212A; /* Red background */
                color: white;
                padding: 0.25rem 0.75rem;
                margin: 0.25rem;
                border-radius: 9999px; /* Full rounded corners */
                font-size: 0.875rem;
                font-weight: 600;
                border: 1px solid #008C45; /* Green border */
            }

            .topping-pill button {
                background: #f7f5e8;
                color: #CD212A;
                border: none;
                border-radius: 50%;
                width: 1.25rem;
                height: 1.25rem;
                margin-left: 0.5rem;
                cursor: pointer;
                font-weight: bold;
                line-height: 1;
                transition: background-color 0.2s;
            }

            .topping-pill button:hover {
                background-color: #008C45;
                color: white;
            }

            /* Style for the buttons and selects to give them dimension */
            select, input[type="submit"] {
                border: 2px solid #008C45;
                transition: all 0.2s ease-in-out;
            }
            select:focus, input[type="submit"]:focus {
                box-shadow: 0 0 0 3px rgba(205, 33, 42, 0.4); /* Red focus ring */
            }

            input[type="submit"] {
                background-color: #008C45; /* Green button */
                color: white;
                font-weight: bold;
            }
            input[type="submit"]:hover {
                background-color: #007038;
                transform: translateY(-1px);
            }
        </style>
    </head>
    <body>
        <a href="Cart.php">Košík </a>
        <?php if ($order_success): ?>
            <div class="flash-success"><?= htmlspecialchars($order_success) ?></div>
        <?php endif; ?>

        <div id="pizzaBuilder">
        <form action="AddPizza.php" method="POST">
            <h2>Pizza</h2>
            <label for="base">Base</label><select name="base" id="base">
                <option value="" disabled selected>Select Base</option>
                <?php
                foreach (Enums\Base::cases() as $case) {
                    ?>
                <option value="<?= $case->name;?>"><?= $case->name;?></option>
                <?php
                }
                ?>
            </select>
                <select id="toppingssel" onchange="addTopping();" onfocus="this.selectedIndex = 0;">
                    <option value="" disabled selected>Select Topping</option>
                    <?php
                    foreach (Enums\Topping::cases() as $case) {
                        ?>
                        <option value="<?= $case->name;?>"><?= $case->name;?></option>
                        <?php
                    }
                    ?>
                </select>
                <h4>Your Selected Toppings:</h4>
                <div id="selectedtoppingsdisplay">
                </div>

                <input type="hidden" name="toppingsdata" id="toppingsdata">
            <input type="submit" value="Add toppings">

        </form>
    </div>
        <script>
            let selectedToppings = [];
            function addTopping() {
                const selector = document.getElementById('toppingssel');
                const topping = selector.value;
                selectedToppings.push(topping);
                updateDisplayAndHiddenField();
                selector.value = "";
            }
            function removeTopping(toppingToRemove) {
                selectedToppings.splice(toppingToRemove, 1);
                updateDisplayAndHiddenField();
            }


            function updateDisplayAndHiddenField() {
                const displayArea = document.getElementById('selectedtoppingsdisplay');
                const hiddenInput = document.getElementById('toppingsdata');

                displayArea.innerHTML = '';

                if (selectedToppings.length === 0) {
                    displayArea.innerHTML = '<p>No toppings selected .</p>';
                } else {
                    selectedToppings.forEach((topping, i) => {
                        const pill = document.createElement('span');
                        pill.className = 'topping-pill';
                        pill.id = `topping-pill-${i}`
                        pill.innerHTML = `
                ${topping}
                <button type="button" onclick="removeTopping('${i}')">X</button>
            `;
                        displayArea.appendChild(pill);
                    });
                }

                hiddenInput.value = JSON.stringify(selectedToppings);
            }

            updateDisplayAndHiddenField();
        </script>
    </body>
</html>
