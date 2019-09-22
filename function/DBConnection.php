<?php
	class connectDataBase{
		// public $ip = "";
		public $link = "";
		function __construct($db_name){
            // 配置数据库链接参数：地址、用户名、密码、数据库名
            include "config/dbconfig/maindb.php";
			$timezone="Asia/Shanghai";
			if ($link = mysqli_connect($host,$user,$pass)) {
				mysqli_select_db($link,$db_name);
				mysqli_query($link,"set names 'utf8mb4'");
				// $ip = getIP();
				$this->link = $link;
				// echo "数据库连接成功".$this->link."\n";
			} else {
				echo "数据库连接失败！";
				exit;
			}
		}
    }
?>