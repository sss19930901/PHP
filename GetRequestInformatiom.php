<?php
	/*$file_ID = array("1","4");
	$start = array("2020-02-25 17:48:00.000000");
	$end = array("2020-02-25 17:50:00.000000");
	$alone = array("1");
	$lat = array("22.996845","22.994492");
	$lng = array("120.222487","120.220805");
	$boardDescription = array("DCL_01_01","測試用1號 ");	
	$slotnum = array("534");
	$durattion = array("2");
	$board_ID = array("1");
	
	echo json_encode($file_ID,JSON_UNESCAPED_UNICODE);
	echo json_encode($start,JSON_UNESCAPED_UNICODE);
	echo json_encode($end,JSON_UNESCAPED_UNICODE);
	echo json_encode($alone,JSON_UNESCAPED_UNICODE);
	echo json_encode($lat,JSON_UNESCAPED_UNICODE);
	echo json_encode($lng,JSON_UNESCAPED_UNICODE);
	echo json_encode($boardDescription,JSON_UNESCAPED_UNICODE);
	echo json_encode($slotnum,JSON_UNESCAPED_UNICODE);
	echo json_encode($durattion,JSON_UNESCAPED_UNICODE);
	echo json_encode($board_ID,JSON_UNESCAPED_UNICODE);*/
	$RequestID = $_POST['RequestID'];
		
	$count = 0;
	$count1 = 0;
	
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	
	#要比對的資料儲存型態如果是字串需要加上' '
	$result = $link -> query("SELECT * FROM schedule_1 WHERE quest_id = '".$RequestID."'");
	while($row = $result->fetch_assoc()){
		for($i = 1;$i < 4;$i++)
			if (!is_null($row["ad_id".(string)$i]))
				$file_ID[] = $row["ad_id".(string)$i];
		$start[] = $row['start'];
		$end[] = $row['end'];	
		$alone[] = $row['alone'];	
		$slotnum[] = $row['slot_number'];
		$durattion[] = $row['duration'];
		$board_ID[] = $row['board_id'];
		$count = $count + 1;		
	}
	
	$board_ID_arr = explode('-',$board_ID[0]);
	#
	for ( $i = 0 ; $i < count($board_ID_arr); $i++){
		$result = $link -> query("SELECT * FROM ad_board WHERE id = '".$board_ID_arr[$i]."'");
		while($row = $result->fetch_assoc()){
			$lat[] = $row['latitude'];
			$lng[] = $row['longitude'];
			$boardDescription[] = $row['other'];
			$count1 = $count1 + 1;
		}
	}

	if($count > 0 and $count1 > 0){
		echo json_encode($file_ID,JSON_UNESCAPED_UNICODE);
		echo json_encode($start,JSON_UNESCAPED_UNICODE);
		echo json_encode($end,JSON_UNESCAPED_UNICODE);
		echo json_encode($alone,JSON_UNESCAPED_UNICODE);
		echo json_encode($lat,JSON_UNESCAPED_UNICODE);
		echo json_encode($lng,JSON_UNESCAPED_UNICODE);
		echo json_encode($boardDescription,JSON_UNESCAPED_UNICODE);
		echo json_encode($slotnum,JSON_UNESCAPED_UNICODE);
		echo json_encode($durattion,JSON_UNESCAPED_UNICODE);
		echo json_encode($board_ID_arr,JSON_UNESCAPED_UNICODE);
	}
	else
		echo "0";
?>