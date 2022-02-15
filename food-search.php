<?php include('partials-front/menu.php'); ?>

    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">

            <?php
                //lấy từ khóa tìm kiếm
                
                $search = mysqli_real_escape_string($conn ,$_POST['search']);
            
            
            ?>
            
            <h2>Foods on Your Search <a href="#" class="text-white">"<?php echo $search; ?>"</a></h2>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->



    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <?php
                //lấy từ khóa tìm kiếm
                $search = $_POST['search'];
                //truy vấn sql để lấy thức ăn dựa trên từ khóa tìm kiếm

                $sql = "SELECT * FROM tbl_food WHERE title LIKE '%$search%' OR description LIKE '%$search%' ";
                //thực hiện truy vấn
                $res=mysqli_query($conn, $sql);
                //đếm hàng
                $count = mysqli_num_rows($res);
                //kiểm tra xem thức ăn có sẵn không
                if($count>0)
                {
                    //thức ăn có sẵn 
                    while($row=mysqli_fetch_assoc($res))
                    {
                        //lấy thông tin chi tiết
                        $id = $row['id'];
                        $title = $row['title'];
                        $price = $row['price'];
                        $description = $row['description'];
                        $image_name = $row['image_name'];
                        ?>
                            <div class="food-menu-box">
                                <div class="food-menu-img">
                                    <?php
                                        //kiểm tra xem tên hình ảnh có sẵn hay không 
                                        if($image_name=="")
                                        {
                                            //hình ảnh không có sẵn
                                            echo "<div class='error'>Image not Available.</div>";
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

                                    <a href="#" class="btn btn-primary">Order Now</a>
                                </div>
                            </div>

                        <?php

                    }
                }
                else
                {
                    //thức ăn không có sẵn
                    echo "<div class='error'>Food not found.</div>";
                }
                        
            ?>

            
            <div class="clearfix"></div>       

        </div>

    </section>
    <!-- fOOD Menu Section Ends Here -->

    <?php include('partials-front/footer.php'); ?>