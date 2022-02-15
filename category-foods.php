<?php include('partials-front/menu.php'); ?>

    <?php 
        //kiểm tra xem id có được thông qua hay không
        if(isset($_GET['category']))
        {
            //id danh mục được đặt và lấy id
            $category_id = $_GET['category_id'];
            //lấy tiêu đề danh mục dựa trên ID danh mục
            $sql = "SELECT title FROM tbl_category WHERE id=$category_id";
            //thực hiện truy vấn
            $res = mysqli_query($conn, $res);

            //lấy giá trị từ cơ sở dữ liệu
            $row = mysqli_fetch_assoc($res);
            //lấy tiêu đề
            $category_id = $row['title'];

        }
        else
        {
            //danh mục không được thông qua
            //chuyển hướng đến trang chủ
            header('location:'.SITEURL);
        }
    
    
    
    ?>

    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            
            <h2>Foods on <a href="#" class="text-white">"<?php echo $category_title; ?>"</a></h2>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->



    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <?php 
                //tạo truy vấn sql để lấy thực phẩm dựa trên danh mục đã chọn
                $sql2 = "SELECT * FROM tbl_food WHERE category_id=$category_id";

                //thực hiện truy vấn
                $res2= mysqli_query($conn , $sql2);

                //đếm hàng
                $count2 = mysqli_num_rows($res2);

                //kiểm tra xem thức ăn có sẵn hay không
                if($count2>0)
                {
                    //thức ăn có sẵn 
                    while($row2=mysqli_fetch_assoc($res2))
                    {
                        $id = $row2['id'];
                        $title = $row2['title'];
                        $price = $row2['price'];
                        $description=$row2['description'];
                        $image_name=$row2['image_name'];
                        ?>
                        
                        <div class="food-menu-box">
                            <div class="food-menu-img">
                                <?php 
                                    if($image_name=="")
                                    {
                                        //hình ảnh không có sẵn
                                        echo "<div class='error'>Image not availale</div>";
                                        
                                    }
                                    else
                                    {   
                                        //hình ảnh có sẵn
                                        ?>
                                        <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="Chicke Hawain Pizza" class="img-responsive img-curve">
                                        <?php
                                    }
                                ?>
                                
                            </div>

                            <div class="food-menu-desc">
                                <h4><?php echo $title; ?></h4>
                                <p class="food-price">$<?php echo $price; ?></p>
                                <p class="food-detail">
                                    <?php echo $description; ?>
                                </p>
                                <br>

                                <a href="<?php echo SITEURL; ?>order.php?food_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                            </div>
                        </div>


                        <?php

                    }
                }
                else
                {
                    //thức ăn không có sẵn 
                    echo "<div class='error'>Food not available</div>";
                }

                        
            ?>

            <div class="clearfix"></div>            

        </div>

    </section>
    <!-- fOOD Menu Section Ends Here -->

    
    <?php include('partials-front/footer.php'); ?>