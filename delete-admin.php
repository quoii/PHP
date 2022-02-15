<?php
//0
    include('../config/constants.php');

//1
    $id = $_GET['id'];
//2
    $sql = "DELETE FROM tbl_admin WHERE id=$id";
//3
    $res = mysqli_query ($conn , $sql);
//4
if($res==true)
{
    //echo "Admin Deleted";
    $_SESSION['delete'] = "<div class='success'>Admin Deleted Successfully.</div>";
    header('location:'.SITEURL.'admin/manage-admin.php');

}
else
{  
    //echo "Failed to Delete Admin";
    $_SESSION['delete'] = "<div class='error'>Failed to delete Admin. Try Again Later.</div>";
    header('location:'.SITEURL.'admin/manage-admin.php');
}


?>