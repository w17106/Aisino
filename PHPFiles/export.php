<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    $dir = dirname(__FILE__);//���ҵ�ǰ�ű�����·��
	require $dir."/dbconfig.php"; 
    require $dir."/db.php"; //����MySQL�������ļ�
    require $dir."/PHPExcel.php"; //����PHPExcel
    $db = new db($phpexcel); //ʵ����DB�࣬�������ݿ�
    $objPHPExcel = new PHPExcel(); //ʵ����PHPExcel�࣬��ͬ�����������½�Excel
	
    for($i=1;$i<=3;$i++){
        if($i>1){
        $objPHPExcel->createSheet(); //�����µ����ñ�3����͸���һ�ű�,ȱʡ�Ѿ���һ�ű���
        }
        $objPHPExcel -> setActiveSheetIndex($i-1);//��Ϊ�Sheet
        $objSheet = $objPHPExcel->getActiveSheet();//��ȡ��ǰ�Sheet
        $objSheet->setTitle("grade".$i);
        $data = $db->getDataByGrade($i);//��ѯÿ����͵�ѧ������
        $objSheet->setCellValue("A1","Name")->setCellValue("B1","score")->setCellValue("C1","Class");//�������
        $j = 2;
        foreach($data as $key=>$val){
            $objSheet->setCellValue("A".$j,$val["Name"])->setCellValue("B".$j,$val["Score"])->setCellValue("C".$j,$val["Class"]);
            $j++;
        }
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");//����Excel�ļ�
    //$objWriter->save($dir."/export_1.xls");  //�����ļ�
	browser_export();
	$objWriter->save("php://output");
	
	function  browser_export(){
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="simple.xls"');
        header('Cache-Control: max-age=0');
	}
?>	

