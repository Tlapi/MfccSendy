<?php

require_once('../vendor/mandrill/mandrill/src/Mandrill.php');
		
	
	/*	$mt = new  Mandrill_Templates($mandrill);
		$ms = new MandrillMessageSet();
		$ms = populateMessageSet($ms,$row);
		$result = $mt->render($ms->getTemplate(), $ms->getTemplateContent());
		if(!array_key_exists("html",$result))
		{
			echo json_encode($result);
				}
		else
		{	//write to external file to include in iframe
			file_put_contents('log/mail_preview.html',$result["html"]);
		}
		//var_dump(json_encode($result));
	}*/
	$mandrill = new Mandrill('SlcPQn3V1Ooa8grE5j9Liw');
	$rejects = new Mandrill_Rejects($mandrill);
	$result = $rejects->getList(null, true, null);

	$reasons = array('hard-bounce','soft-bounce','spam','unknown');
	/*{
		$_params = array("email" => $email, "include_expired" => $include_expired, "subaccount" => $subaccount);
		return $this->master->call('rejects/list', $_params);
	}*/
	$i=0;
	$rejects = array();
	$rejects['hard-bounce']=array();
	$rejects['soft-bounce']=array();
	$rejects['spam']=array();
	$rejects['unknown']=array();
	
	foreach($result as $row)
	{	$i++;
		//echo "$i ".$row['email']." ".$row['reason']."<br>";
		if($row['reason']=='hard-bounce') $rejects['hard-bounce'][]=$row['email'];
		elseif($row['reason']=='soft-bounce') $rejects['soft-bounce'][]=$row['email'];
		elseif($row['reason']=='spam') $rejects['spam'][]=$row['email'];
		else 
		{	$unknown[] = $rejects['unknown'][]=$row['email'];
			echo "$i ".$row['email']." ".$row['reason']."<br>";
		}
	}
	
	$mysqli = new mysqli("localhost","nh2100100","lebcynbil","nh2100100db");
	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') '
				. $mysqli->connect_error);
	}
	
	echo 'Success... ' . $mysqli->host_info . "<br>";
	
	foreach($reasons as $reason)
	{	echo "<br>$reason<br>";
		if($reason=='soft-bounce') $reason_id = 3;
		if($reason=='hard-bounce') $reason_id = 4;
		if($reason=='spam') $reason_id = 5;
		$query = 'select id from subscribers where email in ("'.implode($rejects[$reason],'", "').'")';
		$q_result = $mysqli->query($query);
		
		if (!$q_result) {
			echo "Error: $mysqli->error<br>";
		}
		
		$tmp = array();
		while($row = $q_result->fetch_array())
		{	$tmp[]=$row['id'];
		}
		$query = 'update lists_to_subscribers set status='.$reason_id.' where subscriber_id in ('.implode($tmp,', ').')';
		echo $query;
		$q_result = $mysqli->query($query);
		echo "<br>$reason $q_result";
		
	}

echo 'success2';
