<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    $dir = dirname(__FILE__);//查找当前脚本所在路径
	require $dir."/dbconfig.php"; 
    require $dir."/db.php"; //引入MySQL操作类文件
    require $dir."/PHPExcel.php"; //引入PHPExcel
    $db = new db($phpexcel); //实例化DB类，连接数据库
    $objPHPExcel = new PHPExcel(); //实例化PHPExcel类，等同于在桌面上新建Excel
	
    for($i=1;$i<=3;$i++){
        if($i>1){
        $objPHPExcel->createSheet(); //创建新的内置表，3个年纪各建一张表,缺省已经有一张表了
        }
        $objPHPExcel -> setActiveSheetIndex($i-1);//设为活动Sheet
        $objSheet = $objPHPExcel->getActiveSheet();//获取当前活动Sheet
        $objSheet->setTitle("grade".$i);
        $data = $db->getDataByGrade($i);//查询每个年纪的学生数据
        $objSheet->setCellValue("A1","Name")->setCellValue("B1","score")->setCellValue("C1","Class");//填充数据
        $j = 2;
        foreach($data as $key=>$val){
            $objSheet->setCellValue("A".$j,$val["Name"])->setCellValue("B".$j,$val["Score"])->setCellValue("C".$j,$val["Class"]);
            $j++;
        }
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");//生成Excel文件
    //$objWriter->save($dir."/export_1.xls");  //保存文件
	browser_export();
	$objWriter->save("php://output");
	
	function  browser_export(){
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="simple.xls"');
        header('Cache-Control: max-age=0');
	}
?>	

