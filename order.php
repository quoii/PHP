<?php include('partials-front/menu.php'); ?>

    <?php 
        //kiểm tra xem id thực phẩm đã được đặt hay chưa
        if(isset($_GET['food_id']))
        {
            //lấy id thực phẩm và thông tin chi tiết của thực phẩm đã chọn
            $food_id = $_GET['food_id'];

            //lấy thông tin chi tiết về thực phẩm đã chọn
            $sql = "SELECT * FROM tbl_food WHERE id=$food_id";

            //thực hiện truy vấn
            $res= mysqli_query($conn, $sql);

            //đếm hàng
            $count=mysqli_num_rows($res);

            //kiểm tra xem dữ liệu có sẵn hay không
            if($count==1)
            {
                //chúng ta có dữ liệu
                //lấy dữ liệu từ cơ sở dữ liệu
                $row=mysqli_fetch_assoc($res);

                $title = $row['title'];
                $price = $row['price'];
                $image_name = $row['image_name'];
            }
            else
            {
                //thức ăn không có
                //chuyển hướng tới trang chủ
                header('location:'.SITEURL); 
            }
            
        }
        else
        {
            //chuyển hướng đến trang chủ
            header('location:'.SITEURL);
        }
    
    
    ?>

    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search">
        <div class="container">
            
            <h2 class="text-center text-white">Fill this form to confirm your order.</h2>

            <form action="" method="POST" class="order">
                <fieldset>
                    <legend>Selected Food</legend>

                    <div class="food-menu-img">
                        <?php 
                            //kiểm tra xem hình ảnh có sẵn hay không
                            if($image_name=="")
                            {   
                                //ảnh không hoạt động
                                echo "<div class='error'>Image not available.</div>";
                            }
                            else
                            {
                                //ảnh hoạt động
                                ?>
                                <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                                <?php
                            }

                        ?>
                        
                    </div>
    
                    <div class="food-menu-desc">
                        <h3><?php echo $title; ?></h3>
                        <input type="hidden" name ="food" value='<?php echo $title; ?>'>

                        <p class="food-price">$<?php echo $price; ?></p>
                        <input type="hidden" name="price" value="<?php echo $price; ?>">

                        <div class="order-label">Quantity</div>
                        <input type="number" name="qty" class="input-responsive" value="1" required>
                        
                    </div>

                </fieldset>
                
                <fieldset>
                    <legend>Delivery Details</legend>
                    <div class="order-label">Full Name</div>
                    <input type="text" name="full-name" placeholder="" class="input-responsive" required>

                    <div class="order-label">Phone Number</div>
                    <input type="tel" name="contact" placeholder="" class="input-responsive" required>

                    <div class="order-label">Email</div>
                    <input type="email" name="email" placeholder="" class="input-responsive" required>

                    <div class="order-label">Address</div>
                    <textarea name="address" rows="10" placeholder="" class="input-responsive" required></textarea>

                    <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary">
                </fieldset>

            </form>

            <?php
                //kiểm tra xem nút gửi có được nhấp vào hay không
                if(isset($_POST['submit']))
                {
                    //lấy tất cả các chi tiết từ biểu mẫu
                    $food = $_POST['food'];
                    $price = $_POST['price'];
                    $qty = $_POST['qty'];
                    $total = $price * $qty; //total =price * qty

                    $order_date = date("Y-m-d h:i:sa"); // ngày đặt hàng

                    $status = "Ordered";//đặt hàng khi giao hàng đã giao bị hủy
                    
                    $customer_name = $_POST['full-name'];
                    $customer_contact = $_POST['contact'];
                    $customer_email = $_POST['email'];
                    $customer_address = $_POST['address'];

                    //lưu thông tin đặt hàng lên cơ sở dữ liệu
                    //tạo cơ sở dữ liệu với dữ liệu
                    $sql2 = "INSERT INTO tbl_order SET
                        food ='$food',
                        price ='$price',
                        qty = '$qty',
                        total ='$total',
                        order_date = '$order_date',
                        status ='$status',
                        customer_name = '$customer_name',
                        customer_contact = '$customer_contact',
                        customer_email = '$customer_email',
                        customer_address = '$customer_address'                    
                    ";

                    //echo $sql2; die();

                    //thực hiện truy vấn
                    $res2=mysqli_query($conn, $sql2);

                    // /kiểm tra xem truy vấn có được thực thi thành công hay không
                    if($res2==true)
                    {
                        //hàng đợi đc thực thi và đặt hàng đc lưu
                        $_SESSION['order'] = "<div class='success text-center'>Food Order Successfully. </div>";
                        header('location:'.SITEURL);

                    }
                    else
                    {
                        //thất bại khi lưu đặtk hàng
                        $_SESSION['order'] = "<div class='error text-center'>Failed to Order.</div>";
                        header('location:'.SITEURL);
                    }
                }
            
            
            
            ?>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->

    <?php include('partials-front/footer.php'); ?>