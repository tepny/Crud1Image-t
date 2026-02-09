<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM tbl_product WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: table.php");
        exit;
    } else {
        echo "Delete failed: " . $stmt->error;
    }
}
?>
