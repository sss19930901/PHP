<?php 
	#$account = "user001";
	$account = $_POST['account'];
	#$publish_date = $_POST['publish_date'];
	
	#選擇的看板
	#$board = ",1,";
	$board = $_POST['board'];
	$board_arr = explode(',',$board);
		
	#選擇的每個時段的開始時間
	#$publish_slot = ",719,";
	$publish_slot = $_POST['publish_slot'];
	$publish_slot_arr = explode(',',$publish_slot);
	
	#時段的長度
	#$duration = $_POST['duration'];
	#$duration_arr = explode(',',$$duration);	
	
	#檔案ID
	#$file_id = ",3,4,5,";
	$file_id = $_POST['file_id'];
	$file_arr = explode(',',$file_id);
	$filecount = count($file_arr) - 2;
	
	#1~3個檔案各自的播放時間
	$play_time = ",60,60,";
	#$play_time = $_POST['playtime'];
	$play_time_arr = explode(',',$play_time);
	
	$Shared = $_POST['Shared'];

	$day_minutes = 1440;
	$slot_length = 2;
	$start_slot = $publish_slot_arr[1];
	$duration = 1;	
	$date = date('Y-m-d', strtotime("now"));
	$date = $date." 00:00:00";
	$tempboard = "";
	//===========↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓=============== 修改資料庫
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	
	for ( $i = 1 ; $i < count($board_arr) - 1 ; $i++ ) {
		for($j = 1 ; $j < count($publish_slot_arr) - 1 ; $j++ ){
			$select = "SELECT * FROM today_".$board_arr[$i]." WHERE slot_number = ".$publish_slot_arr[$j];
			$result = $link->query($select);
			$row = $result->fetch_assoc();
			if ($row['usercount'] == 0){
				$usercount = "1";
				$userid = "user1";
				$file = array("ad_id1","ad_id2","ad_id3");
				$time = array("playtime1","playtime2","playtime3");
			}	
			else if ($row['usercount'] == 1){
				$usercount = "2";
				$userid = "user2";
				$file = array("ad_id4","ad_id5","ad_id6");
				$time = array("playtime4","playtime5","playtime6");
			}			
			else if ($row['usercount'] == 2){
				$usercount = "3";
				$userid = "user3";
				$file = array("ad_id7","ad_id8","ad_id9");
				$time = array("playtime7","playtime8","playtime9");
			}
			if(!strcmp($Shared,"0"))
				$usercount = "3";
			$temp = $filecount + $row['filecount'];
			
			$sql = "UPDATE today_".$board_arr[$i]." SET usercount = ".$usercount.", filecount = ".$temp.",".$userid." = '".$account."'";
			for ( $k = 0 ; $k < count($file_arr) - 2 ; $k++ )
				$sql = $sql.",".$file[$k]." = ".$file_arr[$k+1].",".$time[$k]." = ".(string)(40 / $filecount);
				#$sql = $sql.",".$file[$k]." = ".$file_arr[$k+1].",".$time[$k]." = ".$play_time_arr[$k+1];
			$sql = $sql." WHERE slot_number = ".$publish_slot_arr[$j].";";

			if ($link->query($sql) === TRUE) {
				echo "插入shedule成功";
			}
			else {
				echo "Error: " . $sql . "<br>" . $link->error;
			}
		}
		$modify = "UPDATE ad_board SET modify_bit = 1 WHERE id = ".$board_arr[$i].";";
		
		$select = "SELECT * FROM user_data WHERE account = '".$account."'";
		$result = $link->query($select);
		$row = $result->fetch_assoc();
		
		$history1 = "UPDATE user_data SET history1 = '".$board_arr[$i]."' WHERE account = '".$account."'";
		$history2 = "UPDATE user_data SET history2 = '".$row['history1']."' WHERE account = '".$account."'";
		$history3 = "UPDATE user_data SET history3 = '".$row['history2']."' WHERE account = '".$account."'";
		if ($link->query($history3) === TRUE && $link->query($history2) === TRUE && $link->query($history1) === TRUE)
			echo "修改歷史看板成功<br>";
		else 
			echo "Error: " . $sql . "<br>" . $link->error;
		
		if ($link->query($modify) === TRUE)
			echo "修改modify_bit成功<br>";
		else 
			echo "Error: " . $sql . "<br>" . $link->error;
	}
	
	$start = date('Y-m-d H:i:s', strtotime($date." +".(string)((int)$publish_slot_arr[1] * 2)." min"));
	$startslot = $publish_slot_arr[1];
	for ( $i = 1 ; $i < count($board_arr) - 1 ; $i++)
					if($i == count($board_arr) - 2)
						$tempboard = $tempboard.$board_arr[$i];
					else
						$tempboard = $tempboard.$board_arr[$i]."-";
	if(!strcmp($Shared,"0"))
		$alone = "1";
	else
		$alone = "0";
	$file = array("ad_id1","ad_id2","ad_id3");
	$time = array("playtime1","playtime2","playtime3");
	for($j = 1 ; $j < count($publish_slot_arr) - 1 ; $j++ ){
		if($j < count($publish_slot_arr) - 2){
			if((int)$publish_slot_arr[$j] + 1 != (int)$publish_slot_arr[$j + 1]){
				if ($result = mysqli_query($link, "SELECT * FROM `schedule_1` ORDER BY quest_id DESC LIMIT 1")) {
					$row = $result->fetch_assoc();
					$quest_id = (int)$row['quest_id'] + 1;
					mysqli_free_result($result);
				}
				$end = date('Y-m-d H:i:s', strtotime($date." +".(string)(((int)$publish_slot_arr[$j] + 1) * 2)." min"));
				$sql = "INSERT INTO schedule_1 (quest_id, owner_id, board_id, start, end, slot_number, duration";
				for($k = 0 ; $k < count($file_arr) - 2 ; $k++)
					$sql = $sql.", ".$file[$k].", ".$time[$k];
				$sql = $sql.", alone)"; 
				$sql = $sql." VALUES('$quest_id', '$account', '$tempboard', '$start', '$end',  '$startslot', '$duration'";
				for ( $k = 0 ; $k < count($file_arr) - 2 ; $k++ )
					$sql = $sql.", '".$file_arr[$k+1]."', '".(string)(40 / $filecount)."'";
				$sql = $sql.", '".$alone."')";
				echo $sql;
		
				$start = date('Y-m-d H:i:s', strtotime($date." +".(string)((int)$publish_slot_arr[$j + 1] * 2)." min"));
				$startslot = $publish_slot_arr[$j + 1];
				$duration = 1;
			}
			else{
				$duration = $duration + 1;
			}
			if ($link->query($sql) === TRUE)
				echo "修改schdule成功<br>";
			else 
				echo "Error: " . $sql . "<br>" . $link->error;
		}
		else{
			if ($result = mysqli_query($link, "SELECT * FROM `schedule_1` ORDER BY quest_id DESC LIMIT 1")) {
					$row = $result->fetch_assoc();
					$quest_id = (int)$row['quest_id'] + 1;
					mysqli_free_result($result);
				}
			$end = date('Y-m-d H:i:s', strtotime($date." +".(string)(((int)$publish_slot_arr[$j] + 1) * 2)." min"));
			$sql = "INSERT INTO schedule_1 (quest_id, owner_id, board_id, start, end, slot_number, duration";
			for($k = 0 ; $k < count($file_arr) - 2 ; $k++)
				$sql = $sql.", ".$file[$k].", ".$time[$k];
			$sql = $sql.", alone) ";
			$sql = $sql."VALUES('$quest_id', '$account', '$tempboard', '$start', '$end',  '$startslot', '$duration'";
			for ( $k = 0 ; $k < count($file_arr) - 2 ; $k++ )
				$sql = $sql.", '".$file_arr[$k+1]."', '".(string)(40 / $filecount)."'";
			$sql = $sql.", '".$alone."')";
			echo $sql;
			if ($link->query($sql) === TRUE)
				echo "修改schdule成功<br>";
			else 
				echo "Error: " . $sql . "<br>" . $link->error;
		}
	}
	$link->close();
	//================↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑============= 修改資料庫
?>

