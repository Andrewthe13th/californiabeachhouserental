<?php

// Error Checking and Removes spacing
$bFname = true;
	if ($_POST[fname] == "" || ctype_space($_POST[fname])){
		$bFname = FALSE;
	} else{
		$_POST[fname] = trim($_POST[fname]);
	}
$bLname = true;
	if ($_POST[lname] == "" || ctype_space($_POST[lname])){
		$bLname = FALSE;
	} else{
		$_POST[lname] = trim($_POST[lname]);
	}	
$bEmail = true;
	if ($_POST[email] == "" || ctype_space($_POST[email])){
		$bEmail = FALSE;
	} else{
		$_POST[email] = trim($_POST[email]);
	}
$bBday = true;
	if ($_POST[bday] == ""){
		$bBday = FALSE;
	} else{
		$_POST[bday] = trim($_POST[bday]);
	}	

// If at least email was added
$bReservationCreated = FALSE;
if ($bEmail)
{
	///////// SQL is added here  /////////////////

	// 1. Create a database connection
      $dbhost = "ecsmysql";
      $dbuser = "cs332u8";  // where ?? is your id
      $dbpass = "cohoilah"; // replace with your password
      $dbname = "cs332u8";
      $dbconnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

       //Check if the connection is ok
      if (mysqli_connect_errno()) {
         die("Database connection failed: " .
         mysqli_connect_error() . " (" > mysqli_connect_errno() . ")" );
      }

	  /// IF Email is reused again
	  $query = "SELECT Email FROM PERSON WHERE Email = '$_POST[email]';";

	  $result = mysqli_query($dbconnection, $query);

		// Check if there is a query error for email
		if (!$result) {
			die( "<p><br>Email ERROR!!!/p>");
		}

		$something = mysqli_fetch_assoc($result);

		// if found an email comparison
		if ( !is_null($something) ) {
			if ($bFname && $bLname && $bEmail && $bBday){
			echo '<p style="color: red;">* Email has already been used within the database<p>';
			$bEmail = FALSE;
			}
			else { // database if user already exists in the database
				//echo "ALREADY EXISTS";
				// SQL INSERT INTO BILL
				$query =" INSERT INTO BILL (pEmail)"; 
				$query.=" VALUE ('$_POST[email]');"; 
				// SQL INSERT INTO WEBSITE
				$query.=" INSERT INTO RESERVES"; 
				$query.=" VALUES ('$_POST[startDate]','$_POST[endDate]', '$_POST[email]','$_POST[roomID]');";
				// SQL INSERT INTO HAS
				$query.=" INSERT INTO HAS (BillId, RoomId)"; 
				$query.=" VALUES ((SELECT Id FROM BILL"; 
				$query.=" ORDER BY Id DESC LIMIT 1), '$_POST[roomID]');";
				// SQL INSERT INTO PAYMENT
				$query.=" INSERT INTO PAYMENT VALUES( (SELECT Id ";
				$query.=" FROM BILL";
				$query.=" ORDER BY Id DESC LIMIT 1), '$_POST[email]');";
				// SQL Set Total Days
				$query.=" UPDATE HAS SET TotalDays = TIMESTAMPDIFF(DAY,'$_POST[startDate]','$_POST[endDate]')";
				$query.=" WHERE BillId = (SELECT Id FROM BILL";
				$query.=" ORDER BY Id DESC LIMIT 1);";
				// SQL Set Total Price in BILL
				$query.=" UPDATE BILL SET Total = ";
				$query.=" (SELECT TotalDays FROM HAS WHERE BillId = (SELECT BillId";
				$query.=" FROM HAS";
				$query.=" ORDER BY BillId DESC LIMIT 1)) * '$_POST[price]'";
				$query.=" WHERE Id = (";
				$query.=" SELECT BillId FROM HAS";
				$query.=" ORDER BY BillID DESC LIMIT 1);";

				//print "<br><br>$query";

				// SQL INJECTION into database
				$result = mysqli_multi_query($dbconnection, $query);

				// Check if there is a query error
				if (!$result) {
					die( "<p><br>Query ERROR!!!/p>");
				}

				// Removes the Sumbit button and allows user to return to home page
				$bReservationCreated = true;
				}
		}
		//then populate the database creating a new person info
		else if ($bFname && $bLname && $bEmail && $bBday){  

			////// SQL INSERT INTO PERSON
			$query="INSERT INTO PERSON ( Lname, Fname, Email, Dob )";  
			$query.=" VALUES ( '$_POST[lname]', '$_POST[fname]', '$_POST[email]', '$_POST[bday]' );";
			// SQL INSERT INTO BILL
			$query.=" INSERT INTO BILL (pEmail)"; 
			$query.=" VALUE ('$_POST[email]');"; 
			// SQL INSERT INTO WEBSITE
			$query.=" INSERT INTO RESERVES"; 
			$query.=" VALUES ('$_POST[startDate]','$_POST[endDate]', '$_POST[email]','$_POST[roomID]');";
			// SQL INSERT INTO HAS
			$query.=" INSERT INTO HAS (BillId, RoomId)"; 
			$query.=" VALUES ((SELECT Id FROM BILL"; 
			$query.=" ORDER BY Id DESC LIMIT 1), '$_POST[roomID]');";
			// SQL INSERT INTO PAYMENT
			$query.=" INSERT INTO PAYMENT VALUES( (SELECT Id ";
			$query.=" FROM BILL";
			$query.=" ORDER BY Id DESC LIMIT 1), '$_POST[email]');";
			// SQL Set Total Days
			$query.=" UPDATE HAS SET TotalDays = TIMESTAMPDIFF(DAY,'$_POST[startDate]','$_POST[endDate]')";
			$query.=" WHERE BillId = (SELECT Id FROM BILL";
			$query.=" ORDER BY Id DESC LIMIT 1);";
			// SQL Set Total Price in BILL
			$query.=" UPDATE BILL SET Total = ";
			$query.=" (SELECT TotalDays FROM HAS WHERE BillId = (SELECT BillId";
			$query.=" FROM HAS";
			$query.=" ORDER BY BillId DESC LIMIT 1)) * '$_POST[price]'";
			$query.=" WHERE Id = (";
			$query.=" SELECT BillId FROM HAS";
			$query.=" ORDER BY BillID DESC LIMIT 1);";

			//print "<br><br>$query";

			// SQL INJECTION into database
			$result = mysqli_multi_query($dbconnection, $query);

			// Check if there is a query error
			if (!$result) {
				die( "<p><br>Query ERROR!!!/p>");
			}


			// Removes the Sumbit button and allows user to return to home page
			$bReservationCreated = true;
		
		}
		else
			echo '<p style="color: red;">* Email has never been used before!!!<p>';
}

	
?>
<html>
<head>
</head>
<body>

	<!-- Shows Confirmation and a redirect back to the homepage  -->
	<? if ($bReservationCreated){ ?>

	<h1> Reservation has been Created!! </h1>
	<p>Click the checkmark to return to website</p>
	<a href="http://ecs.fullerton.edu/~cs332u8/">
	<img src="http://findicons.com/files/icons/2625/google_plus_interface_icons/128/checkmark2.png"
				style="width: 200px; height: 200px;">
	</a>
	<!-- Allows user to fill out the rest of the form  -->
	<?}else {?>

		<h1> Enter Your Personal Information<br>to Reserve a House</h1>
		<img src="../roomPics/<?echo $_POST[roomID]?>.jpg" 
					style=" height: 200px;">	
		<!--  Rest of the web Page  -->
		<form action="http://ecs.fullerton.edu/~cs332u8/createReservation/" method="post"> <br>
			Firstname:<br>
			<input type="text" name="fname" value="<? echo $_POST[fname]; ?>"/>
			<? if (!$bFname){ echo "* Enter a Name";} ?>
			<br><br>
			Lastname:<br>
			<input type="text" name="lname" value="<? echo $_POST[lname]; ?>"/>
			<? if (!$bLname){ echo "* Enter a Last Name";} ?>
			<br><br>
			Email:<br>
			<input type="email" name="email" value="<? echo $_POST[email]; ?>"/>
			<? if (!$bEmail){ echo "* Enter a Valid Email";} ?>
			<br><br>
			Date of Birth:<br> 
			<input type="date" name="bday" value="<? echo $_POST[bday] ?>"/>
			<? if (!$bBday){ echo " * Enter your Birthday";} ?>
			
			<br><br>

			<!-- Hidden Info make sure it stays upon refresh -->
			<input type="hidden" name="roomID" value="<? echo $_POST[roomID]; ?>"/>
			<input type="hidden" name="startDate" value="<? echo $_POST[startDate]; ?>"/>
			<input type="hidden" name="endDate" value="<? echo $_POST[endDate]; ?>"/>
			<input type="hidden" name="numOfBeds" value="<? echo $_POST[numOfBeds]; ?>"/>
			<input type="hidden" name="NoPeople" value="<? echo $_POST[NoPeople]; ?>"/>
			<input type="hidden" name="price" value="<? echo $_POST[price]; ?>"/>

			<!-- Hides Submit Button if reservation was created!!! -->
			<? if (!$bReservationCreated){ ?>
			<input type="submit" value="Reserve Room" /><br><br>
			<? } ?>
		</form>

		<!-- Or sign in with pre-existing infomation!!!!  -->
		<form action="http://ecs.fullerton.edu/~cs332u8/createReservation/" method="post">
			<h2>If Reserved Room before</h2>
			Enter email below:<br>
			<input type="email" name="email" value="<? echo $_POST[email]  ?>"></input>

			<!-- Hidden Info make sure it stays upon refresh -->
			<input type="hidden" name="fname" value="<? echo "" ?>"/>
			<input type="hidden" name="lname" value="<? echo "" ?>"/>
			<input type="hidden" name="bday" value="<? echo "" ?>"/>
			<input type="hidden" name="roomID" value="<? echo $_POST[roomID]; ?>"/>
			<input type="hidden" name="startDate" value="<? echo $_POST[startDate]; ?>"/>
			<input type="hidden" name="endDate" value="<? echo $_POST[endDate]; ?>"/>
			<input type="hidden" name="numOfBeds" value="<? echo $_POST[numOfBeds]; ?>"/>
			<input type="hidden" name="NoPeople" value="<? echo $_POST[NoPeople]; ?>"/>
			<input type="hidden" name="price" value="<? echo $_POST[price]; ?>"/>

			<input type="submit" value="Reserve Room" />
		</form>


		<!--  Add more later  -->
		
		<?php
		// echo "Room: $_POST[roomID]<br>";
		// echo "Start Date: $_POST[startDate]<br>";
		// echo "End Date: $_POST[endDate]<br>";
		// echo "# of Beds: $_POST[numOfBeds]<br>";
		// echo "# of People: $_POST[NoPeople]<br>";
		// echo "Price: $$_POST[price]";	
		?>			

	<?} //End off the Webpage  ?>
	
</body>
</html>
