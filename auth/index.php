<?php
session_start();	

// Show Debug information
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

function str_to_unix($date)
{
	$d = new DateTime($date);
	$timestamp = $d->getTimestamp(); 
	return $timestamp;
}

function unix_time_milisecounds_to_date($unixtime) { 
	$date = new DateTime();
	$date->setTimestamp(($unixtime/1000));
	return $date->format('Y-m-d');
}


/** select the calendar info on the wordpress database. */
require_once('function.php');


require_once 'Google/autoload.php';


/************************************************	
 The following 3 values an be found in the setting	
 for the application you created on Google 	 	
 Developers console.	 	 Developers console.
 The Key file should be placed in a location	 
 that is not accessible from the web. outside of 
 web root.	 
 	 	 
 In order to access your GA account you must	
 Add the Email address as a user at the 	
 ACCOUNT Level in the GA admin. 	 	
 ************************************************/
if(@$_GET["cid"] == NULL)
	exit();
 $calendar_info = calender_info($_GET["cid"]);
 
if($calendar_info['id'] == NULL) exit();
 	
$client_id = '[your client]';
$Email_address = $calendar_info['email_account'];

//$key_file_location = 'p12files/calendar_'.$calendar_info['id'].'.p12';	 	

$config = new Google_Config();
$config->setClassConfig('Google_Cache_File', array('directory' => '../tmp/cache/'.$calendar_info['id']));

$client = new Google_Client($config);	 	
$client->setApplicationName("Client_Library_Examples");

//$key = file_get_contents($key_file_location);	 
$key = $calendar_info['key'];

// separate additional scopes with a comma	 
$scopes ="https://www.googleapis.com/auth/calendar.readonly"; 	
$cred = new Google_Auth_AssertionCredentials(	 
	$Email_address,	 	 
	array($scopes),	 	
	$key 
	);	 	
$client->setAssertionCredentials($cred);
if($client->getAuth()->isAccessTokenExpired()) {	 	
	$client->getAuth()->refreshTokenWithAssertion($cred);	 	
}	 	
$service = new Google_Service_Calendar($client);    



// Check to ensure that the access token was successfully acquired.
if ($client->getAccessToken()) {

  try {
	$out = array();
    // Execute an API request that lists the streams owned by the user who
    // authorized the request.
	// Print the next 10 events on the user's calendar.
	if(isset($_GET["from"]) && isset($_GET["to"])) {
		$startdate = unix_time_milisecounds_to_date($_GET["from"]);
		$enddate = unix_time_milisecounds_to_date($_GET["to"]);
	} else if(isset($_GET["startdate"]) && isset($_GET["enddate"])) {
		$startdate = $_GET["startdate"];
		$enddate = $_GET["enddate"];
	} else {
		//echo "startdate/enddate must specific!";
		//die();
		$startdate = $_GET["start"];
		$enddate = $_GET["end"];
	}
	$calendarId = $calendar_info['calendar_id'];	//'fablabajaccio.com_q611r46bo0rctrqukm78a3ljsk@group.calendar.google.com';
	$optParams = array(
	  //'maxResults' => 10,
	  //'orderBy' => 'updated',
	  'singleEvents' => TRUE,
	  //'timeMin' => date('c'),
	  'timeMin' => $startdate.'T00:00:00+03:30',
	  'timeMax' => $enddate.'T23:59:59+03:30',
          'fields' =>'items',
	);
	$results = $service->events->listEvents($calendarId, $optParams);
	//$results = $service->events->listEvents($calendarId);

	if (count($results->getItems()) == 0) {
	  print "No upcoming events found.\n";
	} else {
	  //print "Upcoming events:<br>";
	  foreach ($results->getItems() as $event) {

	    $start = $event->start->dateTime;
	    if (empty($start)) {
	      $start = $event->start->date;
	    }

	    $end = $event->end->dateTime;
	    if (empty($end)) {
	      $end = $event->end->date;
	    }          
		
	    //printf("<a href='%s'>%s (%s)</a>\n", $event->htmlLink, $event->getSummary(), $start);
	    //echo "<br>****************************************************************************<br>";
	    //var_dump(get_object_vars($event));
	    //var_dump(get_object_vars($event->start));
            //var_dump(get_object_vars($event->end));
	    //$events[] = array($event,array("start" => $start),array("end" => $end));
	    $event->start = $start;
	    $event->end = $end;
        $events[] = $event;
       

	    $out[] = array(
		'id' => $event->id,
		'title' => $event->summary,
		'url' => 'wp-admin/admin-ajax.php?action=maps_booking_system_do_ajax&id='.$event->id,
		'class' => 'event-important',
	//	'start' => str_to_unix($start).'000',
	//	'end' => str_to_unix($end).'000',
		'start' => $start,
		'end' => $end

	    );
	  }
	//echo("Json:<br>");
	//echo json_encode($events);
	//echo json_encode(array('success' => 1, 'result' => $out));
	echo json_encode($out);
	exit;
	}

   }  catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  }
}


