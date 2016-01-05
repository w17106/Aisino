<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    $dir = dirname(__FILE__);//查找当前脚本所在路径
	require $dir."/dbconfig.php"; 
    require $dir."/db.php"; //引入MySQL操作类文件
    require $dir."/PHPExcel.php"; //引入PHPExcel
    $db = new db($phpexcel); //实例化DB类，连接数据库
    $objPHPExcel = new PHPExcel(); //实例化PHPExcel类，等同于在桌面上新建Excel
	$objSheet=$objPHPExcel->getActiveSheet(); //获得当前活动单元格
	$objSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置居中
	$objSheet->getDefaultStyle()->getFont()->setName('Arial');//设置缺省字体
    $objSheet->getDefaultStyle()->getFont()->setSize(12); //设置缺省字体大小
	$objSheet->getStyle("A2:Z2")->getFont()->setName('Arial')->setSize(20)->setBold(True);//设置年级字体大小
	$objSheet->getStyle("A3:Z3")->getFont()->setName('Arial')->setSize(16)->setBold(True);//设置班级字体大小
    //$objSheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    //$objSheet->getStyle('B2')->getFill()->getStartColor()->setARGB('FFFF0000');
 

	$gradeinfo = $db->getAllGrade(); //查询所有年级
	$index = 0;
	foreach($gradeinfo as $gk=>$gv){
		$gradeIndex=getCells($index*2);//获取年级信息所在列
		$objSheet->setCellValue($gradeIndex."2",$gv['Grade']);
		$classinfo=$db->getClassByGrade($gv['Grade']);//查询每个年级中的班级
		foreach($classinfo as $ck=>$cv){
			$nameIndex=getCells($index*2);  //获得每个班级学生姓名所在列
			$scoreIndex=getCells($index*2+1);  //获得每个班级学生分数所在列
			$objSheet->mergeCells($nameIndex."3:".$scoreIndex."3");//合并每个班级的单元格
			$objSheet->getStyle($nameIndex."3:".$scoreIndex."3")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('4a86e8');
			$classBorderStyle=getBorderStyle("6aa84f");
		    $objSheet->getStyle($nameIndex."3:".$scoreIndex."3")->applyFromArray($classBorderStyle);
			$info=$db->getDataByClassGrade($cv['Class'],$gv['Grade']);
			$objSheet->setCellValue($nameIndex."3",$cv['Class']); //填充班级信息
			$objSheet->getStyle($nameIndex)->getAlignment()->setWrapText(true); //设置列自动换行
			$objSheet->setCellValue($nameIndex."4","Name\naaa")->setCellValue($scoreIndex."4","Score");
			$objSheet->getStyle($scoreIndex)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

			$j=5;
			foreach($info as $key=>$val){
				//$objSheet->setCellValue($nameIndex.$j,$val['Name'])->setCellValue($scoreIndex.$j,$val['Score']); //填充数据
				//$objSheet->getCell('A1')->setValueExplicit('25', PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objSheet->setCellValue($nameIndex.$j,$val['Name'])->setCellValueExplicit($scoreIndex.$j,$val['Score'],PHPExcel_Cell_DataType::TYPE_STRING); //填充数据
				
				$j++;
			}
			$index ++;
		}
		$endGradeIndex=getCells($index*2-1);  //获得每个年纪的终止单元格
		$objSheet ->mergeCells($gradeIndex."2:".$endGradeIndex."2");//合并每个年级的单元格
	    $objSheet->getStyle($gradeIndex."2:".$endGradeIndex."2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('a4c2f4');
	    //填充年级所在列的颜色
		$gradeBorderStyle=getBorderStyle("6aa84f");
		$objSheet->getStyle($gradeIndex."2:".$endGradeIndex."2")->applyFromArray($gradeBorderStyle); //设置年级边框
	}
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");//生成Excel文件
    $objWriter->save($dir."/export_1.xls");  //保存文件
	//browser_export();
	//$objWriter->save("php://output");
	
	function  browser_export(){
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="simple.xls"');
        header('Cache-Control: max-age=0');
	}
	/**
	**根据下标获得单元格所在列
	**/
	function getCells($index){
		$arr=range("A","Z");
		return $arr[$index];
	}
	/**
	**获取不同的边框样式
	**/
	function getBorderStyle($color){
		$styleArray = array(
	        'borders' => array(
		        'outline' => array(
			        'style' => PHPExcel_Style_Border::BORDER_THICK,
			        'color' => array('rgb' => $color),
		        ),
	        ),
        );
    return $styleArray;

	}
?>	

