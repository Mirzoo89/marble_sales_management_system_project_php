	<?php 
	session_start();
	include_once'layout/header.php'; 
?>
<style>
	#carouselExampleFade{
		position: relative;
		z-index: -1;
		
	}
	#login{
		position: absolute;
		
	}
</style>
<div class="col-md-12 col-12 mt-1">
	<div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
		<div class="carousel-inner">
		    <div class="carousel-item active">
			    <img src="images/m1.jpg" class="img-fluid w-100" style="height: 700px; width: 100%" alt="...">
			</div>
			<div class="carousel-item">
			    <img src="images/m2.jpg" class="img-fluid w-100" style="height: 700px; width: 100%" alt="...">
			</div>
			<div class="carousel-item">
			    <img src="images/m3.jpg" class="img-fluid w-100" style="height: 700px; width: 100%" alt="...">
			</div>
			<div class="carousel-item">
			    <img src="images/m4.jpg" class="img-fluid w-100" style="height: 700px; width: 100%" alt="...">
			</div>
		</div>
 	</div>
</div>


 <div class="container d-flex align-items-center justify-content-center" id="login" style="min-height: 100vh">

          <div class="d-flex flex-column justify-content-between">
            <div class="row justify-content-center">
              <div class="col-md-6 col-8 offset-md-6">
                <div class="card card-default mb-0">
                  <div class="card-header pb-0">
                    <div class="app-brand w-100 d-flex justify-content-center border-bottom-0">
                      
                        <img src="images/smf.png" width="45px" height="45px" alt="Mono">
                        <span class="brand-name text-dark mt-1"><b>Sadiq Marbles</b></span>
                      
                    </div>
                  </div>&nbsp;
                  <div class="card-body px-5 pb-5 pt-0">

                    <h2 class="text-dark mb-6 text-center">Welcome To Software</h2>

                    <form action="processing/login_processing.php" method="POST">
                    	<div class="row">
                    		<?php 
								if(isset($_SESSION['msgError'])){
							?>
							
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
    							<strong>Sorry!</strong> <?php echo $_SESSION['msgError']; ?>
    							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						
						<?php unset($_SESSION['msgError']); }  ?>
                    	</div>

	                    <div class="row">
	                        <div class="form-group col-md-12 mb-3">
	                    	    <input type="text" class="form-control input-lg"  
	                            placeholder="User Name" name="username" required="">
	                        </div>
	                        <div class="form-group col-md-12">
	                        	<input type="password" class="form-control input-lg"  name="pass" placeholder="Password" required="">
	                        </div>
	                        <div class="form-group col-md-12">
	                        	<div class="row ">
	                        		<div class="col-5 offset-1">
	                        			<input type="radio" id="admin" class="" name="type" value="Admin" required="">
	                        	    	<label for="admin">Admin</label>
	                        		</div>
	                        		<div class="col-6">
	                        			<input type="radio" id="manager" class="" name="type" value="Manager">
	                        	    	<label for="manager">Manager</label>
	                        		</div>
	                        	</div>             				                        
	                        </div>
	                        <div class="col-md-12">
	                        	<input type="submit" class="btn btn-primary form-control btn-pill mb-4" value="Login" name="login">
							</div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


<?php 
		include_once'layout/footer.php';
?>
