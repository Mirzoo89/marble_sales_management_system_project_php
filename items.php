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
		<h2 class="display-4 text-center form-control text-white bg-primary col-md-12 mt-2 mb-3 profile">Items</h2>
		<div class="col-md-3 col-12">
			<form id="form">
				<input type="hidden" name="action" id="action" value="add">
				<input type="hidden" name="id" id="id">
				<select id="selctD" name="dep" class="form-control">
					
				</select>
				<input type="text" name="iname" pattern="[a-zA-Z\s]*" title="Item Name in Alphabets" id="iname" placeholder="Item Name" class="form-control mt-2" required="">
				<input type="submit" value="Add Items" id="submit" class="btn btn-primary form-control mt-2">
			</form>
		</div>
		<div class="col-md-9 col-12">
			<div class="table-responsive">
				<table id="tbl" class="table table-striped">
		    		<thead>
			    		<tr>
			    			<th>Id</th>
			    			<th>Category Name</th>
			    			<th>Item Name</th>
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
		show();
		function show(){
     		$.ajax({
            	url:'processing/items_processing.php',
                type:'ajax',
                method:'post',
                data:{action:'getDep'},
                    success:function(all){
                        var var1=$.parseJSON(all);
                        var cd='<option disabled selected>--Select Category--</option>';
	                        $.each(var1,function(key,val){
	                            cd+='<option value="';
	                            cd+=val.id;
	                            cd+='">';
	                            cd+=val.category_name;
	                            cd+='</option>';
	                        });

                          $('#selctD').html(cd);

                                                          
                    },
            });
    	}

    	// Add Data with Ajax
		$(document).on("submit","#form",function(event){
			event.preventDefault();
			var fdata=$(this).serialize();

			$.ajax({
					url :'processing/items_processing.php',
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

	 					$("#iname").val("");
	 					show();
	 					

	 					$("#submit").removeClass();
						$("#submit").addClass("btn btn-primary form-control mt-2");
						$("#submit").val("Add Items");
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
              url:'processing/items_processing.php',
              method:'POST',
              type:'ajax',
              data:{action:'showitem'}
            },

            "columns":[
	            {data:"item_id"},
	            {data:"category_name"},
	            {data:"item_name"},
	            
	            {data:null,
	                render: function(data){
	            	    var id=data.item_id;
	                	return "<button class='btn btn-primary edit' id='"+id+"'><i class='bi bi-pencil-square'></i></button> <button class='btn btn-danger delete' id='"+id+"'><i class='bi bi-archive-fill'></i></button>"
	                }
	            },
			],
     	});
	

	//Edit data with ajax
     	$(document).on("click",".edit",function(){
     		var id=$(this).attr("id");

			$.ajax({
				url:'processing/items_processing.php',
				method:'POST',
				type:'ajax',
				data:{idd:id,action:"getSingleData"},
				success:function(eve){
					var d=$.parseJSON(eve);
					
					$("#selctD").val(d.category_id);
					$("#iname").val(d.item_name);
 					

					
					$("#submit").removeClass();
					$("#submit").addClass("btn btn-warning form-control mt-2");
					$("#submit").val("Add Items");
					$("#action").val("update");
					$("#id").val(d.item_id);
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
								url:'processing/items_processing.php',
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