<?php 

$mysqli = new mysqli("localhost","nh2100100","lebcynbil","nh2100100db");
if ($mysqli->connect_error) {
	die('Connect Error (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
}

echo 'Success... ' . $mysqli->host_info . "<br>";

$result = $mysqli->query('select value from custom_fields where name="last_id"');
$rrow = $result->fetch_array();
$last_id = intval($rrow['value']);

echo 'Success... '.$last_id.' '.($last_id+100);
 
$result = $mysqli->query("insert into subscribers_cross (m_id,ext_id) select s.id, se.id from (select * from subscribers_ext where id>=".$last_id." AND id<".($last_id+1000).") as se join subscribers as s on se.email = s.email");

if (!$result) {
	echo "Error: $mysqli->error<br>";
}

$query = 'update custom_fields set value='.($last_id+100).' where name="last_id"';

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