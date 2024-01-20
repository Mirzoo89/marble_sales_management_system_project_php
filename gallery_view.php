<?php 
	session_start();

 	if(isset($_SESSION['ManagerLogedIn']) AND $_SESSION['ManagerLogedIn']==true){
		include_once "layout/header.php";
	    include_once "layout/sidebar.php";
	    include_once "processing/dbcon.php";
	    include_once "layout/nav.php";

	    $select="SELECT * FROM gallery";
	    $query=mysqli_query($conn,$select);
?>
<style type="text/css">
	.profile{
		height: 80px;
		font-size: 45px; 
		font-weight: 200;
	}	
</style>
<div class="container">
	<div class="row">
		<h2 class="display-4 text-center form-control text-white bg-primary col-md-12 mt-2 mb-3 profile">Gallery View</h2>
	<?php 
		while($row=mysqli_fetch_assoc($query)){
	?>
		<div class="col-md-4">
		    <div class="card mb-2">		    	
		      	<div class="row no-gutters mt-2">
		      		<a data-fancybox data-ratio="2" href="gallery/gimages/<?php echo $row['img_name']?>" class="block__96788">
			        	<img src="gallery/gimages/<?php echo $row['img_name']?>" class="rounded-left horizontal-img img-fluid " alt="Image" >	
			        </a>        			        
		    	</div>
		    	<div class="row">		    		
			        <div class="card-body">
			           	<h5 class="card-title pt-2"><?php echo $row['img_title']; ?></h5>
			            <p class="card-text mb-6"><?php echo $row['img_desc']; ?> &nbsp;
			            	<span class="float-right">
			            		<a data-fancybox data-ratio="2" href="gallery/gimages/<?php echo $row['img_name']?>" class="block__96788"><span class="mdi mdi-fullscreen"></span> Full View</a>
			            	</span>
			            </p>
				    </div>			        
		    	</div>
			</div>
  		</div>
  	<?php } ?>
	</div>
</div>
<?php 
	include_once 'layout/footer.php';
 	}else{
 		header("location:login.php");
	}
?>