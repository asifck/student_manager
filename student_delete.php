<?php
if (isset($_GET['id'])){
    require_once "dbo.php";
    deleteRecord('student',$_GET['id']);
}else{
    echo "please provide specific data";
}
header("Location: student_list.php");
exit();
?>