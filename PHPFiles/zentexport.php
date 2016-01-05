<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    $dir = dirname(__FILE__);//���ҵ�ǰ�ű�����·��
    require $dir."/zentdb.php"; //����MySQL�������ļ�
    require $dir."/PHPExcel.php"; //����PHPExcel
	date_default_timezone_set("Asia/Shanghai");  //����ʱ��
	$currentTime = date('Y-m-d H:i:s', time());  //��ʾ��ǰʱ��
    $db = new db($phpexcel); //ʵ����DB�࣬�������ݿ�
    $objPHPExcel = new PHPExcel(); //ʵ����PHPExcel�࣬��ͬ�����������½�Excel
	
	for($i=1;$i<=4;$i++){
        if($i>1){
        $objPHPExcel->createSheet(); //�����µ����ñ�ȱʡ����������ݣ��ڶ��ű���´��������񣬵����ű������ɵ�����
        }
        $objPHPExcel -> setActiveSheetIndex($i-1);//��Ϊ�Sheet
        $objSheet = $objPHPExcel->getActiveSheet();//��ȡ��ǰ�Sheet
	    $project = $db->getProjectName();   //��ȡ��Ŀ��Ŷ�Ӧ����Ŀ��
	
	    switch($i){
		case 1:
		    $data = $db->getTaskData();  //ȡ��ȫ����������
			$title = "���������б�";
			break;
		case 2:
		    $data = $db->getNewCreatedTask();  //ȡ�ñ����´���������
			$title = "δ�ر���Ҫ�����б�";
			break;
		case 3:
		    $data = $db->getNewFinishedTask(); //ȡ�ñ����½���������
			$title = "���������Ҫ�����б�";
			break;
		case 4:
		    $data = $db->getBugData();  //ȡ��Bug����
			$title = "����BUG�б�";
		}
        $objSheet->setTitle(iconv('gbk', 'utf-8',"$title"));	//Ϊ����������
		
        if($i < 4){			
	        $objSheet->setCellValue("A1",iconv('gbk', 'utf-8',"��Ŀ"))->setCellValue("B1",iconv('gbk', 'utf-8',"����"))->setCellValue("C1",iconv('gbk', 'utf-8',"���ȼ�"))->setCellValue("D1",iconv('gbk', 'utf-8',"����ʱ��"));//�������
	        $objSheet->setCellValue("E1",iconv('gbk', 'utf-8',"ʵ������ʱ��"))->setCellValue("F1",iconv('gbk', 'utf-8',"�������"))->setCellValue("G1",iconv('gbk', 'utf-8',"״̬"))->setCellValue("H1",iconv('gbk', 'utf-8',"����ʱ��"));
	        $objSheet->setCellValue("I1",iconv('gbk', 'utf-8',"ִ����"))->setCellValue("J1",iconv('gbk', 'utf-8',"����ʱ��"))->setCellValue("K1",iconv('gbk', 'utf-8',"����ʱ��"));
            } else{
		    $objSheet->setCellValue("A1",iconv('gbk', 'utf-8',"��Ŀ"))->setCellValue("B1",iconv('gbk', 'utf-8',"BUG����"))->setCellValue("C1",iconv('gbk', 'utf-8',"������"));
			$objSheet->setCellValue("D1",iconv('gbk', 'utf-8',"����"))->setCellValue("E1",iconv('gbk', 'utf-8',"״̬"))->setCellValue("F1",iconv('gbk', 'utf-8',"�����"));
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

	
   
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");//����Excel�ļ�
    $objWriter->save($dir."/export_task.xls");  //�����ļ�
	//browser_export();
	//$objWriter->save("php://output");
	
	function  browser_export(){
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="simple.xls"');
        header('Cache-Control: max-age=0');
	}
	
	
?>	

