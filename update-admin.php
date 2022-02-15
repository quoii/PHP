<?php include ('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Admin</h1>

        <br><br>

        <?php 
            //1.get the ID of selected Admin
            $id=$_GET['id'];
            //2. Create SQL query to get the details
            $sql="SELECT * FROM tbl_admin WHERE id=$id";
            //3.Execute the query
            $res=mysqli_query($conn,$sql);

            //Check whether the query is executed or not
            if($res==true)
            {
                $count=mysqli_num_rows($res);
                //check whether we have admin data or not
                if($count==1)
                {
                    //get the details
                    //echo "Admin Available";
                    $row=mysqli_fetch_assoc($res);

                    $full_name=$row['full_name'];
                    $username=$row['username'];
                }
                else
                {
                    //Redirect to manage admin page
                    header('location:'.SITEURL.'admin/manage-admin.php');
                }
            }
        ?>

        <form action="" method="POST">

            <table class="tbl-30">
                <tr>
                    <td>FULL Name:</td>
                    <td>
                        <input type="text" name="full_name" value="<?php echo $full_name; ?>">
                    </td>
                </tr>

                <tr>
                    <td>Username:</td>
                    <td>
                        <input type="text" name="username" value="<?php echo $username; ?>">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Admin" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>   
    </div>
</div>

<?php 
    //check whether the submit Button is clicked or not
    if(isset($_POST['submit']))
    {
        $id = $_POST['id'];
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];

        //Create a sql query to update admin
        $sql = "UPDATE tbl_admin SET
        full_name = '$full_name',
        username = '$username'
        WHERE id='$id'
        ";

        //Execute the query
        $res = mysqli_query($conn, $sql);

        //check whether the query executed successfully or not
        if($res==true)
        {
            //Query Executed and admin Update
            $_SESSION['update'] = "<div class='success'>Admin Update Successfully.</div>";
            //Redirect to manage Admin page
            header('location:'.SITEURL.'admin/manage-admin.php');
        }
        else
        {
            //Failed to Update admin
            $_SESSION['update'] = "<div class='error'>Failed to Update Admin. Try Again Later.</div>";
            //Redirect to manage Admin page
            header('location:'.SITEURL.'admin/manage-admin.php');
        }
    }

?>


<?php include ('partials/footer.php'); ?>