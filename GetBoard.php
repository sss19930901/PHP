<?php
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	$result = $link -> query("SELECT * FROM `ad_board`");
	while ($row = $result->fetch_assoc())
	{
		$output[]=$row['other'];
	}
	print(json_encode($output,JSON_UNESCAPED_UNICODE));
	$link -> close();
?>
