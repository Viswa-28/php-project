<?php
$server = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        if ($server == "index.php") echo "Home";
        elseif ($server == "products.php") echo "Products";
        elseif ($server == "cart.php") echo "Cart";
        ?>
</title>

    <!-- Bootstrap & Icons -->

    <link rel="stylesheet" href="./css/common.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <?php
    if ($server == "index.php") echo '<link rel="stylesheet" href="css/style.css">';
    elseif($server == "footer.php") echo '<link rel="stylesheet" href="../css/footer.css">';
    elseif($server == "navbar.php") echo '<link rel="stylesheet" href="../css/style.css">';
 
    
    elseif ($server == "product.php") echo '<link rel="stylesheet" href="css/product.css">';
    elseif ($server == "cart.php") echo '<link rel="stylesheet" href="./css/cart.css">';
    elseif ($server == 'stocks.php') echo '<link rel="stylesheet" href="../css/stocks.css">';
    elseif ($server == 'add-stocks.php'|| $server == 'edit-stocks.php') echo '<link rel="stylesheet" href="../css/addstocks.css">';
    elseif($server == 'enquiry.php') echo '<link rel="stylesheet" href="../css/enquiry.css">';
    elseif($server == 'users.php') echo '<link rel="stylesheet" href="../css/users.css">';
    elseif($server == 'delete-users.php') echo '<link rel="stylesheet" href="../css/users.css">';
    elseif($server == 'admin.php') echo '<link rel="stylesheet" href="../css/admin.css">';
    elseif($server == 'all.php') echo '<link rel="stylesheet" href="./css/all.css">';

    ?>


</head>


<body>





    <!-- cdn -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- aos -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="<?php
    if($server == "index.php") echo './js/script.js';
    elseif ($server == "products.php") echo './js/products.js';
    elseif ($server == "cart.php") echo './js/cart.js';
    ?>
    "></script>

</body>



</html>