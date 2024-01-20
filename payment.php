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
		<h1 class="profile display-4 text-center mt-5 form-control bg-primary text-white">Payment Record</h1>
		<div class="col-md-3 col-12">
			<form id="form">
				<input type="hidden" name="action" value="add" id="action" class=" form-control">
				<input type="hidden" name="id" id="id" class=" form-control">

				<input type="date" name="date" id="date" class="form-control mt-2" value="<?php echo Date('Y-m-d');?>" required="">
				<!-- <select id="selectP" name="purchaser" class="form-control mt-2" title="please select purchaser"  required="">
					
				</select> -->
				<input type="text" name="purchaser" id="" placeholder="Select Purchaser" class="form-control mt-2" required="" pattern="[a-zA-Z\s]*" title="Hamza">					
				<input type="number" name="amount" id="amount" placeholder="Enter Amount" class="form-control mt-2" required="">
				

				<input type="submit" value="Add Purchaser" id="submit" class="btn btn-primary mt-2 form-control">		
			</form>
		</div>
		<div class="col-md-9 col-12 mt-2">
			<div class="table-responsive">
				<div class="table-responsive">
					<table id="tbl" class="table  table-striped">
				    	<thead>
				    		<tr>
				    			<th>Purchaser Name</th>
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
</div>
<?php 
 	include_once 'layout/footer.php';
 	}else{
 		header("location:login.php");
	}
?>
<script type="text/javascript">
	$(document).ready(function(){
		showPurchaser();
		function showPurchaser(){
     		$.ajax({
            	url:'processing/payment_processing.php',
                type:'ajax',
                method:'post',
                data:{action:'getPurchaser'},
                    success:function(all){
                        var var1=$.parseJSON(all);
                        var cd='<option disabled selected>-- Select Purchaser --</option>';
	                        $.each(var1,function(key,val){
	                            cd+='<option value="';
	                            cd+=val.id;
	                            cd+='">';
	                            cd+=val.customer_name;
	                            cd+='</option>';
	                        });

                        $('#selectP').html(cd);
					},
            });
    	}

    	

		// show data with ajax table
 		var tbl= $("#tbl").DataTable({
            "paging":true,
            "lengthChange":true,
            "ordering":true,
            "info":true,
            "searching":true,

             "ajax":{
              url:'processing/payment_processing.php',
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
	            	    var id=data.purchaser_id;
	                	return "<a href='payment_detail.php?id="+id+"' class='btn btn-primary'>Detail</a>"
	                }
	            },
	            
			],
     	});

     	// Add Data with Ajax
		$(document).on("submit","#form",function(event){
			event.preventDefault();
			var fdata=$(this).serialize();

			$.ajax({
					url :'processing/payment_processing.php',
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
	 					
	 					
	 					showPurchaser();

	 					$("#submit").removeClass();
						$("#submit").addClass("btn btn-primary form-control mt-2");
						$("#submit").val("Add Purchaser");
						// $("#action").val("update");

	 					tbl.ajax.reload();
	 					$("#selectP").show();
						
 					}
 					
 				}	
			});
		});
	});
</script>
