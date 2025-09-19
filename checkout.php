<?php
include('./include/config.php');
include('./include/header.php');

if(isset($_POST['checkout'])) {
   $name=$_POST['name'];
   $price=$_POST['total'];
   $image=$_POST['image'];
   $description=$_POST['description'];
   $stock=$_POST['stock'];
   $category=$_POST['category'];
   $size=$_POST['size'];
   $total =$_POST['total'];
   $sql="INSERT INTO checkout(name,price,image,description,stock,category,size) VALUES('$name','$total','$image','$description','$stock','$category','$size')";
   $result=$conn->query($sql);

   if($result){
    echo "<script>alert('Order successfully confirmed');window.location.href='index.php';</script>";
   }
}

?>