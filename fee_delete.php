<?php
if (isset($_GET['id'])){
    require_once "dbo.php";
    deleteRecord("fees",$_GET['id']);

}else{
    echo "please give proper information";
}

header("Location: fee_list.php");
exit();
?>