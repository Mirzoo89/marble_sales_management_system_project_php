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
		<h2 class="display-4 text-center form-control text-white bg-primary col-md-12 mt-2 mb-3 profile">Prices</h2>
		<div class="col-md-3 col-12">
			<form id="form">
				<input type="hidden" name="action" id="action" value="add">
				<input type="hidden" name="id" id="id">

				<select id="selctD" name="catname" class="form-control" required="">
					
				</select>
				<select id="selctI" name="item" class="form-control mt-2" >
					
				</select>

				<input type="text" placeholder="Size 12*12, 12*24" name="size" id="size" class="mt-2 form-control" pattern="[0-9]{2}*[0-9]{2}" required="">
				<input type="text" placeholder="Price" name="price" id="price" class="mt-2 form-control" required="">
				<input type="submit" value="Add Prices" id="submit" class="btn btn-primary mt-2 form-control">
			</form>
		</div>
		<div class="col-md-9 col-12">
			<div class="table-responsive">
				<table id="tbl" class="table  table-striped">
			    	<thead>
			    		<tr>
			    			<th>Id</th>
			    			<th>Category Name</th>
			    			<th>Item Name</th>
			    			<th>Sizes</th>
			    			<th>Prices</th>
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
		// $("#selctI").hide();
		// show category
		showCat();
		function showCat(){
     		$.ajax({
            	url:'processing/prices_processing.php',
                type:'ajax',
                method:'post',
                data:{action:'getCat'},
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
    	// on change category show items
    	$(document).on("change","#selctD",function(){
    		$.ajax({
  				url:'processing/prices_processing.php',
               	type:'ajax',
               	method:'post',
              	data:{action:'getItem'},
                    success:function(all){
                       	var idata=$.parseJSON(all);
                       	var html='<option disabled selected>--Select Items--</option>';
                    	   	$.each(idata,function(key,val){
  								var did=$('#selctD').val();
  								// console.log(did);
                          		if(did==idata[key]['category_id']){
                              		html+='<option value="';
                              		html+=val.item_id;
                              		html+='">';
                              		html+=val.item_name;
                              		html+='</option>';
                          		}
                         	});

                          $('#selctI').html(html);
					},
  			});
  			// $("#selctI").show();
    	});

    	// Add Data with Ajax
		$(document).on("submit","#form",function(event){
			event.preventDefault();
			var fdata=$(this).serialize();

			$.ajax({
					url :'processing/prices_processing.php',
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

	 					$("#size").val("");
	 					$("#price").val("");
	 					$("#selctI").val("");
	 					// $("#selctI").hide();
	 					showCat();
	 					

	 					$("#submit").removeClass();
						$("#submit").addClass("btn btn-primary form-control mt-2");
						$("#submit").val("Add Prices");
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
              url:'processing/prices_processing.php',
              method:'POST',
              type:'ajax',
              data:{action:'showitem'}
            },

            "columns":[
	            {data:"price_id"},
	            {data:"category_name"},
	            {data:"item_name"},
	            {data:"size"},
	            {data:"price"},
	            
	            {data:null,
	                render: function(data){
	            	    var id=data.price_id;
	                	return "<button class='btn btn-primary edit' id='"+id+"'><i class='bi bi-pencil-square'></i></button> <button class='btn btn-danger delete' id='"+id+"'><i class='bi bi-archive-fill'></i></button>"
	                }
	            },

			],
     	});
     	//Edit data with ajax
     	$(document).on("click",".edit",function(){
     		var id=$(this).attr("id");

			$.ajax({
				url:'processing/prices_processing.php',
				method:'POST',
				type:'ajax',
				data:{idd:id,action:"getSingleData"},
				success:function(eve){
					var d=$.parseJSON(eve);
					$("#selctI").hide();
					$("#selctD").hide();
					// $("#selctD").val(d.category_id);
					// $("#selctI").val(d.item_id);
					$("#size").val(d.size);
					$("#price").val(d.price);
 					

					
					$("#submit").removeClass();
					$("#submit").addClass("btn btn-warning form-control mt-2");
					$("#submit").val("Update Prices");
					$("#action").val("update");
					$("#id").val(d.price_id);
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
								url:'processing/prices_processing.php',
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