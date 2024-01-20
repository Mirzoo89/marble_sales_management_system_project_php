<?php 
	session_start();

  	if(isset($_SESSION['AdminLogedIn']) AND $_SESSION['AdminLogedIn']==true OR  
    isset($_SESSION['ManagerLogedIn']) AND $_SESSION['ManagerLogedIn']==true){
		include_once "layout/header.php";
	    include_once "layout/sidebar.php";
	    include_once "processing/dbcon.php";
	    include_once "layout/nav.php";
?>
<style type="text/css">
	.profile{
		height: 80px;
		font-size: 45px; 
		font-weight: 200;
	}	
</style>





<div class="container">
	<h1 class="profile display-4 text-center mt-5 form-control bg-primary text-white">Customer</h1>
 	<div class="row mt-5">
 		<div class="col-md-4 col-12">
 			<form id="form">
 				<div class="input-group mb-3">
					<div class="input-group-prepend">
					    <span class="input-group-text bg-dark" id="basic-addon1"><i class="text-white mdi mdi-account-group"></i></span>
					</div>
					<select name="type" id="type" class="form-control form-select" required="">
						<option value="" disabled="" selected="">--Select Type--</option>
						<option value="Seller">Seller</option>
						<option value="Purchaser">Purchaser</option>
					</select>
				  	
				  	<!-- <input type="text" class="form-control" placeholder="Customer type" aria-label="Username" aria-describedby="basic-addon1" name="type" id="type"> -->
				  	
				  	<input type="hidden" name="action" value="add" id="action">
				  	<input type="hidden" name="id"  id="id">
				</div>

				<div class="input-group mb-3">
				  	<div class="input-group-prepend">
				   		<span class="input-group-text bg-dark" id="basic-addon1"><i class="text-white mdi mdi-account-circle"></i></span>
				  	</div>
				  	<input type="text" class="form-control" placeholder="Customer Name" aria-label="Username" aria-describedby="basic-addon1" pattern="[a-zA-Z\s]*" title="Husnain Afzal" name="name" id="name" required="" minlength="3" maxlength="20">
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text bg-dark" id="basic-addon1"><i class="text-white mdi mdi-phone-voip"></i></span>
					</div>
						<input type="text" class="form-control" placeholder="Customer Contact" aria-label="Username" pattern="[0-9]{11}" title=" Write in this format(11) 03087020521" aria-describedby="basic-addon1" name="contact" id="contact" required="">
				</div>

				<div class="input-group mb-3">
				  	<div class="input-group-prepend">
				    	<span class="input-group-text bg-dark" id="basic-addon1"><i class="text-white mdi mdi-compass"></i></span>
				  	</div>
				  	<input type="text" class="form-control" placeholder="Address" aria-label="Username" aria-describedby="basic-addon1"  name="address" id="address" required="">
				</div>

				
				
				<div class="input-group mb-3">
				  	<input type="submit" class="form-control btn btn-primary" name="submit" id="submit" value="submit">
				</div>
 			</form>
 		</div>

 		<div class="col-md-8 col-12">
 			<!-- <h2 class="mt-3 text-center mb-2">Customer Record</h2> -->
	 		<div class="table-responsive">
	 			<table id="tbl" class="table table-striped">
	           		<thead class="sticky-top ">
		               	<tr>
		                    <!-- <th>ID</th> -->
							<th>Type</th>
		                    <th>Name</th>
		                    <th>Contact No</th>
	                        <th>Address</th>
	                        <th>Action</th>
	                    </tr>
	           		</thead>
	            	<tbody >
	    				
	    			</tbody>
	        	</table>
	 		</div>			
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
 			// $('#table').DataTable();

 		$(document).on("submit","#form",function(event){
 			event.preventDefault();
 			var fdata=$(this).serialize();

 			$.ajax({
 				url :'processing/customer_processing.php',
 				method:'POST',
 				type: 'ajax',
 				data: fdata,

				success: function(eve){
					var d=$.parseJSON(eve);
					var msg=d.msg;
					var stu=d.status;
					if(stu=="danger"){
						$.notify(msg,stu);
					}else{
 						$.notify(msg,stu);

 						$("#type").val("");
 						$("#name").val("");
 						$("#contact").val("");
 						$("#address").val("");

 						$("#submit").removeClass();
						$("#submit").addClass("btn btn-primary form-control");
						$("#submit").val("submit");

	 						// showdata();
 						tbl.ajax.reload();
 					}

 				}
 			});
 		});
 			// Show data with ajax
 		var tbl= $("#tbl").DataTable({
	        "paging":true,
	        "lengthChange":true,
	        "ordering":true,
	        "info":true,
	        "searching":true,

	        "ajax":{
	            url:'processing/customer_processing.php',
	            method:'POST',
	            type:'ajax',
	            data:{action:'showdata'}
	       	},

	        "columns":[
	            {data:"customer_type"},		            
	            {data:"customer_name"},
	            {data:"contact"},
	            {data:"address"},
	            
	            {data:null,
	                render: function(data){
	            	    var id=data.id;
	                		return "<button class='btn btn-primary edit' id='"+id+"'><i class='bi bi-pencil-square'></i></button> <button class='btn btn-danger delete' id='"+id+"'><i class='bi bi-archive-fill'></i></button>"
		            }
		        },
		        
			],
     	});

 			// Edit With Ajax 
 		$(document).on("click",".edit",function(){
			var id=$(this).attr("id");

			$.ajax({
				url:'processing/customer_processing.php',
				method:'POST',
				type:'ajax',
				data:{idd:id,action:"getSingleData"},
				success:function(eve){
					var d=$.parseJSON(eve);
					$("#type").val(d.customer_type);
					$("#name").val(d.customer_name);
					$("#contact").val(d.contact);
					$("#address").val(d.address);

					$("#submit").removeClass();
					$("#submit").addClass("btn btn-warning form-control");
					$("#submit").val("Update");
					$("#action").val("update");
					$("#id").val(d.id);
				}
			});
		});
		$(document).on("click",".edi",function(){
			var id=$(this).attr("id");

			$.ajax({
				url:'processing/customer_processing.php',
				method:'POST',
				type:'ajax',
				data:{idd:id,action:"getSingleData"},
				success:function(eve){
					var d=$.parseJSON(eve);
					$("#typ").val(d.customer_type);
					$("#nam").val(d.customer_name);
					$("#contac").val(d.contact);
					$("#addres").val(d.address);

					$("#submit").removeClass();
					$("#submit").addClass("btn btn-warning form-control");
					$("#submit").val("Update");
					$("#action").val("update");
					$("#id").val(d.id);
				}
			});
		});

		// Delete The data with confirm

		$(document).on("click",".delete",function(){
			var id=$(this).attr("id");

			$.confirm({
			    title: 'Delete!',
			    content: 'Press Delete! If you want.',
			    autoClose: 'cancel|10000',
			    buttons: {
			    	Delete: {
			    		btnClass:'btn-danger',
			    		action:function(){
			    			$.ajax({
								url:'processing/customer_processing.php',
								method:'POST',
								type:'ajax',
								data:{id:id,action:"deleteData"},

								success:function(){
									// location.reload();
									// showdata();
									tbl.ajax.reload();
								},
							});
						}
					},
					cancel: function(){

					},
				}
			});
		}); 			
 	});
</script>



