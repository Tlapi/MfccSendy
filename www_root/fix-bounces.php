<?php 

$mysqli = new mysqli("localhost","nh2100100","lebcynbil","nh2100100db");
if ($mysqli->connect_error) {
	die('Connect Error (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
}

echo 'Success... ' . $mysqli->host_info . "<br>";

$result = $mysqli->query("select * from subscribers AS s LEFT JOIN lists_to_subscribers AS l ON s.id = l.subscriber_id where s.bounced_hard = 1");

if (!$result) {
	echo "Error: $mysqli->error<br>";
}

$i=0;
$ids=array();
while($row = $result->fetch_array())
{	$i++;
	echo $i.': '.$row['id'].' '.$row['email'].' '.$row['bounced_hard'].' '.$row['bounce_message'].' '.$row['list_id'].' '.$row['status'].'<br>';
	$ids[]=$row['id'];
}

$query = "update lists_to_subscribers set status=4 where subscriber_id in (".implode($ids,', ').')';

$result = $mysqli->query($query);

if (!$result) {
	echo "Error: $mysqli->error<br>";
}
else echo $result;

$mysqli->close();

/*
mysqli_fetch_assoc($result)
mysqli_num_rows($result)
mysqli_insert_id($cxn)
*/