<?php
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	$account = $_POST['account'];
	$password = $_POST['password'];
	$result = $link -> query("SELECT * FROM `user_data`");
	while ($row = $result->fetch_assoc())
	{
		if($row['account']==$account and $row['password'] == $password)
		{
			$output[]='1';
		}
		else
			$output[]='0';
	}
	//$output[]=$password;
	print(json_encode($output,JSON_UNESCAPED_UNICODE));
	$link -> close();
?>
