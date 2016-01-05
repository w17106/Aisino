<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    $dir = dirname(__FILE__);//查找当前脚本所在路径
    require $dir."/zentdb.php"; //引入MySQL操作类文件
    require $dir."/PHPExcel.php"; //引入PHPExcel
	date_default_timezone_set("Asia/Shanghai");  //设置时区
	$currentTime = date('Y-m-d H:i:s', time());  //显示当前时间
    $db = new db($phpexcel); //实例化DB类，连接数据库
    $objPHPExcel = new PHPExcel(); //实例化PHPExcel类，等同于在桌面上新建Excel
	
	for($i=1;$i<=4;$i++){
        if($i>1){
        $objPHPExcel->createSheet(); //创建新的内置表，缺省表放所有数据，第二张表放新创建的任务，第三张表放新完成的任务
        }
        $objPHPExcel -> setActiveSheetIndex($i-1);//设为活动Sheet
        $objSheet = $objPHPExcel->getActiveSheet();//获取当前活动Sheet
	    $project = $db->getProjectName();   //获取项目编号对应的项目名
	
	    switch($i){
		case 1:
		    $data = $db->getTaskData();  //取得全部任务数据
			$title = "所有任务列表";
			break;
		case 2:
		    $data = $db->getNewCreatedTask();  //取得本周新创建的任务
			$title = "未关闭重要任务列表";
			break;
		case 3:
		    $data = $db->getNewFinishedTask(); //取得本周新结束的任务
			$title = "本周完成重要任务列表";
			break;
		case 4:
		    $data = $db->getBugData();  //取得Bug数据
			$title = "所有BUG列表";
		}
        $objSheet->setTitle(iconv('gbk', 'utf-8',"$title"));	//为表设置名字
		
        if($i < 4){			
	        $objSheet->setCellValue("A1",iconv('gbk', 'utf-8',"项目"))->setCellValue("B1",iconv('gbk', 'utf-8',"任务"))->setCellValue("C1",iconv('gbk', 'utf-8',"优先级"))->setCellValue("D1",iconv('gbk', 'utf-8',"估计时间"));//填充数据
	        $objSheet->setCellValue("E1",iconv('gbk', 'utf-8',"实际消耗时间"))->setCellValue("F1",iconv('gbk', 'utf-8',"最后期限"))->setCellValue("G1",iconv('gbk', 'utf-8',"状态"))->setCellValue("H1",iconv('gbk', 'utf-8',"创建时间"));
	        $objSheet->setCellValue("I1",iconv('gbk', 'utf-8',"执行人"))->setCellValue("J1",iconv('gbk', 'utf-8',"分配时间"))->setCellValue("K1",iconv('gbk', 'utf-8',"结束时间"));
            } else{
		    $objSheet->setCellValue("A1",iconv('gbk', 'utf-8',"项目"))->setCellValue("B1",iconv('gbk', 'utf-8',"BUG描述"))->setCellValue("C1",iconv('gbk', 'utf-8',"严重性"));
			$objSheet->setCellValue("D1",iconv('gbk', 'utf-8',"类型"))->setCellValue("E1",iconv('gbk', 'utf-8',"状态"))->setCellValue("F1",iconv('gbk', 'utf-8',"解决人"));
			} 
		$j = 2;
        foreach($data as $key=>$val){
	        $p = $val["project"];
			
			if($i < 4){
                $objSheet->setCellValue("A".$j,$project[$p])->setCellValue("B".$j,$val["name"])->setCellValue("C".$j,$val["pri"])->setCellValue("D".$j,$val["estimate"])->setCellValue("E".$j,$val["consumed"])->setCellValue("F".$j,$val["deadline"]);
                $objSheet->setCellValue("G".$j,$val["status"])->setCellValue("H".$j,$val["openedDate"])->setCellValue("I".$j,$val["assignedTo"])->setCellValue("J".$j,$val["assignedDate"])->setCellValue("K".$j,$val["finishedDate"]);
		        } else {
				$objSheet->setCellValue("A".$j,$project[$p])->setCellValue("B".$j,$val["title"])->setCellValue("C".$j,$val["severity"]);
				$objSheet->setCellValue("D".$j,$val["type"])->setCellValue("E".$j,$val["status"])->setCellValue("F".$j,$val["resolvedBy"]);
				}
			$j++;
        }
	}

	
   
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");//生成Excel文件
    $objWriter->save($dir."/export_task.xls");  //保存文件
	//browser_export();
	//$objWriter->save("php://output");
	
	function  browser_export(){
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="simple.xls"');
        header('Cache-Control: max-age=0');
	}
	
	
?>	

