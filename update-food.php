<?php include('partials/menu.php');?>

<?php 
    //kiểm tra xem id đã được đặt hay chưa
    if(isset($_GET['id']))
    {
        //lấy tất cả các chi tiết
        $id = $_GET['id'];

        //truy vấn sql để lấy món ăn đã chọn
        $sql2 = "SELECT * FROM tbl_food WHERE id=$id";

        //thực hiện truy vấn
        $res2=mysqli_query($conn, $sql2);

        //nhận giá trị dựa trên truy vấn được thực thi
        $row2=mysqli_fetch_assoc($res2);

        //nhận các giá trị riêng của Thực phẩm đã chọn
        $title = $row2['title'];
        $description = $row2['description'];
        $price = $row2['price'];
        $current_image = $row2['image_name'];
        $current_category = $row2['category_id'];
        $featured= $row2['featured'];
        $active = $row2['active'];

    }
    else
    {
        //chuyển hướng để quản lý thực phẩm
        header('location:'.SITEURL. 'admin/manage-food.php');
    }

?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update food</h1>

        <br><br>
        
        <form action=""method="POSt" enctype="multipart/form-data">

        <table class="tbl-30">

        <tr>
                <td>Title:</td>
                <td>
                    <input type="text" name="title" value="<?php echo $title; ?>">
                </td>
            </tr>

            <tr>
                <td>Description: </td>
                <td>
                    <textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea>
                </td>
            </tr>

            <tr>
                <td>Price: </td>
                <td>
                    <input type="number" name="price" value="<?php echo $price; ?>">
                </td>
            </tr>

            <tr>
                <td>Current image: </td>
                <td>
                    <?php 
                        if($current_image =="")
                        {
                            //hình ảnh có sẵn
                            echo "<div class='error'>Image not available.</div>"; 
                        }
                        else
                        {
                            //hình ảnh kh có sẵn
                            ?>
                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="200px">    

                            <?php
                        }
                    
                    
                    
                    ?>
                </td>
            </tr>

            <tr>
                <td>Select New Image:</td>
                <td>
                    <input type="file" name="image">
                </td>
            </tr>

            <tr>
                <td>Category: </td>
                <td>
                    <select name="category">
                        <?php 
                            // truy vấn để nhận các danh mục hoạt động
                            $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                            //// thực hiện truy vấn
                            $res = mysqli_query($conn, $sql);
                            //// đếm hàng
                            $count = mysqli_num_rows($res);

                            //kiểm tra xem các danh mục có sẵn không
                            if($count>0)
                            {
                                //danh mục có sẵn
                                while($row=mysqli_fetch_assoc($res))
                                {
                                    $category_title = $row['title'];
                                    $category_id = $row['id'];

                                    //echo "<option value='$category_id'>$category_title </option>";
                                    ?>
                                    <option <?php if($current_category==$category_id){echo "selected";} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>
                                    <?php

                                }
                            }
                            else
                            {
                                //danh mục không có sẵn 
                                echo "<option value='0'>Category not available.</option>";
                            }

                        
                        ?>
                        
                    </select>
                </td>
            </tr>

            <tr>
                <td>Featured: </td>
                <td>
                    <input <?php if($featured=="Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes">Yes
                    <input <?php if($featured=="No"){echo "checked";} ?> type="radio" name="featured" value="No">No
                </td>
            </tr>

            <tr>
                <td>Active: </td>
                <td>
                    <input <?php if($active=="Yes"){echo "checked";} ?> type="radio" name="active" value="Yes">Yes
                    <input <?php if($active=="No"){echo "checked";} ?> type="radio" name="active" value="No">No
                </td>
            </tr>

            <tr>
                <td>
                    <input type="hidden" name ="id" value="<?php echo $id; ?>">
                    <input type="hidden" name ="current_image" value="<?php echo $current_image; ?>">

                    <input type="submit" name ="submit" value="Update Food" class="btn-secondary">
                </td>
            </tr>


        </table>
            

        </form>

        <?php 
            if(isset($_POST['submit']))
            {
                //1.lấy tất cả các chi tiết từ biểu mẫu
                $id=$_POST['id'];
                $title=$_POST['title'];
                $description=$_POST['description'];
                $price=$_POST['price'];
                $current_image=$_POST['current_image'];
                $category=$_POST['category'];

                $featured=$_POST['featured'];
                $active=$_POST['active'];



                //2.tải lên hình ảnh nếu được chọn
                //kiểm tra xem nút tải lên có được nhấp hay không
                if($_FILES['image']['name'])
                {
                    //đã nhấp vào nút tải lên
                    $image_name=$_FILES['image']['name'];// tên mới của ảnh

                    //kiểm tra xem các tệp có sẵn hay không
                    if($image_name !="")
                    {
                        //ảnh hoạt động
                        //tải lên hình ảnh mới


                        //đổi tên ảnh
                        $ext=end(explode('.', $image_name)); //lấy phần mở rộng của hình ảnh
                        $image_name = "Food-name-".rand(0000,9999).'.'.$ext; //Đây sẽ là hình ảnh được đổi tên

                        //lấy đường dẫn nguồn và đường dẫn đích
                        $src_path = $_FILES['image']['tmp_name'];//source path
                        $dest_path ="../images/food/".$image_name;// destination path
                        //tải ảnh lên
                        $upload = move_uploaded_file($src_path, $dest_path);

                        //kiểm tra xem hình ảnh có được tải lên hay không
                        if($upload==false)
                        {
                            //thất bại tải ảnh
                            $_SESSION['upload']="<div class='error'>Failed to upload new image.</div>";
                            header('location:'.SITEURL.'admin/manage-food.php');
                            //dừng chương trình
                            die();
                        }

                        //3.xóa hình ảnh nếu hình ảnh mới được tải và hình ảnh hiện tại tồn tại
                        //loại bỏ hình ảnh hiện tại nếu có
                        if($current_image!="")
                        {
                            //hình ảnh hiện tại có sẵn
                            //xóa ảnh
                            $remove_path = "../images/food/".$current_image;

                            $remove=unlink($remove_path);

                            //kiểm tra xem hình ảnh có bị xóa hay không
                            if($remove==false)
                            {
                                //thất bại khi xóa ảnh
                                $_SESSION['remove-failed']="<div class='error'>Failed to remove curent image.</div>";
                                //chuyển hướng để quản lý thực phẩm 
                                header('location:'.SITEURL.'admin/manage-food.php');
                                //stop the process
                                die();

                            }
                        }
                    }
                    else
                    {
                        $image_name=$current_image;
                    }
                }
                else
                {
                    $image_name=$current_image;
                }


                //4.cập nhật thực phẩm trong cơ sở dữ liệu
                $sql3="UPDATE tbl_food SET
                    title='$title',
                    description = '$description',
                    price = '$price',
                    image_name='$image_name',
                    category_id = '$category_id',
                    featured = '$featured',
                    active = '$active'
                    WHERE id=$id            
                ";

                //đã thực hiện truy vấn sql
                $res3 = mysqli_query($conn, $sql3);

                //kiểm tra xem truy vấn có được thực thi hay không
                if($res3==true)
                {
                    //truy vấn được thực hiện và cập nhật thực phẩm
                    $_SESSION['update']="<div class='success'>Food update successfully.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
                else
                {
                    //thất bại khi cập nhật ảnh
                    $_SESSION['update']="<div class='error'>Failed to update food.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }


                //5.chuyển hướng để quản lý thực phẩm với thông báo phiên
            }
                
        
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>