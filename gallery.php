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
		<h2 class="display-4 text-center form-control text-white bg-primary col-md-12 mt-2 mb-3 profile">Gallery</h2>
	
		<div class="col-md-3 col-12">
			<form id="form" enctype="multipart/form-data">
				<input type="hidden" name="action" value="add" id="action" class="form-control">
				<input type="hidden" name="id"  id="id" class="form-control">
				
				<input type="text" name="title" id="title" placeholder="Picture Title" class="form-control mt-2" required="">
				<input type="text" name="desc" id="desc" placeholder="Picture Descripction" class="form-control mt-2" required="">
				<input type="file" id="image" name="img" accept=".png, .jpeg, .jpg" class="form-control mt-2">
				<input type="submit" id="submit" value="Add Gallery" name="imgSubmit" class="form-control btn btn-primary mt-2">

				<img src="images/default.jpg" id="img" style="height: 250px;" class="img-fluid mt-2" alt="">
			</form>
		</div>

		<div class="col-md-9 col-12 mt-2">
			<div class="table-responsive">
				<table  id="tbl" class="table table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Image</th>
							<th>Image-Name</th>
							<th>Image-Desc</th>
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
					url :'processing/gallery_processing.php',
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

	 					$("#title").val("");
	 					$("#desc").val("");
	 					$("#image").val("");
	 					$('#img').attr("src","images/default.jpg");

	 					$("#submit").removeClass();
						$("#submit").addClass("btn btn-primary form-control mt-2");
						$("#submit").val("Add Gallery");
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
              url:'processing/gallery_processing.php',
              method:'POST',
              type:'ajax',
              data:{action:'showdata'}
            },

            "columns":[
	            {data:"id"},
	            {data:null,
	                render: function(data){
	            	    var id=data.img_name;
	                	return "<img src='gallery/gimages/"+id+"' class='img-fluid' style='height: 70px;'>"
	                }
	            },
	            {data:"img_title"},
	            {data:"img_desc"},
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
				url:'processing/gallery_processing.php',
				method:'POST',
				type:'ajax',
				data:{idd:id,action:"getSingleData"},
				success:function(eve){
					var d=$.parseJSON(eve);
					$("#title").val(d.img_title);
 					$("#desc").val(d.img_desc);
 					// $("#image").val(d.img_name);
 					$('#img').attr("src","gallery/gimages/"+d.img_name);

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
								url:'processing/gallery_processing.php',
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
			// showdata();
		});	    		
	});
</script>

