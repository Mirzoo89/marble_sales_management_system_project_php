<?php 
	session_start();
	if(isset($_SESSION['AdminLogedIn']) AND $_SESSION['AdminLogedIn']==true OR  
    isset($_SESSION['ManagerLogedIn']) AND $_SESSION['ManagerLogedIn']==true){
		include_once "layout/header.php";
	    include_once "layout/sidebar.php";
	    include_once "processing/dbcon.php";
	    include_once "layout/nav.php";

	    $id=$_GET['id'];

        $selectQuery="SELECT * FROM receipt_payment JOIN customer ON customer.id=receipt_payment.customer_id WHERE customer_id='$id' AND rp_type='purchaser'";
        $results=mysqli_query($conn,$selectQuery);

        
        $sel="SELECT * FROM customer WHERE id='$id'";
       
        $query=mysqli_query($conn,$sel);
        $r=mysqli_fetch_assoc($query);
       
        
        
    

?>
<style type="text/css">
	.profile{
		height: 80px;
		font-size: 45px; 
		font-weight: 200;
	}	
</style>
<div class="container">
	<div class="row" >
		<h1 class="profile display-4 text-center mt-3 form-control bg-primary text-white">Paid Payment Detail</h1>

		<div class="col-md-12  col-12 mt-2" id="datt">
			<h2 class="text-center mt-5">SADIQ MARBLES FACTORY</h2>
			<p class="text-center">0333-6694089 / 0305-7454389 <br> Faisalabad Road Samundri Near WAPDA House </p>
			<div class="row mt-3">
				<div class="col-6 ">
					<h6 class="mt-2 mb-3">Purchaser Name: <u><?php echo $r['customer_name']?></u></h6>
				</div>
			
				<div class="col-6 ">
					<h6 class="mt-2 mb-3">Contact NO.: <u><?php echo $r['contact']?></u></h6>
				</div>
			</div>
			<table id="tbl" class="table table-striped border">
		    	<thead>
		    		<tr>	
		    			<th>Date</th>
		    			<th>Amount</th>
		    					    			
		    		</tr>
		    	</thead>
		    	<tbody>
		    		<?php 
		    			while($row=mysqli_fetch_assoc($results)){


            				echo "<tr>";
            					echo "<td>";
            						echo $row['date'];
            					echo "</td>";

            					echo "<td>";
            						echo $row['rp_amount'];
            					echo "</td>";
            				echo "</tr>";
									
								
       					}
       					// Total Amount
       					$selt="SELECT sum(rp_amount) as Total FROM receipt_payment WHERE customer_id='$id' AND rp_type='purchaser'";
				        $rsult=mysqli_query($conn,$selt);
				        $ro=mysqli_fetch_assoc($rsult);
				        $Total=$ro['Total'];
		    		?>
		    		<tr class="bg-dark">
		    			<th class="text-danger text-center">Total:</th>
		    			<th class="text-danger "><?php  echo $Total; ?></th>
		    				
		    		</tr>
		    	</tbody>
		    </table>
		    
		</div>
			<div class="col-4">
				<a href="payment.php" class="btn btn-primary mt-3 mr-3">Back</a>
		        <a href="" id="print" class="btn btn-warning  mt-3"><i class="bi bi-printer-fill"></i></a>
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
    	 $(document).on('click','#print',function(){
          	var dt=$('#datt').html();
          	var HTML='';
            	HTML+='<html><head><title>Print</title>';
              	HTML+='<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css"/></head>';
              	HTML+='<body onload="window.print();"><div class="container borderd">';
              	
              			            		


              		
              			
              		HTML+=dt;
              		HTML+='<br>';
              
             

              
              
              HTML+='</div></body></html>'; 
             // console.log(dt);
          var newWin=window.open('','');
          newWin.document.open();
          newWin.document.write(HTML);
          newWin.document.close();
          });
       
    });
</script>