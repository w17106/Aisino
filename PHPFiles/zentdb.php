<?php
    $dir = dirname(__FILE__); //Get current directory
    require $dir."/zentdbconfig.php"; //���������ļ�

    class db{
        public $conn=null;

        public function __construct($config){  //���췽����ʵ������ʱ�Զ�����
            $this->conn = mysql_connect($config["host"],$config["username"],$config["password"]) or die(mysql_error()); //�������ݿ�
            mysql_select_db($config["database"],$this->conn) or die(mysql_error());//ѡ�����ݿ�
			mysql_query("set names 'utf8'")or die(mysql_error());//�趨�ַ���
        }
		/**
		**���ݴ���sql��䣬��ѯMYSQL�����
		**/
		public function getResult($sql){
			$resource=mysql_query($sql,$this->conn) or die(mysql_error());//��ѯsql���
			$res = array();
			while(($row=mysql_fetch_assoc($resource))!=false){
				$res[]=$row;
			}
			
			return $res;
		}
		/**
		**��ѯ��Ч��Ŀ�е�����������Ϣ
		**/
		public function getTaskData(){
			$sql ="select project,name,pri,estimate,consumed,deadline,status,openedDate,assignedTo,assignedDate,finishedDate from zt_task where project>'4' order by project";
			$res=self::getResult($sql);
			
			return $res;
		
		}
		/**
		**��ȡ��Ŀ��Ŷ�Ӧ����Ŀ
		**/
		public function  getProjectName(){
			$sql = "Select id,name from zt_project";
			$res=$this->getResult($sql);
			$proj=array();
			foreach($res as $key=>$value){
               $proj[$value["id"]] =$value["name"];
            }
			return $proj;
		}
		/**
		**��ѯ����δ�رյ�Task
		**/
		public  function getNewCreatedTask(){
			$sql = "Select project,name,pri,estimate,consumed,deadline,status,openedDate,assignedTo,assignedDate,finishedDate from zt_task where status!='done' and status!='closed' and pri='1'";
			$res=$this->getResult($sql);
			return $res;		
		}
		/**
		**��ѯ���һ����ɵ�Task
		**/
		public function getNewFinishedTask(){
		    $sql = "Select project,name,pri,estimate,consumed,deadline,status,openedDate,assignedTo,assignedDate,finishedDate from zt_task where TO_DAYS(NOW()) - TO_DAYS(finishedDate) <= 7 and pri='1'";
            $res=$this->getResult($sql);
			
			return $res;			
		}
		/**
		**��ѯ��Ч��Ŀ�е�����BUG��Ϣ
		**/
		public function getBugData(){
			$sql ="select project,title,severity,type,status,resolvedBy from zt_bug where project>'4' order by project";
			$res=self::getResult($sql);
			
			return $res;
		}
    }
?>