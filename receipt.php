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
		<h1 class="profile display-4 text-center mt-3 form-control bg-primary text-white">Receipt Record</h1>
		<div class="col-md-3 col-12">
			<form id="form">
				<input type="hidden" name="action" value="add" id="action" class=" form-control">
				<input type="hidden" name="id" id="id" class=" form-control">

				<input type="date" name="date" id="date" class="form-control mt-2" value="<?php echo Date('Y-m-d');?>" required="">
				<select id="selectS" name="seller" class="form-control mt-2"  required="">
					
				</select>
					
				<input type="number" name="amount" id="amount" placeholder="Enter Amount" class="form-control mt-2" required="">
				

				<input type="submit" value="Add Salles" id="submit" class="btn btn-primary mt-2 form-control">		
			</form>
		</div>
		<div class="col-md-9 col-12 mt-2 ">
			<div class="table-responsive">
				<div class="table-responsive">
					<table id="tbl" class="table  table-striped">
				    	<thead>
				    		<tr>
				    			<th>Seller Name</th>
				    			<th>Total Amount</th>
				    			<th>Paid Amount</th>
				    			<th>Pending Amount</th>		    			
				    			<th>Detail</th>		    			
				    		</tr>
				    	</thead>
				    	<tbody>
				    		
				    	</tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>	

	<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title text-center" id="cname"></h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table">
        	<thead>
        	<tr>
        		<th>Date</th>
        		<th>Amount</th>
        	</tr>
        	</thead>
        	<tbody id="body">
        		
        	</tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
       
      </div>
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
            	url:'processing/receipt_processing.php',
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
    	// Add Data with Ajax
		$(document).on("submit","#form",function(event){
			event.preventDefault();
			var fdata=$(this).serialize();

			$.ajax({
					url :'processing/receipt_processing.php',
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

	 					
	 					$("#amount").val("");
	 					// $("#date").val("");
	 					
	 					
	 					showSeller();

	 					$("#submit").removeClass();
						$("#submit").addClass("btn btn-primary form-control mt-2");
						$("#submit").val("Add Seller");
						// $("#action").val("update");

	 					tbl.ajax.reload();
	 					$("#selectS").show();
						
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
              url:'processing/receipt_processing.php',
              method:'POST',
              type:'ajax',
              data:{action:'showitem'}
            },

            "columns":[
	            
	            {data:"customer_name"},
	            {data:null,
                	render:function(data){
                 		var total= parseInt(data.total);
                 		return total;
                	}
              	},
              	{data:null,
	                render:function(data){
	                 	var paid=0;
	                 	if(data.paid){
	                  		paid=data.paid;
	                 	}
	                 		return paid;
	                }
              	},
              	{data:null,
                 	render: function(data){
                  		var pending=(parseInt(data.total))-data.paid;
                  		return pending;
                 	}
              	},
              	{data:null,
	                render: function(data){
	            	    var id=data.seller_id;
	                	return "<a href='receipt_detail.php?id="+id+"' class='btn btn-primary'>Detail</a>"
	                }
	            },
	            
			],
     	});

   //   	$(document).on("click",".detail",function(){
   //   		var id=$(this).attr("id");

			// $.ajax({
			// 	url:'processing/receipt_processing.php',
			// 	method:'POST',
			// 	type:'ajax',
			// 	data:{idd:id,action:"getDetail"},
			// 	success:function(eve){
			// 		var data=$.parseJSON(eve);
			// 		var name=data[0]['customer_name'];
			// 		var tbl='';
			// 		$.each(data,function(key,val){
			// 			tbl+='<tr>';
			// 				tbl+='<td>';
			// 				  tbl+="hello";
			// 				tbl+='</td>';
			// 			tbl+='</tr>';
			// 		$("#body").html(tbl);

			// 		});
					
			// 		$("#cname").text(name);
					

			// 		$("#exampleModal").modal("show");



			// 		$.each(data,function(key,val){
						
			// 		});


			// 	}
			// });
   //   	});
	});
</script>