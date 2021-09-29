<?php 
	$account = $_POST['account'];
	$dirPath = './img/board/' ;//設置文件保存的目錄
	$today= date("Y-m-d");
	$link = mysqli_connect("localhost","root","root","advertisement");
	$link -> set_charset("UTF8");
	
	if ($link->connect_error) {
		die("連接失敗: " . $conn->connect_error);
	}

	if(!is_dir($dirPath)){
	  //目錄不存在則創建目錄
	  @mkdir($dirPath);
	}

	$count = count($_FILES);//所有文件數
	if($count<1) die('{"status":0,"msg":"錯誤提交"}');//沒有提交的文件

	$success = $failure = 0;
	#$row_cnt = sprintf("%03d", $row_cnt);
	#$row_cnt = (string)$row_cnt;
	
	foreach($_FILES as $key => $value){
		//循環遍歷數據
		if ($result = mysqli_query($link, "SELECT * FROM `adv` ORDER BY id DESC LIMIT 1")) {
			$row = $result->fetch_assoc();
			$id = (int)$row['id'] + 1;
			mysqli_free_result($result);
		}
		$id = sprintf("%03d", $id);
		$id = (string)$id;
		$tmp = $id.'.png';//獲取上傳文件名
		$tmpName = $value['tmp_name'];//臨時文件路徑
		$tmpSize = $value['size'];
		$tmpSize = round($tmpSize / 1000000, 2);
		$tmpSize = (string)$tmpSize."MB";
		echo $tmpSize;
		//上傳的文件會被保存到php臨時目錄，調用函數將文件復制到指定目錄
			if(move_uploaded_file($tmpName,$dirPath.$tmp)){
				$success++;
				//===========↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓=============== 修改資料庫
				$sql = "INSERT INTO adv (id, owner_id, size, censor, upload, times, usually) VALUES('$id', '$account', '$tmpSize', 0, '$today', 0, 0)";
				$link->query($sql);
				//================↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑============= 修改資料庫
			}
			else{
				$failure++;
			}
	}
	$link->close();
	$arr['status'] = 1;
	$arr['msg']   = '提交成功';
	$arr['success'] = $success;
	$arr['failure'] = $failure;
	
	echo json_encode($arr);

?>

