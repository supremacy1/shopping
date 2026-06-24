<?php
include "../db.php";

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    // IMAGE UPLOAD
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    $uploadDir = "../uploads/";
    move_uploaded_file($tmp, $uploadDir . $image);

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image)
                            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $desc, $price, $image);
    $stmt->execute();

    echo "<div class='alert alert-success'>Product added successfully</div>";
}
?>

<?php include "layout.php"; ?>

<h3>Add Product</h3>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" class="form-control mb-2" placeholder="Product Name">

    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>

    <input type="number" name="price" class="form-control mb-2" placeholder="Price">

    <input type="file" name="image" class="form-control mb-3">

    <button name="submit" class="btn btn-primary">Add Product</button>
</form>

<?php include "footer.php"; ?>