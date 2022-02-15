<?php 
    ob_start();
    include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add food</h1>


        <br><br>

        <?php

            if(isset($_SESSION['upload']))
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        
        ?>

        <form action=""method="POST" enctype="multipart/form-data">

            <table class="tbl-30">
                
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" placeholder="title of the food">
                    </td>
                </tr>

                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" cols="30" rows="5" placeholder="Description of the food."></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price">
                    </td>
                </tr>

                <tr>
                    <td>Select Image: </td>
                    <td>
                        <input type="file" name="image" >
                    </td>
                </tr>

                <tr>
                    <td>Category: </td>
                    <td>
                        <select name="category" >

                            <?php 
                                //tạo mã PHP để hiển thị các danh mục từ cơ sở dữ liệu
                                //tạo sql để lấy tất cả các danh mục hoạt động từ cơ sở dữ liệu
                                $sql = "SELECT * FROM tbl_category WHERE active ='Yes'";
                                //
                                $res = mysqli_query($conn, $sql);

                                //đếm hàng để kiểm tra xem chúng ta có danh mục hay không
                                $count = mysqli_num_rows($res);

                                //nếu số lượng lớn hơn 0, chúng tôi có các danh mục khác, chúng tôi không có các danh mục
                                if($count>0)
                                {
                                    //chúng ta có danh mục
                                    while($row=mysqli_fetch_assoc($res))
                                    {
                                        //lấy thông tin chi tiết của các loại
                                        $id = $row['id'];
                                        $title = $row['title'];

                                        ?>
                                        <option value="<?php echo $id; ?>"><?php echo $title; ?></option>
                                        

                                        <?php

                                    }
                                }
                                else
                                {   
                                    //chúng ta không cso danh mục
                                    ?>
                                    <option value="0">No Category Found</option>
                                    <?php       
                                }

                                //hiển thị trên trình đơn thả xuống
                            
                            ?>


                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes">Yes
                        <input type="radio" name="featured" value="No">No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes">Yes
                        <input type="radio" name="active" value="No">No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Food" class="btn-secondary">

                    </td>
                </tr>


            </table>
   
        </form>


        <?php
            //kiểm tra nút bấm có hoạt động không
            if(isset($_POST['submit']))
            {
                //thêm thức ăn vào cơ sở dữ liệu

                //1.lấy mẫu biểu mẫu dữ liệu
                $title = $_POST['title'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $category = $_POST['category'];

                //kiểm tra xem nút radio cho tính năng nổi bật và hoạt động có được chọn hay không
                if(isset($_POST['featured']))
                {
                    $featured = $_POST['featured'];
                }
                else
                {
                    $featured = "No"; //thiết lập giá trị mặc định
                }

                if(isset($_POST['active']))
                {
                    $active = $_POST['active'];
                }
                else
                {
                    $active = "No";
                }


                //2.tải lên hình ảnh nếu được chọn
                //kiểm tra xem hình ảnh đã chọn có được nhấp vào hay không và chỉ tải hình ảnh lên nếu hình ảnh được chọn
                if(isset($_FILES['image']['name']))
                {
                    //lấy thông tin chi tiết của hình ảnh đã chọn
                    $image_name=$_FILES['image']['name'];

                    //kiểm tra xem hình ảnh có được chọn hay không và chỉ tải lên hình ảnh nếu được chọn
                    if($image_name !="")
                    {
                        //Hình ảnh đc chọn
                        //A. Đổi tên ảnh
                        //lấy phần mở rộng của hình ảnh đã chọn (jpg, png, gif, v.v.) "tanquoi.jpg" tan-quoi.jpg
                        $ext = end(explode('.', $image_name));
                        
                        
                        ////tạo tên mới cho ảnh
                        $image_name = "Food-Name-".rand(0000,9999).".".$ext;//tên hình ảnh mới có thể là "Tên món ăn-567"
                        
                        //B. Tải ảnh lên
                        //lấy đường dẫn Scr path và Destination path

                        //đường dẫn nguồn là vị trí hiện tại của hình ảnh
                        $src = $_FILES['image']['tmp_name'];

                        //đường dẫn đích của hình ảnh để tải lên
                        $dst = "../images/food/".$image_name;

                        //cuối cùng ups ảnh lên

                        $upload=move_uploaded_file($src, $dst);

                        //kiêm tra ảnh có dc úp hay chưa 
                        if($upload==false)
                        {
                            //thất bại khi úp ảnh
                            //chuyển hướng để thêm trang thực phẩm với thông báo lỗi
                            $_SESSION['upload'] ="<div class ='error'>Failed to upload Image .</div>";
                            header('location:'.SITEURL.'admin/add-food.php');
                            //dừng chương trình
                            die();
                        }

                    }

                }
                else
                {
                    $image_name = ""; //đặt giá trị mặc định là trống
                }

                //3.chèn vào cơ sở dữ liệu

                //tạo truy vấn sql để lưu hoặc thêm thức ăn
                //đối với dạng số, chúng ta không cần phải chuyển giá trị vào bên trong dấu ngoặc kép '' bot đối với giá trị chuỗi thì bắt buộc phải thêm dấu ngoặc kép ''
                $sql2 = "INSERT INTO tbl_food SET 
                    title='$title',
                    description='$description',
                    price= $price,
                    image_name='$image_name',
                    category_id= $category,
                    featured='$featured',
                    active='$active'
                ";

                //thực thi truy vấn
                $res2=mysqli_query($conn, $sql2);
                //kiểm tra dư liệu đã đc chèn hay chưa 
                
                //4.chuyển hướng với tin nhắn để quản lý trang thực phẩm
                if($res2 == true)
                {
                    //dữ liệu đã đc chèn thành công
                    
                    $_SESSION['add'] ="<div class ='success'>Food added successfully.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                    
                }
                else
                {
                    //thát bại khi chèn dữ liệu
                    $_SESSION['add'] ="<div class ='error'>Failed to add Food.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }

            } 
            
                                
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>