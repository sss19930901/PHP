<?php
	//$datetime = '2019-12-18';
	//$board = ',1,2,';
	$datetime = $_POST['datetime'];
	#$occupied_slot[] = $datetime;
	$board = $_POST['board'];
	$board_arr = explode(',',$board);	//將str轉為array 
	$time = $datetime." 00:00:00";
	#$occupied_slot[] = $time;
	$datetime = date('Y-m-d', strtotime($datetime));
	#$occupied_slot[] = $datetime;
	$now = date('Y-m-d', strtotime("now"));
	$count = 0;
	$day_minutes = 1440;
	$slot_length = 2;
	$slot_num = 0;
	
	#如果選擇的日期是今天，把已經過了的slot num去掉(slot num在迴圈會一直往上加)
	if ($datetime === $now){
		for ( $slot_num=0 ; $slot_num< $day_minutes/$slot_length ; $slot_num++ ) {
			$temp = date('Y-m-d H:i:s', strtotime($time." +".(string)($slot_num * 2)." min"));
			$now = date('Y-m-d H:i:s', strtotime("now"));
			if($temp > $now){
				break;
			}
		}
	}
	#slot_num會被調整到現在的時間，把過去的slot_num記入為被占用
	for ($i = 0; $i < $slot_num; $i++){
		$occupied_slot[] = $i;
		$count++;
	}	
	
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	//printf("%d", $board_arr[3]);
	
	#輪流檢查所選擇的看板
	for ( $i=1 ; $i<count($board_arr)-1 ; $i++ ) {
		$result = $link -> query("SELECT * FROM schedule_".$board_arr[$i]);
		
		#初始化occupied_num 全部為0
		for ($j = 0; $j < $day_minutes/$slot_length; $j++) 
			$occupied_num[$j] = 0;
		
		#讀取schedule每行
		while ($row = $result->fetch_assoc())
		{
			$start = $row['start'];
			$start = date("Y-m-d",strtotime($start));
			#如果這行的日期是跟選擇的日期一樣 而且開始時間在"未來"(現在時間點之後都算，slot_num在前面被調整到現在了)
			if ($start === $datetime && $row['slot_number'] >= $slot_num){
				#如果這個任務不願與人分享看板，直接把它占用的slot記入進occupied_slot
				if ($row['alone'] == 1){
					#從該任務開始的slot_number加j j = 0~duration-1 表示該任務所佔的全部slot_num，全部加進occupied_slot
					for ( $j=0 ; $j<$row['duration'] ; $j++ ){
						$occupied_slot[] = $j + $row['slot_number'];
						$count = $count + 1;
					}
				}
				#如果這任務願意分享，計算對應的occupied_num，如果使用人數加自己以後大於3人，則記入occupied_slot
				else {
					for ( $j=0 ; $j<$row['duration'] ; $j++ ){
						$occupied_num[$j + $row['slot_number']] = $occupied_num[$j + $row['slot_number']] + 1;
						if ($occupied_num[$j + $row['slot_number']] == 3){
							$occupied_slot[] = $j + $row['slot_number'];
							$count = $count + 1;
						}
					}
				}
			}
		}
	}

	if ($count == 0){
		$occupied_slot[] = -1;
		#$occupied_slot[] = -3;
	}
	/*foreach ($occupied_slot as $value){
		echo 'value='.$value.'<br>';
	}*/
	echo json_encode($occupied_slot,JSON_UNESCAPED_UNICODE);
	//print(json_encode($output,JSON_UNESCAPED_UNICODE));
?>
