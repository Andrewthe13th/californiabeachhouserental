<!DOCTYPE HTML>
<html>
<body>
      <title><b>CUSTOMER BILL INFORMATION For</b></title>
      <br/>
      <?php
         if (isset($_POST["submit"])) {
            if (isset($_POST["email"])) {
               $email= $_POST["email"];
            } else {
               $email= "unknown";
            }
			
            echo "<b> " . "     (email: {$email})</b><br/>";
			
			// 1. Create a database connection
			$dbhost = "ecsmysql";
			$dbuser = "cs332u8";
			$dbpass = "cohoilah";
			$dbname = "cs332u8";
			$dbconnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
			// Check if the connection is ok
			if (mysqli_connect_errno()) {
				die("Database connection failed: " .
					mysqli_connect_error() .
					" (" > mysqli_connect_errno() . ")"
				);
			}
			
			// 2. Perform database query
			$query = "SELECT *";
			$query .= "FROM BILLVIEW WHERE EMAIL = '$email';";
	
			$query2 = "SELECT *";
			$query2 .= "FROM BILLSUM WHERE EMAIL = '$email';";

			$query3 = "SELECT *";
			$query3 .= "FROM BILLDATE WHERE REMAIL = '$email';";
	
			$result = mysqli_query($dbconnection, $query);
			$result2 = mysqli_query($dbconnection, $query2);
			$result3 = mysqli_query($dbconnection, $query3);



			// Check if there is a query error
			if (!$result) {
				die("Database query failed.");
			}

	   echo "<h1> RESERVATION INFORMATION </h1>";

		//BILL DATE TABLE
 		echo "<br/><table border=\"1\">\n";
	        echo "<tr><th bgcolor=\"#ff90b7\">EMAIL</th>
		      <th bgcolor=\"#ff90b7\">HOUSE NUMBER</th> 
		      <th bgcolor=\"#ff90b7\">START DATE</th> 
		      <th bgcolor=\"#ff90b7\">END DATE</th> 



		</tr>";

			$num_results3 = mysqli_num_rows($result3);

			//LOOP FOR RESULTS 3
			for ($kx=0; $kx < $num_results3; $kx++) {
		       // process results3
			   $row3 = mysqli_fetch_assoc($result3);
			   
			   // output each bill for the customer 
			   echo "<tr>
			         <td align=\"right\">".$row3["REMAIL"]."</td>
			         <td align=\"right\">".$row3["ROOMID"]."</td>
			         <td align=\"right\">".$row3["START"]."</td>
			         <td align=\"right\">".$row3["END"]."</td>
					
			         </tr>";
			}
			echo "</table>";

	

	   echo "<h1> BILL INFORMATION </h1>";
			
           echo "<br/><table border=\"2\">\n";
	        echo "<tr><th bgcolor=\"#ff90b7\">Email</th> 
	              <th bgcolor=\"#ff90b7\">First Name</th> 
		      <th bgcolor=\"#ff90b7\">Last Name</th> 
	              <th bgcolor=\"#ff90b7\">Date Of Birth</th>
	              <th bgcolor=\"#ff90b7\">Bill Id</th>
	              <th bgcolor=\"#ff90b7\">House Number</th>
                      <th bgcolor=\"#ff90b7\">Total Days</th>
                      <th bgcolor=\"#ff90b7\">Price</th>
	              <th bgcolor=\"#ff90b7\">Total</th>
	              <th bgcolor=\"#ff90b7\">Status</th>
	              </tr>";
				  
		   	// 3. Use returned result
		    //    Get the number of rows returned by the query
			$num_results = mysqli_num_rows($result);

 			for ($ix=0; $ix < $num_results; $ix++) {
		       // process results
			   $row = mysqli_fetch_assoc($result);
			   
			   // output each bill for the customer 
			   echo "<tr>
			         <td align=\"right\">".$row["EMAIL"]."</td>
					 <td align=\"right\">".$row["FNAME"]."</td> 
					 <td align=\"right\">".$row["LNAME"]."</td> 
					 <td align=\"right\">".$row["DOB"]."</td> 
					 <td align=\"right\">".$row["ID"]."</td> 
					 <td align=\"right\">".$row["ROOMNUMBER"]."</td> 
 				         <td align=\"right\">".$row["TOTALDAYS"]."</td>
                                         <td align=\"right\">".$row["PRICE"]."</td>
 					 <td align=\"right\">".$row["TOTAL"]."</td> 
					 <td align=\"right\">".$row["STATUS"]."</td>

			         </tr>";
			}
			echo "</table>";

	 	echo "<h1> TOTAL </h1>";

		//BILL SUM TABLE
 		echo "<br/><table border=\"1\">\n";
	        echo "<tr><th bgcolor=\"#ff90b7\">EMAIL</th>
		      <th bgcolor=\"#ff90b7\">BILL SUM</th> 

		</tr>";

			$num_results2 = mysqli_num_rows($result2);

			//LOOP FOR RESULTS 2
			for ($kx=0; $kx < $num_results2; $kx++) {
		       // process results2
			   $row2 = mysqli_fetch_assoc($result2);
			   
			   // output each bill for the customer 
			   echo "<tr>
			         <td align=\"right\">".$row2["EMAIL"]."</td>
			         <td align=\"right\">".$row2["SUM(TOTAL)"]."</td>
					
			         </tr>";
			}
			echo "</table>";


	    	   // 4. Release returned result
	 		   mysqli_free_result($result);
	 		   mysqli_free_result($result2);
			   mysqli_free_result($result3);


		   
			   // 5. Close the database connection
			   mysqli_close($dbconnection);
		    
         } else {
            echo "Customer email not submited <br/>";
         }
      ?>
</body>
</html>
