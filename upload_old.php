<?php 
	$account = $_POST['account'];
	$upload_time = $_POST['upload_time'];
	$board = $_POST['board'];
	$board_arr = explode(',',$board);
	
	$dirPath = './img/board/' ;//設置文件保存的目錄
	$occupied_slot[] = $upload_time;
	$time = substr($upload_time,0,10); #本來是YYYY-MM-DD 0~10 月分和日期要補到2位數
	$time = $time." 00:00:00";
	#echo ($time);
	$start_time = date('Y-m-d H:i:s', strtotime($upload_time));
	$end_time = date('Y-m-d H:i:s', strtotime($upload_time." +2 min"));
	$occupied_slot[] = $time;
	$day_minutes = 1440;
	$slot_length = 2;
	$slot_num = 0;
	
	for ( $slot_num=0 ; $slot_num <= $day_minutes/$slot_length ; $slot_num++ ) {
		#time為一天的0點0分，慢慢加slot長度直到超過上傳選擇的時間
		$temp = date('Y-m-d H:i:s', strtotime($time." +".(string)($slot_num * 2)." min"));
		#找到start time屬於的slot number
		if($temp > $start_time){
			$slot_num = $slot_num - 1;
			break;
		}
	}
		
	//===========↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓=============== 修改資料庫
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}

	if ($result = mysqli_query($link, "SELECT id FROM adv")) {
		/* determine number of rows result set */
		$row_cnt = mysqli_num_rows($result);
		//printf("Result set has %d rows.\n", $row_cnt);
		/* close result set */
		mysqli_free_result($result);
	}
	$row_cnt = $row_cnt + 1;
	$sql = "INSERT INTO adv (id, owner_id) VALUES('$row_cnt', '$account')";
	$link->query($sql);
	
	for ( $i=1 ; $i<count($board_arr)-1 ; $i++ ) {
		$sql = "INSERT INTO schedule_".$board_arr[$i]." (start, end, slot_number,duration, ad_id, owner_id) VALUES('$start_time', '$end_time', '$slot_num', 1, '$row_cnt', '$account')";
		$modify = "UPDATE ad_board SET modify_bit = '1' WHERE ad_board.id = ".$board_arr[$i].";";
		if ($link->query($sql) === TRUE && $link->query($modify) === TRUE) {
			#echo "插入且修改成功";
		}
		else {
			#echo "Error: " . $sql . "<br>" . $link->error;
		}
	}
	
	$link->close();
	//================↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑============= 修改資料庫
	
	if(!is_dir($dirPath)){
	  //目錄不存在則創建目錄
	  @mkdir($dirPath);
	}

	$count = count($_FILES);//所有文件數

	if($count<1) die('{"status":0,"msg":"錯誤提交"}');//沒有提交的文件

	$success = $failure = 0;
	$row_cnt = sprintf("%03d", $row_cnt);
	$row_cnt = (string)$row_cnt;
	
	foreach($_FILES as $key => $value){
	  //循環遍歷數據
	  $tmp = $row_cnt.'.png';//獲取上傳文件名
	  $tmpName = $value['tmp_name'];//臨時文件路徑
	  //上傳的文件會被保存到php臨時目錄，調用函數將文件復制到指定目錄
	  if(move_uploaded_file($tmpName,$dirPath.$tmp)){
		//rename($dirPath.$tmp,"./img/board/003.png/")  
		$success++;
	  }else{
		$failure++;
	  }
	}
	
	$arr['status'] = 1;
	$arr['msg']   = '提交成功';
	$arr['success'] = $success;
	$arr['failure'] = $failure;
	
	echo json_encode($occupied_slot);

?>

