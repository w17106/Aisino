#!C:\Strawberry\perl\bin\perl.exe

my $repository = $ARGV[0];
my $start_date = $ARGV[1];
my $end_date = $ARGV[2];
my $svn_path = 'C:\SlikSvn\bin\svn.exe';
my $java_path = 'c:\Program Files (x86)\Java\jre7\bin\java.exe';


`$svn_path checkout http://172.26.2.93:8000/opt/svndata/$repository  C:\\repos\\$repository`;
`$svn_path log -r {$start_date}:{$end_date} -v --xml --username lily  --password Fox102974 c:\\repos\\$repository > c:\\repos\\$repository\\logfile.log`;
`"$java_path" -jar C:\\website\\cgi-bin\\statsvn.jar -charset gb2312 c:\\repos\\$repository\\logfile.log c:\\repos\\$repository`;

my($sec,$min,$hour,$mday,$mon,$year,$wday,$yday)=localtime(time); 
$year+=1900; 
$mon+=1;
my $day="$year$mon$mday"; 
my $partdir="$day_$repo";




#sub check_parameter()
#{
#   if ((@ARGV != 3) or ($ARGV[1] =~ /\D+/) or ($ARGV[2] =~ /\D+/) )    ----如果参数个数不为1或者参数不是数字
#   {
#       my $cmd_name = basename($0);              ----获取当前执行文件的名称        
#       print("USEAGE:\n");
#       print("    $cmd_name  Please enter the date! \n");
#       exit;
#   }    
#}
