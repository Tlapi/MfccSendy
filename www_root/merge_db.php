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

$result = $mysqli->query('select value from custom_fields where name="merge_lock"');
$rrow = $result->fetch_array();
$lock = intval($rrow['value']);

if($lock) die();

$query = 'update custom_fields set value=1 where name="merge_lock"';
$result = $mysqli->query($query);

$step = 2000;

echo 'Success... '.$last_id.' '.($last_id+$step);
 
$result = $mysqli->query("insert into subscribers_cross (m_id,ext_id) select s.id, se.id from (select * from subscribers_ext where id>=".$last_id." AND id<".($last_id+$step).") as se join subscribers as s on se.email = s.email");

if (!$result) {
	echo "Error: $mysqli->error<br>";
}

$query = 'update custom_fields set value=0 where name="merge_lock"';
$result = $mysqli->query($query);

$query = 'update custom_fields set value='.($last_id+$step).' where name="last_id"';
echo done;
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