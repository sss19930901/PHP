<?php
	$lat = $_POST['lat'];
	#$lat = "22.996845";
	$lng = $_POST['lng'];
	#$lng = "120.222487";
	$board = ",";
	
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	$str = "SELECT * FROM ad_board WHERE latitude = '".$lat."'"."and longitude = '".$lng."'";
	if ($result = $link -> query($str)){
		while($row = $result->fetch_assoc()){
			$board = $board.$row['id'].",";
		}
		echo $board;
	}
	else
		echo "查無此資訊";
?>