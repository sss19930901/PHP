<?php
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	for($i = 1; $i < 4; $i++)
	{
		$sql = "UPDATE today_".$i." SET usercount = 0, filecount = 0,
		user1 = NULL, ad_id1 = NULL, playtime1 = NULL, ad_id2 = NULL, playtime2 = NULL, ad_id3 = NULL, playtime3 = NULL,
		user2 = NULL, ad_id4 = NULL, playtime4 = NULL, ad_id5 = NULL, playtime5 = NULL, ad_id6 = NULL, playtime6 = NULL,
		user3 = NULL, ad_id7 = NULL, playtime7 = NULL, ad_id8 = NULL, playtime8 = NULL, ad_id9 = NULL, playtime9 = NULL";
		if ($link->query($sql) === TRUE){
			echo "插入且修改成功";
		}
		else {
			echo "Error: " . $sql . "<br>" . $link->error;
		}
	}
?>