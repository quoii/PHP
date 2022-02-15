<?php 
    //gắn trang hằng số
    include('../config/constants.php');

    if(isset($_GET['id']) && isset($_GET['image_name']))//sử dụng && hoặc And
    {
        //quá trình xóa
        //echo"Process to delete";

        //1.lấy id và tên hình ảnh
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        //2.xóa hình ảnh nếu có sẵn
        //kiểm tra xem hình ảnh có sẵn hay không và chỉ xóa nếu có
        if($image_name != "")
        {
            // nó có hình ảnh và cần xóa khỏi thư mục
            // lấy đường dẫn hình ảnh
            $path = "../images/food/".$image_name;
            
            // xóa tệp hình ảnh khỏi thư mục
            $remove = unlink($path);

            // kiểm tra xem hình ảnh có bị xóa hay không
            if($remove==false)
            {
                //thất bại khi xóa ảnh
                $_SESSION['upload']="<div class='error'>Faile to remove image Files.</div>";
                //chuyển hướng để quản lý thực phẩm
                header('location:'.SITEURL.'admin/manage-food.php');
                //dừng chương trình lại
                die();

            }
        }

        //3.xóa thực phẩm khỏi cơ sở dữ liệu
        $sql = " DELETE FROM tbl_food WHERE id=$id";
        var_dump($sql);

        // thực hiện truy vấn
        $res=mysqli_query($conn,$sql);

        //kiểm tra xem truy vấn có được thực thi hay không và đặt thông báo phiên tương ứng
        //4.chuyển hướng để quản lý thực phẩm với thông báo phiên
        if($res==true)
        {
            //xóa thức ăn
            $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully.</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }
        else
        {
            //thất bại khi xóa thức ăn
            $_SESSION['delete'] = "<div class='error'>Failed to delete food.</div>";
            header('location:'.SITEURL.'admin/manage-food.php'); 
        }

        //4.chuyển hướng để quản lý thực phẩm với thông báo phiên

    }
    else
    {
        //chuyển hướng đến quản lý trang thực phẩm
        $_SESSION['unauthorize']="<div class='error'>Unauthorized Accces.</div>";
        header('location:'.SITEURL.'admin/manage-food.php');
    }


?>