<?php
include "../db.php";

if (isset($_POST['submit'])) {

    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price_naira = $_POST['price_naira'];
    $price_dollar = $_POST['price_dollar'];
    $health_benefit = trim($_POST['health_benefit']);
    $how_to_use = trim($_POST['how_to_use']);

    // Image Upload
    $image = "";

    if (!empty($_FILES['image']['name'])) {

        $image = time() . "_" . basename($_FILES['image']['name']);
        $tmp = $_FILES['image']['tmp_name'];

        $uploadDir = "../uploads/";

        move_uploaded_file($tmp, $uploadDir . $image);
    }

    $stmt = $conn->prepare("
        INSERT INTO products
        (name, description, price_naira, price_dollar, image, health_benefit, how_to_use)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssddsss",
        $name,
        $desc,
        $price_naira,
        $price_dollar,
        $image,
        $health_benefit,
        $how_to_use
    );

    if($stmt->execute()){
        echo "<div class='alert alert-success'>
                Product added successfully.
              </div>";
    }else{
        echo "<div class='alert alert-danger'>
                ".$stmt->error."
              </div>";
    }

    $stmt->close();
}
?>

<?php include "layout.php"; ?>

<div class="card shadow">

    <div class="card-header bg-primary text-white">
        <h4>Add Product</h4>
    </div>

    <div class="card-body">

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label>Product Name</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea
                    name="description"
                    class="form-control"
                    rows="4"
                    required></textarea>
            </div>

            <div class="mb-3">
                <label>Health Benefit</label>
                <textarea
                    name="health_benefit"
                    class="form-control"
                    rows="4"
                    ></textarea>
            </div>

            <div class="mb-3">
                <label>How to Use</label>
                <textarea
                    name="how_to_use"
                    class="form-control"
                    rows="4"
                    ></textarea>
            </div>

            <div class="row">

                <div class="col-md-6">

                    <label>Price (₦ Naira)</label>

                    <input
                        type="number"
                        step="0.01"
                        name="price_naira"
                        class="form-control"
                        placeholder="Enter Naira Price"
                        required>

                </div>

                <div class="col-md-6">

                    <label>Price ($ Dollar)</label>

                    <input
                        type="number"
                        step="0.01"
                        name="price_dollar"
                        class="form-control"
                        placeholder="Enter Dollar Price"
                        required>

                </div>

            </div>

            <div class="mt-3">

                <label>Product Image</label>

                <input
                    type="file"
                    name="image"
                    class="form-control"
                    accept="image/*">

            </div>

            <br>

            <button
                type="submit"
                name="submit"
                class="btn btn-success">

                Add Product

            </button>

        </form>

    </div>

</div>

<?php include "footer.php"; ?>