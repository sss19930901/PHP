<?php
	$board = $_POST['board'];
	$Shared = $_POST['Shared'];
	#$board = ',1,2,';
	$board_arr = explode(',',$board);	//將str轉為array 
	$date = date('Y-m-d', strtotime("now"));
	$date = $date." 00:00:00";
	$day_minutes = 1440;
	$slot_length = 2;
	$slot_num = 0;
	$count = 0;
	if(!strcmp($Shared,"1"))
		$limit = 3;
	else
		$limit = 1;
	#如果選擇的日期是今天，把已經過了的slot num去掉(slot num在迴圈會一直往上加)
	for ( $slot_num=0 ; $slot_num< $day_minutes/$slot_length ; $slot_num++ ) {
		$temp = date('Y-m-d H:i:s', strtotime($date." +".(string)($slot_num * 2)." min"));
		$now = date('Y-m-d H:i:s', strtotime("now"));
		if($temp > $now)
			break;
		else{
			$occupied_slot[] = $slot_num;
			$count++;
		}
	}
	#echo $slot_num;
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	$temp = $slot_num;
	for ( $i=1 ; $i<count($board_arr)-1 ; $i++ ) {
		$slot_num = $temp;
		$result = $link -> query("SELECT * FROM today_".$board_arr[$i]." WHERE slot_number >= ".(string)$slot_num);
		#讀取schedule每行
		while ($row = $result->fetch_assoc())
		{
			if($row['usercount'] >= $limit){
				$occupied_slot[] = $slot_num;
				$count++;
			}
			$slot_num++;
		}
	}

	if ($count == 0)
		$occupied_slot[] = -1;
	echo json_encode($occupied_slot,JSON_UNESCAPED_UNICODE);
?>