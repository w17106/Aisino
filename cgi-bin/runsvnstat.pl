#!C:\Strawberry\perl\bin\perl.exe

my $repo = $ARGV[0];
my $start = $ARGV[1];
my $end = $ARGV[2];
my $svn_path = 'C:\SlikSvn\bin\svn.exe';
my $java_path = 'c:\Program Files (x86)\Java\jre7\bin\java.exe';


if ( -e "c:\\repos\\$repo")
{
    `$svn_path  update C:\\repos\\$repo`;
}
else
{
    `$svn_path checkout http://172.26.2.93:8000/opt/svndata/$repo  C:\\repos\\$repo 2>&1`;
}

`$svn_path log -r {$start}:{$end} -v --xml --username lily  --password Fox102974 c:\\repos\\$repo > c:\\repos\\$repo\\logfile.log`;

my $i=1;
while ( -e "c:\\website\\htdocs\\$i")
{
    $i+=1;
}

mkdir "c:\\website\\htdocs\\$i";
chdir "c:\\website\\htdocs\\$i";

`"$java_path" -jar C:\\website\\cgi-bin\\statsvn.jar -charset gb2312 c:\\repos\\$repo\\logfile.log c:\\repos\\$repo`;

return $i;

exit();



