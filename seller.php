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
		<h1 class="profile display-4 text-center mt-5 form-control bg-primary text-white">Seller Records</h1>
		<div class="col-md-3 col-12">
			<form id="form">
				<input type="hidden" name="action" value="add" id="action" class=" form-control">
				<input type="hidden" name="id" id="id" class=" form-control">

				<select id="selectS" name="seller" class="form-control mt-2"  required="">
					
				</select>
				<select id="selctC" name="category"  class="form-control mt-2" required="">
					
				</select>	
				<input type="number" name="quantity" id="quantity" placeholder="Quantity" class="form-control mt-2" required="">
				<input type="number" name="price" id="price" placeholder="Price Per Foot" class="form-control mt-2" required="">

				<input type="submit" value="Add Salles" id="submit" class="btn btn-primary mt-2 form-control">		
			</form>
		</div>
		<div class="col-md-9 col-12 mt-2">
			<div class="table-responsive">
				<table id="tbl" class="table  table-striped">
			    	<thead>
			    		<tr>
			    			<th>Id</th>
			    			<th>Seller Name</th>
			    			<th>Category Name</th>
			    			<th>Quantity</th>
			    			<th>Price (F)</th>
			    			<th>Amount</th>
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
		showSeller();
		function showSeller(){
     		$.ajax({
            	url:'processing/seller_processing.php',
                type:'ajax',
                method:'post',
                data:{action:'getSeller'},
                    success:function(all){
                        var var1=$.parseJSON(all);
                        var cd='<option disabled selected>-- Select Seller --</option>';
	                        $.each(var1,function(key,val){
	                            cd+='<option value="';
	                            cd+=val.id;
	                            cd+='">';
	                            cd+=val.customer_name;
	                            cd+='</option>';
	                        });

                          $('#selectS').html(cd);

                    },
            });
    	}

    	showCategory();
		function showCategory(){
     		$.ajax({
            	url:'processing/seller_processing.php',
                type:'ajax',
                method:'post',
                data:{action:'getCat'},
                success:function(all){
                    var var1=$.parseJSON(all);
                    var cd='<option disabled selected>-- Select Category --</option>';
	                    $.each(var1,function(key,val){
	                        cd+='<option value="';
	                        cd+=val.id;
	                        cd+='">';
	                        cd+=val.category_name;
	                        cd+='</option>';
	                    });

         	        $('#selctC').html(cd);
                },
           	});
    	}
    	// Add Data with Ajax
		$(document).on("submit","#form",function(event){
			event.preventDefault();
			var fdata=$(this).serialize();

			$.ajax({
					url :'processing/seller_processing.php',
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

	 					$("#quantity").val("");
	 					$("#price").val("");
	 					
	 					showCategory();
	 					showSeller();

	 					$("#submit").removeClass();
						$("#submit").addClass("btn btn-primary form-control mt-2");
						$("#submit").val("Add Seller");
						// $("#action").val("update");

	 					tbl.ajax.reload();
	 					$("#selectS").show();
						$("#selctC").show();
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
              url:'processing/seller_processing.php',
              method:'POST',
              type:'ajax',
              data:{action:'showitem'}
            },

            "columns":[
	            {data:"s_id"},
	            {data:"customer_name"},
	            {data:"category_name"},
	            {data:"quantity"},
	            {data:"p_price"},
	            {data:"total_price"},
	            
	            {data:null,
	                render: function(data){
	            	    var id=data.s_id;
	                	return "<button class='btn btn-primary edit' id='"+id+"'><i class='bi bi-pencil-square'></i></button> <button class='btn btn-danger delete' id='"+id+"'><i class='bi bi-archive-fill'></i></button>"
	                }
	            },

			],
     	});

     	//Edit data with ajax
     	$(document).on("click",".edit",function(){
     		var id=$(this).attr("id");

			$.ajax({
				url:'processing/seller_processing.php',
				method:'POST',
				type:'ajax',
				data:{idd:id,action:"getSingleData"},
				success:function(eve){
					var d=$.parseJSON(eve);
					$("#selectS").hide();
					$("#selctC").hide();
					// $("#selectP").val(d.purchaser_id);
					// $("#selctC").val(d.category_id);
					$("#quantity").val(d.quantity);
					$("#price").val(d.p_price);
 					

					
					$("#submit").removeClass();
					$("#submit").addClass("btn btn-warning form-control mt-2");
					$("#submit").val("Update Seller");
					$("#action").val("update");
					$("#id").val(d.s_id);
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
								url:'processing/seller_processing.php',
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