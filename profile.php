<?php 
	session_start();

  	if(isset($_SESSION['AdminLogedIn']) AND $_SESSION['AdminLogedIn']==true OR  
    	isset($_SESSION['ManagerLogedIn']) AND $_SESSION['ManagerLogedIn']==true){
		include_once "layout/header.php";
	    include_once "layout/sidebar.php";
	    include_once "processing/dbcon.php";
	    include_once "layout/nav.php";

	    $id=$_SESSION['id'];
	    $selt="SELECT * FROM login WHERE id='$id'";
		$query=mysqli_query($conn,$selt);
		$r=mysqli_fetch_assoc($query);
?>
<style type="text/css">
	.profile{
		width: 100%;
		height: 80px;
		font-size: 45px; 
		font-weight: 200;
	}	
</style>
<div class="container">
	<div class="row">
		<h1 class="display-4 text-center form-control text-white bg-primary col-md-12 mt-1 mb-3 profile"><?php echo $r['type']; ?> Profile</h1>
		<div class="col-md-9 mt-3">
			<form id="form">
				<input type="hidden" name="id" id="id" value="<?php echo $r['id']; ?>">
			    <input type="hidden" name="action" id="action" value="update">
				
				<div class="row">
					<div class="col-md-6">
			            <div class="input-group mt-3">
					        <div class="input-group-prepend">
						        <span class="input-group-text bg-dark" id="basic-addon1"><i class="text-white mdi mdi-account-details"></i></span>
					        </div>
					        <input type="text" class="form-control" readonly="" id="name" name="name" value="<?php echo $r['name']; ?>" >
				        </div>
					</div>
					<div class="col-md-6">
						<div class="input-group mt-3">
							<div class="input-group-prepend">
								<span class="input-group-text bg-dark" id="basic-addon1"><i class="text-white mdi mdi-account-edit"></i></span>
							</div>
							<input type="text" class="form-control" id="uname" name="uname" value="<?php echo $r['uname']; ?>" >
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
			            <div class="input-group mt-3">
							<div class="input-group-prepend">
								<span class="input-group-text bg-dark" id="basic-addon1"><i class="text-white mdi mdi-database-lock"></i></span>
							</div>
							<input type="text" class="form-control" id="pass" name="pass" value="<?php echo $r['pass']; ?>" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mt-3">
							<div class="input-group-prepend">
								<span class="input-group-text bg-dark" id="basic-addon1"><i class="text-white mdi mdi-account-group"></i></span>
							</div>
							<input type="text" class="form-control" id="type" name="type" value="<?php echo $r['type']; ?>" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
			            <div class="input-group mt-3">
							<div class="input-group-prepend">
					
							</div>
							<input type="file" id="image" name="img" class="form-control" accept=".png, .jpeg, .jpg">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mt-3">
							<div class="input-group-prepend">
					
							</div>
							<input type="submit" class="form-control btn btn-warning" id="update" value="Update">
						</div>
					</div>
				</div>


			</form>
		</div>
		<div class="col-md-3">
			<img src="images/default.jpg" id="img" style="height: 200px;" class="img-fluid mt-2">
		</div>
	</div>
</div>
<?php 
 	include_once 'layout/footer.php';
 	}else{
 		header("location:login.php");
	}
?>
<script type="text/javascript">
	$(document).ready(function(){
		// Change Image 
		$(document).on("change","#image",function(event){
			var addr=URL.createObjectURL(event.target.files[0]);
			$('#img').attr("src",addr);
		});

		$(document).on("submit","#form",function(event){
			event.preventDefault();
			var fdata=new FormData(this);

			$.ajax({
					url :'processing/profile_processing.php',
 					method:'POST',
 					type: 'ajax',
 					data: fdata,
 					processData: false,
            		contentType: false,
            		cache:false,

 				success: function(eve){
 					var d=$.parseJSON(eve);
 					var msg=d.msg;
 					var stu=d.status;
 					if(stu=="danger"){
 						$.notify(msg,stu);
 					}else{
 						$.notify(msg,stu);

	 					// $("#mname").val("");
	 					// $("#uname").val("");
	 					// $("#pass").val("");
	 					$("#image").val("");
	 					$('#img').attr("src","images/default.jpg");
	 					location.reload();
	 					// $("#submit").removeClass();
						// $("#submit").addClass("btn btn-primary form-control mt-1");
						// $("#submit").val("Add Manager");
						// $("#action").val("update");

	 					// tbl.ajax.reload();
	 				} 					
 				}	
			});
		});
	});
</script>