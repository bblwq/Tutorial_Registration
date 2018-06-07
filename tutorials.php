<html>
<head>
  <title>Tutorial session selection</title>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-93511998-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-93511998-1');
  </script>
</head>

<body>
  <h1>Choose your own tutorial session for the next week here!</h1>

<?php
  // Create a session.
  session_start();

  // Initialise the session data with name and email address provided last time.
  $_SESSION['name'] = $_REQUEST['fullName'];
  $_SESSION['email'] = $_REQUEST['emailAddress'];

  // Specify the database access details.
  $db_hostname = 'localhost';
  $db_database = 'bblwq';
  $db_username = 'pi';
  $db_password = 'Pa$$w0rd';

  // Open a connection to the MySQL Server.
  $con = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

  // Return an error message if the connection is not successful.
  if (!$con) die("Unable to connect to MySQL: ".mysqli_connect_error());

  // Calculate the total remaining places of tutorial sessions.
  $queryCapability = "select remainPLace from session;";
  $resultCapability = mysqli_query($con,$queryCapability);
  $resultCapability || die("Database access failed: ".mysqli_error($con));
  $totalCapability = 0;
  while ($Capability = mysqli_fetch_array($resultCapability)) {
    $totalCapability += $Capability[0];
  }
  mysqli_free_result($resultCapability);

  // All places on tutorial sessions have been already allocated.
  if($totalCapability <= 0) {
	echo "<h2>Sorry! All places on the tutorials sessions have already been allocated!</h2>";
  }

  // There is one or more remaining places on tutorial sessions.
  else {
    echo<<<DayFormStart
    <form name='DayForm' method='post' action='/tutorials.php'>
	Day: <select name='day' onChange='document.DayForm.submit()'>
	        <option value='none'>Select a day</option>
DayFormStart;

	// Show days of the week on which there has remaining places.
	$queryDays = "select distinct day from session where remainPLace != 0;";
    $resultDays = mysqli_query($con,$queryDays);
    $resultDays || die("Database access failed: ".mysqli_error($con));
    while ($option = mysqli_fetch_array($resultDays, MYSQLI_ASSOC)) {
	  // Set the option as default if the user has already selected it.
      if($_REQUEST['day'] == $option[day]) {
	    echo "<option value='$option[day]' selected>$option[day]</option>";
	  }
	  else {
        echo "<option value='$option[day]'>$option[day]</option>";
	  }
    }
	mysqli_free_result($resultDays);

	echo<<<DayFormEnd
      </select>
	  </form>
DayFormEnd;

    echo<<<TimeFormStart
	<form name="ExtraForm" method="post"
          action="/tutorials.php">
    Time: <select name="time">
          <option value=0>Select a time</option>
TimeFormStart;

    // The user has selected the day of the week.
    if ($_REQUEST['day']) {

      // Show all the available times on the day that the user selected.
	  $queryTime = "select sessionID, time from session where day = '$_REQUEST[day]' and remainPLace != 0;";
	  $resultTime = mysqli_query($con,$queryTime);
	  $resultTime || die("Database access failed: ".mysqli_error($con));
	  while ($choice = mysqli_fetch_array($resultTime, MYSQLI_ASSOC)) {
          echo "<option value=$choice[sessionID]>$choice[time]</option>";
      }
	  mysqli_free_result($resultTime);
	}

    echo<<<TimeFormEnd
          </select><br />
TimeFormEnd;

    echo<<<Detail
	<br />
	Full name: <input type="text" name="fullName" size="50" value="$_SESSION[name]" /><br /><br />
	E-mail Address: <input type="text" name="emailAddress" size="50" value="$_SESSION[email]" /><br /><br />
	Topics/Questions: <textarea name="question" rows="5" cols="40"></textarea><br /><br />
	<input type="submit" name="Submit" value="Submit"/>
	</form>\n
Detail;

    // The submit button is clicked.
    if ($_REQUEST['Submit']) {

	  // All the necessary data is provided.
	  if ($_REQUEST['time'] != 0 && $_REQUEST['fullName'] != "" && $_REQUEST['emailAddress'] != "" && $_REQUEST['question'] != "") {

	    // Calculate the number of remaining places of the selected session.
		$remain = 0;
		$queryRemain = "select remainPLace from session where sessionID='$_REQUEST[time]';";
		$resultRemain = mysqli_query($con,$queryRemain);
		$resultRemain || die("Database access failed: ".mysqli_error($con));
		while ($Capability = mysqli_fetch_array($resultRemain)) {
          $remain += $Capability[0];
        }
		mysqli_free_result($resultRemain);

		// There are places left on the selected session.
		if ($remain>0) {

		  // Attempt to insert the allocation data.
          if ($stmt = mysqli_prepare($con, "insert into allocation (emailAddress, name, sessionID, question) values(?,?,?,?);")) {
            mysqli_stmt_bind_param($stmt, 'ssis', $_REQUEST['emailAddress'], $_REQUEST['fullName'], $_REQUEST['time'], $_REQUEST['question']);
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

			// The insertion has been successful.
		    if($success) {

			  // Reduce the number of places on the session by 1.
              $queryUpdate = "update session set remainPLace=remainPLace-1 where sessionID='$_REQUEST[time]';";
		      $resultUpdate = mysqli_query($con,$queryUpdate);
              $resultUpdate || die("Database access failed: ".mysqli_error($con));
			  //mysqli_free_result($resultUpdate);

			  // Confirm the allocation by showing the session day and time.
			  $queryConfirm = "select day, time from session where sessionID='$_REQUEST[time]';";
		      $resultConfirm = mysqli_query($con,$queryConfirm);
              $resultConfirm || die("Database access failed: ".mysqli_error($con));
		      while($confirm = mysqli_fetch_array($resultConfirm, MYSQLI_ASSOC)) {
		        echo "<b>Confirmation: Your tutorial session for the next week is on $confirm[day], during $confirm[time]!!!<b/>";
			  }
			  mysqli_free_result($resultConfirm);

			  // Free all session variables currently registered.
			  session_unset();
		    }

			// Reject the request if a student has already selected the session for the next week.
		    else {
		      echo "<b>Warning: Your have already choose your tutorial session for the next week!!!<b/>";
		    }
          }
        }

		/* There are no places left on the selected session
		   which may indicated that two student attempt to choose a session simultaneously
		   when there is only one place left in total.
		*/
		else {
		  echo "<b>Sorry! The tutorial session you selected have just been allocated!</b>";
		}
	  }

	  // The allocation request does not contain all the necessary data.
	  else {

        // The day and time of the session was not specified.
	    if ($_REQUEST['time'] == 0) {
		  echo "<b>Please select the day and the time of session!</b><br />\n";
	    }

		// The full name was not provided.
	    if ($_REQUEST['fullName'] == "") {
		  echo "<b>Please enter your full name!</b><br />\n";
	    }

		// The email address was not provided.
        if ($_REQUEST['emailAddress'] == "") {
		  echo "<b>Please enter your e-mail address!</b><br />\n";
	    }

		// The topics or questions was not provided.
	    if ($_REQUEST['question'] == "") {
		  echo "<b>Please enter the topics or questions you would like to discuss in the session!</b><br />\n";
	    }
	  }
	}
  }

  // Closes the previously opened database connection.
  mysqli_close($con);

  // Destroy the current session.
  session_destroy();
 ?>
</body>
</html>
