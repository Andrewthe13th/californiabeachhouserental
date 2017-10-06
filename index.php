<!DOCTYPE>
<html>
<head>
	<title>Team RTFM</title>
	<link rel="stylesheet" href="styles/index.css">  
	
	<!-- Boot Strap 4  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	
</head>
<body>
<div class="container-fluid">
	<?
      // 1. Create a database connection
      $dbhost = "ecsmysql";
      $dbuser = "cs332u8";  // where ?? is your id
      $dbpass = "cohoilah"; // replace with your password 
      $dbname = "cs332u8";
      $dbconnection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

      // Check if the connection is ok
      if (mysqli_connect_errno()) {
         die("Database connection failed: " .
         mysqli_connect_error() . " (" > mysqli_connect_errno() . ")" );
      }

   ?>
	<div id="topOfPage">
		<a href="http://ecs.fullerton.edu/~cs332u8" id="Homepage" style="display: inline-block;">
			<div >
				<h4>California<br>Beach Home Rentals</h4>
			</div>
		</a>
		<br>
		<div display:>
		<h3>Log In</h3>
		<form action="http://ecs.fullerton.edu/~cs332u8/clientInfo/" method="post" style="display: inline-block;">
			Enter Email:
			<input type="email" name="email"></input>
			<input type="submit" name="submit" value='submit'/>
		</form>
		</div>
	</div>	
	
	<div id="coolPicture">
		<img src="https://s-media-cache-ak0.pinimg.com/originals/ac/80/71/ac8071778fdfb53c1f874f70b11091c7.jpg"
		style="height: 260px;">
	</div>
	
	<!--  Search Parameters  -->
	<div id="search">
		Search for Houses<br>
		<form action="http://ecs.fullerton.edu/~cs332u8/" method="post">
		   <!-- Dosen't work without this frontlash    ^    -->
			Start Date:
			<input type="date" name="startDate" value="<? echo $_POST[startDate] ?>"></input>
			End Date:
			<input type="date" name="endDate" value="<? echo $_POST[endDate] ?>"></input>
			<br>Number of Beds:
			<input type="text" name="numOfBeds" value="<? echo $_POST[numOfBeds] ?>"></input>
			<input type="submit"></input>
		</form>
	</div>
	<!-- End of Search Parameters-->
	
	<?  // Check is no sorting methods is selected
		if ("" == trim($_POST['sort'])){
    		$_POST['sort'] = "highestPrice";}  ?>
	
	<!-- Drop Down View of Sorting methods  -->
	<div id="results">
		<br>Sort By 
		<form action="http://ecs.fullerton.edu/~cs332u8/" method="post">
			<select name="sort">
				<option value="highestPrice">Highest Price</option>
				<option value="lowestPrice">Lowest Price</option>
				<option value="personCapacity">Person Capacity</option>
			</select>
			<input type="hidden" name="startDate" value="<? echo $_POST[startDate] ?>"></input>
			<input type="hidden" name="endDate" value="<? echo $_POST[endDate] ?>"></input>
			<input type="hidden" name="numOfBeds" value="<? echo $_POST[numOfBeds] ?>"></input>
			<input type="submit" value="submit"/>
		</form>

		<!-- php here for display each room info  (do like a for loop)-->
		<?php 
			// SQL STATEMENT FOR HIGHEST PRICE SEARCH

			if ($_POST['sort'] == "highestPrice")
			{
				$query="SELECT RoomNumber,NoPeople,Price"; 
				$query.=" FROM ROOM";
				$query.=" WHERE NoBeds = $_POST[numOfBeds]";
				$query.=" AND RoomNumber";
				$query.=" NOT IN (";
				$query.=" SELECT roomId";
				$query.=" FROM RESERVES";
				$query.=" WHERE  Start BETWEEN '$_POST[startDate]' AND '$_POST[endDate]'";  
				$query.=" OR";
				$query.=" End BETWEEN '$_POST[startDate]' AND '$_POST[endDate]')";
				$query.=" ORDER BY Price DESC;";
			}
			else if ($_POST['sort'] == "lowestPrice")
			{
				$query="SELECT RoomNumber,NoPeople,Price"; 
				$query.=" FROM ROOM";
				$query.=" WHERE NoBeds = $_POST[numOfBeds]";
				$query.=" AND RoomNumber";
				$query.=" NOT IN (";
				$query.=" SELECT roomId";
				$query.=" FROM RESERVES";
				$query.=" WHERE  Start BETWEEN '$_POST[startDate]' AND '$_POST[endDate]'";  
				$query.=" OR";
				$query.=" End BETWEEN '$_POST[startDate]' AND '$_POST[endDate]')";
				$query.=" ORDER BY Price ASC;";
			}
			else if ($_POST['sort'] == "personCapacity")
			{
				$query="SELECT RoomNumber,NoPeople,Price"; 
				$query.=" FROM ROOM";
				$query.=" WHERE NoBeds = $_POST[numOfBeds]";
				$query.=" AND RoomNumber";
				$query.=" NOT IN (";
				$query.=" SELECT roomId";
				$query.=" FROM RESERVES";
				$query.=" WHERE  Start BETWEEN '$_POST[startDate]' AND '$_POST[endDate]'";  
				$query.=" OR";
				$query.=" End BETWEEN '$_POST[startDate]' AND '$_POST[endDate]')";
				$query.=" ORDER BY NoPeople DESC;";
			}
		
			// SQL INJECTION into database
			$result = mysqli_query($dbconnection, $query);

			// Check if there is a query error
			if (!$result) {
				die( "<p><br>Please Search For a House</p>");
			}
			else
			{
				if ( strtotime($_POST[startDate])  > strtotime($_POST[endDate])) 
				{
					echo "<p><br>Please Enter a Valid Date Format!!!<p>";
				}
				else
				{
			// Gathers Data for each row found from the SQL Statement
					while($row = mysqli_fetch_assoc($result))
					{ 
					?>
						<div class="home">
						<img src="roomPics/<?echo $row[RoomNumber];?>.jpg" 
						style="width: 100px; height: 100px;">
						<div class="homeInfo">
							<ul>
								<li>House # <? echo $row[RoomNumber]; ?></li>
								<li>Start Date: <? echo $_POST[startDate]; ?></li>
								<li>End Date: <? echo $_POST[endDate]; ?></li>
								<li><? echo $_POST[numOfBeds]; ?> bedrooms</li>
								<li>Up to <? echo $row[NoPeople]; ?> People</li>
							</ul>
						</div>
						<div class="homePrice_Reserve">
							<p>$<? echo $row[Price]; ?> Per Night</p>

							<form action="http://ecs.fullerton.edu/~cs332u8/createReservation/" method="post">
								<input type="hidden" name="roomID" value="<? echo $row[RoomNumber]; ?>"></input>
								<input type="hidden" name="startDate" value="<? echo $_POST[startDate]; ?>"></input>
								<input type="hidden" name="endDate" value="<? echo $_POST[endDate]; ?>"></input>
								<input type="hidden" name="numOfBeds" value="<? echo $_POST[numOfBeds]; ?>"></input>
								<input type="hidden" name="NoPeople" value="<? echo $row[NoPeople]; ?>"></input>
								<input type="hidden" name="price" value="<? echo $row[Price]; ?>"></input>
								<input type="submit" value="Reserve Room"></input>
							</form>
						</div>
						</div>	
						
				<?php
					}
				}
			}
		?>
		<!-- End of rooms -->
		
		
	</div>

	<div>
	<ul id="bottomTabs">
			<li>FAQ</li>
			<li>Careers</li>
			<li>Contact</li>				
			<li>Terms Of Use</li>
		</ul>
	</div>
</div>
</body>
</html>
