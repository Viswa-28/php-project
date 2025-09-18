<?php
include('./include/config.php');
include('./include/header.php');
include('./include/navbar.php');

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '
    <h1 class="text-center text-white mb-5">All Collections</h1>
    <div class="row w-100 d-flex flex-wrap justify-content-center mt-5 container mx-auto">'; // open row only once
    
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $name = $row['name'];
        $image = $row['image'];
        $price = $row['price'];
        $description = $row['description'];
        $stock = $row['stock'];
        $category = $row['category'];

        echo '
        <div class="col-12 col-md-4 mb-4  mx-auto d-flex">
           <a href="product.php?id=' . $id . '" class="text-decoration-none d-block w-100">
               <div class="card h-100 d-flex flex-column">
                   <img src="./uploads/'.$image.'" class="card-img-top object-fit-cover" alt="Product Image" height="350px">
                   <div class="card-body d-flex flex-column">
                       <h5 class="card-title">'.$name.'</h5>
                       <p class="card-text">Category: '.$category.'</p>
                       <p class="card-text flex-grow-1">'.$description.'</p>
                       <p class="card-text fw-bold mt-auto">Price: ₹'.number_format($price, 2).'</p>
                   </div>
               </div>
           </a>
        </div>';
    }

    echo '</div>'; // close row after loop
}
?>
