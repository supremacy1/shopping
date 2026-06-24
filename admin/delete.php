<?php
include "../db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // First retrieve image name to delete it from uploads folder if needed
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $image = $row['image'];
        if ($image && file_exists("../uploads/" . $image)) {
            unlink("../uploads/" . $image);
        }
    }
    $stmt->close();

    // Now delete product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: products.php");
exit;
?>
