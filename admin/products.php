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
            <th style="width: 30%;">Name</th>
            <th>Price (₦)</th>
            <th>Price ($)</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td>
                <?php if (!empty($row['image']) && file_exists("../uploads/" . $row['image'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" width="60" alt="<?= htmlspecialchars($row['name']) ?>">
                <?php endif; ?>
            </td>
            <td><?= $row['name'] ?></td>
            <td>₦<?= number_format($row['price_naira'], 2) ?></td>
            <td>$<?= number_format($row['price_dollar'], 2) ?></td>
            <td>
                <a href="../product.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm" target="_blank">View</a>
                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include "footer.php"; ?>