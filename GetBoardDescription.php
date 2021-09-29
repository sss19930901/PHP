<?php
	$board = $_POST['board'];
	#$board = ",1,2,3,";
	$board_arr = explode(',',$board);	
	$other = ",";
	
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	for ( $i=1 ; $i<count($board_arr)-1 ; $i++ ) {
		if ($result = $link -> query("SELECT * FROM ad_board WHERE id = '".$board_arr[$i]."'")){
			$row = $result->fetch_assoc();
			$other = $other.$row['other'].",";
		}
		else
			echo "查無此資訊";
	}
	echo $other;
?>