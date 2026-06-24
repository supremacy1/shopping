<?php
include "../db.php";
include "layout.php";

$result = $conn->query("SELECT * FROM products");
?>

<h3>Products</h3>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td>
                <img src="../uploads/<?= $row['image'] ?>" width="60">
            </td>
            <td><?= $row['name'] ?></td>
            <td>₦<?= number_format($row['price']) ?></td>
            <td>
                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include "footer.php"; ?>