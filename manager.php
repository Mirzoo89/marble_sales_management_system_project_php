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
	<div class="row">
		<h2 class="display-4 text-center form-control text-white bg-primary col-md-12 mt-1 mb-3 profile">Manager</h2>
		<div class="col-md-3 col-12">
			<form id="form">
				<input type="hidden" name="action" value="add" id="action" class=" form-control">
				<input type="hidden" name="id" id="id" class=" form-control">

				<input type="text" class="form-control" required="" placeholder="Manager Name" name="mname"  id="mname">
				<input type="text" class="form-control mt-1" required="" placeholder="User Name" name="uname"  id="uname">
				<input type="text" class="form-control mt-1" required="" placeholder="Password" name="pass"        id="pass">	
				<input type="file" id="image" name="img" required="" accept=".png, .jpeg, .jpg" class="form-control mt-1">

				<input type="submit" value="Add Manager" id="submit" class="form-control mt-1 btn btn-primary">

				<img src="images/default.jpg" id="img" class="img-fluid mt-1" style="height: 250px;">		
		    </form>
		</div>
		<div class="col-md-9 col-12">
			<div class="table-responsive">
				<table id="tbl" class="table  table-striped">
			    	<thead>
			    		<tr>
			    			<th>Id</th>
			    			<th>Manager Name</th>
			    			<th>User Name</th>
			    			<th>Password</th>
			    			<th>Image</th>
			    			<th>Action</th>
			    		</tr>
			    	</thead>
			    	<tbody>
			    		
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
		// Change Image 
		$(document).on("change","#image",function(event){
			var addr=URL.createObjectURL(event.target.files[0]);
			$('#img').attr("src",addr);
		});
		// Add Data with Ajax
		$(document).on("submit","#form",function(event){
			event.preventDefault();
			var fdata=new FormData(this);

			$.ajax({
					url :'processing/manager_processing.php',
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

	 					$("#mname").val("");
	 					$("#uname").val("");
	 					$("#pass").val("");
	 					$("#image").val("");
	 					$('#img').attr("src","images/default.jpg");

	 					$("#submit").removeClass();
						$("#submit").addClass("btn btn-primary form-control mt-1");
						$("#submit").val("Add Manager");
						// $("#action").val("update");

	 					tbl.ajax.reload();
 					}
 					
 				}	
			});
		});
		// show data with ajax table
 		var tbl= $("#tbl").DataTable({
            "paging":true,
            "lengthChange":true,
            "ordering":true,
            "info":true,
            "searching":true,

             "ajax":{
              url:'processing/manager_processing.php',
              method:'POST',
              type:'ajax',
              data:{action:'showitem'}
            },

            "columns":[
	            {data:"manager_id"},
	            {data:"manager_name"},
	            {data:"user_name"},
	            {data:"password"},
	            {data:null,
	                render: function(data){
	            	    var id=data.img_name;
	                	return "<img src='gallery/manager/"+id+"' class='img-fluid' style='height: 70px;'>"
	                }
	            },
	            {data:null,
	                render: function(data){
	            	    var id=data.manager_id;
	                	return "<button class='btn btn-primary edit' id='"+id+"'><i class='bi bi-pencil-square'></i></button> <button class='btn btn-danger delete' id='"+id+"'><i class='bi bi-archive-fill'></button>"
	                }
	            },
			],
     	});
     	//Edit data with ajax
     	$(document).on("click",".edit",function(){
     		var id=$(this).attr("id");

			$.ajax({
				url:'processing/manager_processing.php',
				method:'POST',
				type:'ajax',
				data:{idd:id,action:"getSingleData"},
				success:function(eve){
					var d=$.parseJSON(eve);
					
					$("#mname").val(d.manager_name);
					$("#uname").val(d.user_name);
					$("#pass").val(d.password);
 					$('#img').attr("src","gallery/manager/"+d.img_name);

					
					$("#submit").removeClass();
					$("#submit").addClass("btn btn-warning form-control mt-2");
					$("#submit").val("Update Manager");
					$("#action").val("update");
					$("#id").val(d.manager_id);
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
								url:'processing/manager_processing.php',
								method:'POST',
								type:'ajax',
								data:{id:id,action:"deleteData"},

								success:function(){
									// location.reload();
									tbl.ajax.reload();
									// showdata();
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

