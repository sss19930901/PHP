<?php
	$account = 'user001';
	$start_time = date('Y-m-d H:i:s', strtotime("+5 min"));
	$end_time = date('Y-m-d H:i:s', strtotime("+10 min"));
	$board[0] = '_';
	$board[1] = '2';
	$board[2] = '3';
	$board[3] = '_';
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}
	for ( $i=1 ; $i<count($board)-1 ; $i++ ) {
		$sql = "INSERT INTO schedule_".$board[$i]." (start, end, duration, ad_id, owner_id) VALUES('$start_time', '$end_time', 5, 1, '$account')";
	
		if ($link->query($sql) === TRUE) {
			echo "插入成功";
		}
		else {
			echo "Error: " . $sql . "<br>" . $link->error;
		}
	}
	
	if ($result = mysqli_query($link, "SELECT id FROM adv")) {
		/* determine number of rows result set */
		$row_cnt = mysqli_num_rows($result);
		printf("Result set has %d rows.\n", $row_cnt);
		/* close result set */
		mysqli_free_result($result);
	}
	$row_cnt = $row_cnt + 1;
	$sql = "INSERT INTO adv (id, owner_id) VALUES('$row_cnt', '$account')";
	$link->query($sql);

	$row_cnt = sprintf("%03d", $row_cnt);
	$row_cnt = (string)$row_cnt;
	printf("Result set has %s rows.\n", $row_cnt);
	$link->close();
?>
