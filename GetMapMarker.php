<?php
	$lat = array();
	$lng = array();
	$locationDes = array();
	
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	
	if ($result = $link -> query("SELECT * FROM ad_board")){
		$row = $result->fetch_assoc();
		$lat[] = $row['latitude'];
		$lng[] = $row['longitude'];
		$locationDes[] = $row['location_description'];
		while($row = $result->fetch_assoc()){
			for ($i = 0;$i < count($lat);$i++)
				if (strcmp($row['latitude'],$lat[$i]) || strcmp($row['longitude'],$lng[$i])){
					$lat[] = $row['latitude'];
					$lng[] = $row['longitude'];
					$locationDes[] = $row['location_description'];
				}
		}
		echo json_encode($lat,JSON_UNESCAPED_UNICODE);
		echo json_encode($lng,JSON_UNESCAPED_UNICODE);
		echo json_encode($locationDes,JSON_UNESCAPED_UNICODE);
	}
	else
		echo "查無此資訊";
?>