<?php
require 'connection.php';
$id=$_POST['id'];
$delete=" DELETE FROM tbl_student WHERE id='$id'";
$conn->query($delete);