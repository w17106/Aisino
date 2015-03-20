#!/bin/bash 
SRCPATH=/opt/svndata; #定义仓库parent路径 
DISTPATH=/svn_backup/`date +\%Y%m%d` ; #定义存放路径; 
REPO_LIST=/home/svn/scripts/backup/repo_list; #列出要备份的库

if [ -d "$DISTPATH" ] 
then
    mkdir $DISTPATH/log
    touch $DISTPATH/log/backup.log
else
    mkdir $DISTPATH 
    mkdir $DISTPATH/log 
    touch $DISTPATH/log/backup.log
fi
cp $SRCPATH/accessfile  $DISTPATH; #备份access文件
cp $SRCPATH/passwdfile  $DISTPATH; #备份passwd文件
 
echo "Stoping svnserve…"; #Stop SVN service
killall svnserve;
echo "Finished!";


for i in `cat $REPO_LIST`
do
    echo "Backing up to revision `/usr/bin/svnlook youngest $SRCPATH/$i` for repository "$i" !" >>$DISTPATH/log/backup.log 
    /usr/bin/svnadmin hotcopy $SRCPATH/$i $DISTPATH/$i --clean-logs >$DISTPATH/log/$i.log 2>&1 
done

echo "Starting svnserve…";
/usr/bin/svnserve  --listen-port 9999 -d -r ${SRCPATH};
echo "Finished!";

