<?php
try{
  $conn=mysqli_connect('localhost','root','','db_2');
}
catch(Exception $th){
  echo $th ->getmessage();
}