<?php 
  session_start();
  if(isset($_SESSION['AdminLogedIn']) AND $_SESSION['AdminLogedIn']==true OR  
    isset($_SESSION['ManagerLogedIn']) AND $_SESSION['ManagerLogedIn']==true){


    
?>
      
        <!-- ====================================
          ——— LEFT SIDEBAR WITH OUT FOOTER
        ===================================== -->
        <?php
          include_once "layout/header.php";
          include_once "layout/sidebar.php";
          include_once "processing/dbcon.php";
          include_once "layout/nav.php";
          //Total Customers
          $selC="SELECT * FROM customer";
          $query=mysqli_query($conn,$selC);
          $row=mysqli_num_rows($query);

          //Total Categories
          $selc="SELECT * FROM category";
          $qury=mysqli_query($conn,$selc);
          $ro=mysqli_num_rows($qury);

          //Total Purchase
          $selP="SELECT sum(total_price) as totalP FROM purchaser_details";
          $qur=mysqli_query($conn,$selP);
          $rop=mysqli_fetch_assoc($qur);
          $roP=$rop['totalP'];

          //Total Sale
          $selS="SELECT sum(total_price) as totalS FROM seller_details";
          $qurS=mysqli_query($conn,$selS);
          $ros=mysqli_fetch_assoc($qurS);
          $roS=$ros['totalS'];

        ?>

      

      <!-- ====================================
      ——— PAGE WRAPPER
      ===================================== -->
      

        <!-- ====================================
        ——— CONTENT WRAPPER
        ===================================== -->
          <div class="content-wrapper">
            <div class="content">                
               <!-- Top Statistics -->
              <div class="row">
                <div class="col-xl-3 col-sm-6 ">
                  <div class="card card-default card-mini">
                    <div class="card-header">
                      <div class="sub-title">
                        <span class="mr-1"><b><h2>Total Customer:</h2></b></span> 
                        <!-- <span class="mx-1">80%</span> -->                          
                      </div>
                      <h2><?php echo $row; ?></h2>  
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                  <div class="card card-default card-mini">
                    <div class="card-header">
                      <div class="sub-title">
                        <span class="mr-1" ><b><h2>Total Category:</h2></b></span> 
                      </div>
                      <h2 class=""><?php echo $ro; ?></h2>                      
                    </div>
                  </div>
                </div>
                 <div class="col-xl-3 col-sm-6">
                  <div class="card card-default card-mini">
                    <div class="card-header">
                      <div class="sub-title">
                        <span class="mr-1"><b><h2>Total Purchase:</h2></b></span>
                      </div>
                      <h2><?php echo $roP; ?></h2>                      
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                  <div class="card card-default card-mini">
                    <div class="card-header">
                      <div class="sub-title">
                        <span class="mr-1"><b><h2>Total Sale:</h2></b></span>
                      </div>
                      <h2><?php echo $roS; ?></h2>                      
                    </div>
                  </div>
              </div>
            </div>


                


                
              

              
        
          

      </div>
    </div>

    
                   

<?php include_once"layout/footer.php"; ?>

<?php }else{
  header("location:login.php");
} ?>
