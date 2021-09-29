<?php
	$account = $_POST['account'];
	$input = $_POST['input'];
	$now = date('Y-m-d H:i:s', strtotime("now"));		
	$count = 0;
	$return = false;
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	
	#要比對的資料儲存型態如果是字串需要加上'
	$result = $link -> query("SELECT * FROM schedule_1 WHERE owner_id = '".$account."'");
	while($row = $result->fetch_assoc()){
		if(!strcmp($input,"now")){
			if(date('Y-m-d H:i:s', strtotime($row['end'])) > $now)
				$return = true;
			
		}
		else if(!strcmp($input,"history")){
			if(date('Y-m-d H:i:s', strtotime($row['end'])) < $now)
				$return = true;
			#echo date('Y-m-d H:i:s', strtotime($row['end']));
			#echo $now;
		}
		if($return){
			$quest_id[] = $row['quest_id'];
			$start[] = $row['start'];
			$end[] = $row['end'];	
			$alone[] = $row['alone'];	
			//$other_users[] = $row['other_users'];
			$count = $count + 1;		
		}
		$return = false;
	}
	if($count > 0){
		echo json_encode($quest_id,JSON_UNESCAPED_UNICODE);
		echo json_encode($start,JSON_UNESCAPED_UNICODE);
		echo json_encode($end,JSON_UNESCAPED_UNICODE);
		echo json_encode($alone,JSON_UNESCAPED_UNICODE);
		//echo json_encode($other_users,JSON_UNESCAPED_UNICODE);
	}
	else
		echo "0";
?>