<?php include('partials/menu.php');?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Category</h1>

        <br><br>

        <?php

            //check whether the id is set or not
            if(isset($_GET['id']))
            {
                //Get thr ID and all other details
                //echo "Getting the data";
                $id=$_GET['id'];
                //creat SQL query to get all other details
                $sql= "SELECT * FROM tbl_category WHERE id=$id";

                //execute the query
                $res=mysqli_query($conn, $sql);

                //count the rows to check whether the id is valid or not
                $count=mysqli_num_rows($res);

                if($count==1)
                {
                    //get all the data
                    $row = mysqli_fetch_assoc($res);
                    $title=$row['title'];
                    $current_image = $row['image_name'];
                    $featured=$row['featured'];
                    $active=$row['active'];
                }
                else
                {
                    $_SESSION['no-category-found']= "<div class='error'>Category not Found.</div>";
                    header('location:'.SITEURL.'admin/manage-category.php');
                }


            }
            else
            {
                //redirect to manage Category
                header('location:'.SITEURL.'admin/manage-category.php');
            }
             
        ?>

        <form action=""method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                    <input type="text" name="title" value="<?php echo $title; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php
                            if($current_image !="")
                            {
                                ?>
                                    <img src="<?php echo SITEURL; ?>images/category/<?php echo $current_image; ?>"width="150px">
                                <?php
                            }
                            else
                            {
                                echo "<div class='error'>Image Not Added</div>";
                            }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input <?php if($featured=="Yes"){echo "checked";}?> type="radio" name="featured" value="Yes"> Yes
                        <input <?php if($featured=="No"){echo "checked";}?> type="radio" name="featured" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input <?php if($active=="Yes"){echo "checked";}?> type="radio" name="active" value="Yes"> Yes

                        <input <?php if($active=="No"){echo "checked";}?> type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="current_image" value ="<?php echo $current_image;?>">
                        <input type="hidden"name="id"value="<?php echo $id;?>">
                        <input type="submit" name="submit" value="Update Category" class="btn-secondary">
                    </td>
                </tr>


            </table>

        </form>

        <?php
            if(isset($_POST['submit']))
            {
                //thử bấm
                //1. nhận tất cả các giá trị từ biểu mẫu của chúng
                var_dump("submit");
                $id = $_POST['id'];
                $title = $_POST['title'];
                $current_image = $_POST['current_image'];
                $featured = $_POST['featured'];
                $active = $_POST['active'];

                //2.Cập nhật hình ảnh mới đã chọn
                //kiểm tra xem hình ảnh đã được chọn hay chưa
                if(isset($_FILES['image']['name']))
                {
                    var_dump('get image');
                    //lấy hình ảnh Chi tiết
                    $image_name = $_FILES['image']['name'];
                    //kiểm tra xem hình ảnh có sẵn hay không
                    var_dump($image_name);
                    if($image_name!="")
                    {
                        // hình ảnh có sẵn
                        //A.tải lên hình ảnh mới
                        $ext = @end(explode('.',$image_name));
                        $image_name = "Food_Category_".rand(000,999).'.'.$ext;
                        $source_path = $_FILES['image']['tmp_name'];
                        $destination_path = "../images/category/".$image_name;
                        $upload = move_uploaded_file($source_path, $destination_path);
                        if($upload==false)
                        {
                            $_SESSION['upload'] ="<div class ='error'>Failed to upload Image .</div>";
                            header('location:'.SITEURL.'admin/manage-category.php');
                            die();
                        }

                        //B.loại bỏ hình ảnh hiện tại
                        if($current_image!="")
                        {
                            $remove_path="../images/category/".$current_image;

                            $remove = unlink($remove_path);
                            //kiểm tra xem hình ảnh có bị xóa hay không
                            //nếu không loại bỏ được thì hiển thị thông báo và dừng quá trình
                            if($remove==false)
                            {
                                //thất bại xóa ảnh 
                                $_SESSION['failed-remove'] ="<div class ='error'>Failed to remove current Image .</div>";
                                header('location:'.SITEURL.'admin/manage-category.php');
                                die();// dừng chương trình
                            }
                        }
                        
                    }
                    else
                    {
                        $image_name = $current_image;
                    }
                }
                else
                {
                    $image_name = $current_image;
                }

                //3.Úp lên cơ sở dữ liệu
                $sql="UPDATE tbl_category SET
                    title = '$title',
                    image_name = '$image_name',
                    featured = '$featured',
                    active = '$active'
                    WHERE id=$id
                ";

                //thực hiện truy vấn
                $res2=mysqli_query($conn, $sql);

                // 4.chuyển hướng để quản lý danh mục bằng tin nhắn
                // kiểm tra xem có được thực thi hay không

                if($res2==true)
                {
                    //danh mục được cập nhật
                    $_SESSION['update']="<div class='success'>Category Update Successfully.</div>";
                    header('location:'.SITEURL.'admin/manage-category.php');
                }
                else
                {
                    //Thất bại cập nhật danh mục
                    $_SESSION['update']="<div class='error'>Failded to Update.</div>";
                    header('location:'.SITEURL.'admin/manage-category.php');
                }


            }
        
        
        ?>

    </div>

</div>

<?php include('partials/footer.php'); ?>