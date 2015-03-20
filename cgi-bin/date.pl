#!C:\Strawberry\perl\bin\perl.exe
my($sec,$min,$hour,$mday,$mon,$year,$wday,$yday)=localtime(time); 
$year+=1900; 
$mon+=1;
$fn="$year$mon$mday"; 
mkdir $fn; 
print FD $fn; 
close(FD)