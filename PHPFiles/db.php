<?php
    $dir = dirname(__FILE__); //Get current directory
    require $dir."/dbconfig.php"; //���������ļ�

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
			$res=array();
			while(($row=mysql_fetch_assoc($resource))!=false){
				$res[]=$row;
			}
			return $res;
		}
		/**
		**���ݴ����꼶������ѯÿ���꼶��ѧ������
		**/
		public function getDataByGrade($grade){
			$sql ="select Name,Score,Class from users where Grade=".$grade." order by Score";
			$res=self::getResult($sql);
			foreach($res as $k=>$v){
				echo "$v[Name],$v[Score],$v[Class]";
				echo "<br />";
			}
			return $res;
		}
		/**
		**��ѯ���е��꼶
		**/
		public function  getAllGrade(){
			$sql = "Select distinct(Grade) from users order by grade";
			$res=$this->getResult($sql);
			return $res;
		}
		/**
		**�����꼶����ѯ���а༶
		**/
		public  function getClassByGrade($grade){
			$sql = "Select distinct(Class) from users where grade=".$grade." order by class";
			$res=$this->getResult($sql);
			return $res;		
		}
		/**
		**�����꼶���༶����ѯ����ѧ��
		**/
		public function getDataByClassGrade($class,$grade){
		    $sql="select Name,Score from users where class=".$class." and grade=".$grade." order by score";
            $res=$this->getResult($sql);
			return $res;			
		}
    }
?>