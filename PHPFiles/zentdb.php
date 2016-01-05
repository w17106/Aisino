<?php
    $dir = dirname(__FILE__); //Get current directory
    require $dir."/zentdbconfig.php"; //引入配置文件

    class db{
        public $conn=null;

        public function __construct($config){  //构造方法，实例化类时自动调用
            $this->conn = mysql_connect($config["host"],$config["username"],$config["password"]) or die(mysql_error()); //链接数据库
            mysql_select_db($config["database"],$this->conn) or die(mysql_error());//选择数据库
			mysql_query("set names 'utf8'")or die(mysql_error());//设定字符集
        }
		/**
		**根据传入sql语句，查询MYSQL结果集
		**/
		public function getResult($sql){
			$resource=mysql_query($sql,$this->conn) or die(mysql_error());//查询sql语句
			$res = array();
			while(($row=mysql_fetch_assoc($resource))!=false){
				$res[]=$row;
			}
			
			return $res;
		}
		/**
		**查询有效项目中的所有任务信息
		**/
		public function getTaskData(){
			$sql ="select project,name,pri,estimate,consumed,deadline,status,openedDate,assignedTo,assignedDate,finishedDate from zt_task where project>'4' order by project";
			$res=self::getResult($sql);
			
			return $res;
		
		}
		/**
		**提取项目编号对应的项目
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
		**查询所有未关闭的Task
		**/
		public  function getNewCreatedTask(){
			$sql = "Select project,name,pri,estimate,consumed,deadline,status,openedDate,assignedTo,assignedDate,finishedDate from zt_task where status!='done' and status!='closed' and pri='1'";
			$res=$this->getResult($sql);
			return $res;		
		}
		/**
		**查询最近一周完成的Task
		**/
		public function getNewFinishedTask(){
		    $sql = "Select project,name,pri,estimate,consumed,deadline,status,openedDate,assignedTo,assignedDate,finishedDate from zt_task where TO_DAYS(NOW()) - TO_DAYS(finishedDate) <= 7 and pri='1'";
            $res=$this->getResult($sql);
			
			return $res;			
		}
		/**
		**查询有效项目中的所有BUG信息
		**/
		public function getBugData(){
			$sql ="select project,title,severity,type,status,resolvedBy from zt_bug where project>'4' order by project";
			$res=self::getResult($sql);
			
			return $res;
		}
    }
?>