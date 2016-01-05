<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    $dir = dirname(__FILE__);//���ҵ�ǰ�ű�����·��
	require $dir."/dbconfig.php"; 
    require $dir."/db.php"; //����MySQL�������ļ�
    require $dir."/PHPExcel.php"; //����PHPExcel
    $db = new db($phpexcel); //ʵ����DB�࣬�������ݿ�
    $objPHPExcel = new PHPExcel(); //ʵ����PHPExcel�࣬��ͬ�����������½�Excel
	$objSheet=$objPHPExcel->getActiveSheet(); //��õ�ǰ���Ԫ��
	$objSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//���þ���
	$objSheet->getDefaultStyle()->getFont()->setName('Arial');//����ȱʡ����
    $objSheet->getDefaultStyle()->getFont()->setSize(12); //����ȱʡ�����С
	$objSheet->getStyle("A2:Z2")->getFont()->setName('Arial')->setSize(20)->setBold(True);//�����꼶�����С
	$objSheet->getStyle("A3:Z3")->getFont()->setName('Arial')->setSize(16)->setBold(True);//���ð༶�����С
    //$objSheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    //$objSheet->getStyle('B2')->getFill()->getStartColor()->setARGB('FFFF0000');
 

	$gradeinfo = $db->getAllGrade(); //��ѯ�����꼶
	$index = 0;
	foreach($gradeinfo as $gk=>$gv){
		$gradeIndex=getCells($index*2);//��ȡ�꼶��Ϣ������
		$objSheet->setCellValue($gradeIndex."2",$gv['Grade']);
		$classinfo=$db->getClassByGrade($gv['Grade']);//��ѯÿ���꼶�еİ༶
		foreach($classinfo as $ck=>$cv){
			$nameIndex=getCells($index*2);  //���ÿ���༶ѧ������������
			$scoreIndex=getCells($index*2+1);  //���ÿ���༶ѧ������������
			$objSheet->mergeCells($nameIndex."3:".$scoreIndex."3");//�ϲ�ÿ���༶�ĵ�Ԫ��
			$objSheet->getStyle($nameIndex."3:".$scoreIndex."3")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('4a86e8');
			$classBorderStyle=getBorderStyle("6aa84f");
		    $objSheet->getStyle($nameIndex."3:".$scoreIndex."3")->applyFromArray($classBorderStyle);
			$info=$db->getDataByClassGrade($cv['Class'],$gv['Grade']);
			$objSheet->setCellValue($nameIndex."3",$cv['Class']); //���༶��Ϣ
			$objSheet->getStyle($nameIndex)->getAlignment()->setWrapText(true); //�������Զ�����
			$objSheet->setCellValue($nameIndex."4","Name\naaa")->setCellValue($scoreIndex."4","Score");
			$objSheet->getStyle($scoreIndex)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

			$j=5;
			foreach($info as $key=>$val){
				//$objSheet->setCellValue($nameIndex.$j,$val['Name'])->setCellValue($scoreIndex.$j,$val['Score']); //�������
				//$objSheet->getCell('A1')->setValueExplicit('25', PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objSheet->setCellValue($nameIndex.$j,$val['Name'])->setCellValueExplicit($scoreIndex.$j,$val['Score'],PHPExcel_Cell_DataType::TYPE_STRING); //�������
				
				$j++;
			}
			$index ++;
		}
		$endGradeIndex=getCells($index*2-1);  //���ÿ����͵���ֹ��Ԫ��
		$objSheet ->mergeCells($gradeIndex."2:".$endGradeIndex."2");//�ϲ�ÿ���꼶�ĵ�Ԫ��
	    $objSheet->getStyle($gradeIndex."2:".$endGradeIndex."2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('a4c2f4');
	    //����꼶�����е���ɫ
		$gradeBorderStyle=getBorderStyle("6aa84f");
		$objSheet->getStyle($gradeIndex."2:".$endGradeIndex."2")->applyFromArray($gradeBorderStyle); //�����꼶�߿�
	}
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");//����Excel�ļ�
    $objWriter->save($dir."/export_1.xls");  //�����ļ�
	//browser_export();
	//$objWriter->save("php://output");
	
	function  browser_export(){
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="simple.xls"');
        header('Cache-Control: max-age=0');
	}
	/**
	**�����±��õ�Ԫ��������
	**/
	function getCells($index){
		$arr=range("A","Z");
		return $arr[$index];
	}
	/**
	**��ȡ��ͬ�ı߿���ʽ
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

