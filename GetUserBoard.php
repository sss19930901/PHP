<?php
	$account = $_POST['account'];
	$input = $_POST['inputdata'];	
	#$account = "user001";
	#$input = "usually";
	
	$count = 0;
	
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	
	#要比對的資料儲存型態如果是字串需要加上' '
	$result = $link -> query("SELECT * FROM user_data WHERE account = '".$account."'");
	$row = $result->fetch_assoc(); 
	if (!is_null($row)){
		if ($input == "usually"){
			for($i = 1;$i < 4;$i++)
				if (!is_null($row["usual".(string)$i])){
					$board[] = $row["usual".(string)$i];
					$count++;
				}
		}		
		else if ($input == "history"){
			for($i = 1;$i < 4;$i++)
				if (!is_null($row["history".(string)$i])){
					$board[] = $row["history".(string)$i];
					$count++;
				}
		}
		if($count)
			echo json_encode($board,JSON_UNESCAPED_UNICODE);
		else
			echo "0";
	}
	else
		echo "查無此帳號資訊";
?>