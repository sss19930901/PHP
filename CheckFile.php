<?php
	$account = $_POST['account'];
	#$file_id = "";
	#$file_state = "";
	
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	if ($result = $link -> query("SELECT * FROM adv WHERE owner_id = '".$account."'")){
		while($row = $result->fetch_assoc()){
			$file_id[] = $row['id'];
			$file_state[] = $row['censor'];
			$file_size[] = $row['size'];
			$file_upload[] = $row['upload'];
			$file_times[] = $row['times'];
			$file_usually[] = $row['usually'];
		}
	}
	else
		echo "查無此資訊";
	
	echo json_encode($file_id,JSON_UNESCAPED_UNICODE);
	echo json_encode($file_state,JSON_UNESCAPED_UNICODE);
	echo json_encode($file_size,JSON_UNESCAPED_UNICODE);
	echo json_encode($file_times,JSON_UNESCAPED_UNICODE);
	echo json_encode($file_upload,JSON_UNESCAPED_UNICODE);
	echo json_encode($file_usually,JSON_UNESCAPED_UNICODE);
?>