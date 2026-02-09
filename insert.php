<?php
require 'connection.php';

// Correct POST check
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Create folder if not exists
    if (!is_dir('profile')) {
        mkdir('profile', 0777, true);
    }

    // Get form data
    $name = $_POST['name'];
    $gender = $_POST['gender'];

    // Handle file upload
    $file = $_FILES['profile']['name'];
    $tmp_name = $_FILES['profile']['tmp_name'];
    $path = 'profile/' . time() . '_' . $file;

    move_uploaded_file($tmp_name, $path);

    // Insert into database
    $insert = "INSERT INTO tbl_student (name, gender, profile) 
               VALUES ('$name', '$gender', '$path')";
    $ex = $conn->query($insert);

    // Get the last inserted ID (your old method)
    $select_id = "SELECT id FROM tbl_student ORDER BY id DESC LIMIT 1";
    $rs = $conn->query($select_id);
    $row = mysqli_fetch_assoc($rs);

    // Return the new ID to AJAX
    echo $row['id'];
}
?>
