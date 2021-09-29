<?php
	$account = $_POST['account'];
	
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	
	#要比對的資料儲存型態如果是字串需要加上' '
	$result = $link -> query("SELECT * FROM user_data WHERE account = '".$account."'");
	$row = $result->fetch_assoc(); 
	if (!is_null($row)){
		$information[] = $row['email'];
		$information[] = $row['sex'];
		$information[] = $row['phonenumber'];
		$information[] = $row['registerday'];
		echo json_encode($information,JSON_UNESCAPED_UNICODE);
	}
	else
		echo "查無此帳號資訊";
?>