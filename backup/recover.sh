#!/bin/bash 
SRCPATH=/svn_backup/20150210; #定义仓库parent路径 
#DISTPATH=/opt/svndata
DISTPATH=/home/svn/data
#DISTPATH=/svn_backup/`date +\%Y%m%d` ; #定义存放路径; 
REPO_LIST=/home/svn/scripts/backup/repo_list; #列出要备份的库


if [ -d "$DISTPATH" ] 
then
    mkdir $DISTPATH/log
    touch $DISTPATH/log/backup.log
else
    mkdir $DISTPATH 
    #chmod g+s $DISTPATH 
    mkdir $DISTPATH/log 
    touch $DISTPATH/log/backup.log
fi
 

for i in `cat $REPO_LIST`
do
    echo "Backing up to revision `/usr/bin/svnlook youngest $SRCPATH/$i` for repository "$i" !" >>$DISTPATH/log/backup.log 
    /usr/bin/svnadmin hotcopy $SRCPATH/$i $DISTPATH/$i --clean-logs >$DISTPATH/log/$i.log 2>&1 
done

#/home/svn/scripts/backup/del_oldbackup.sh  #运行删除脚本，对过期备份进行删除。 
