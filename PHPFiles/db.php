<?php
    $dir = dirname(__FILE__); //Get current directory
    require $dir."/dbconfig.php"; //引入配置文件

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
			$res=array();
			while(($row=mysql_fetch_assoc($resource))!=false){
				$res[]=$row;
			}
			return $res;
		}
		/**
		**根据传入年级数，查询每个年级的学生数据
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
		**查询所有的年级
		**/
		public function  getAllGrade(){
			$sql = "Select distinct(Grade) from users order by grade";
			$res=$this->getResult($sql);
			return $res;
		}
		/**
		**根据年级数查询所有班级
		**/
		public  function getClassByGrade($grade){
			$sql = "Select distinct(Class) from users where grade=".$grade." order by class";
			$res=$this->getResult($sql);
			return $res;		
		}
		/**
		**根据年级数班级数查询所有学生
		**/
		public function getDataByClassGrade($class,$grade){
		    $sql="select Name,Score from users where class=".$class." and grade=".$grade." order by score";
            $res=$this->getResult($sql);
			return $res;			
		}
    }
?>