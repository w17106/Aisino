#!/bin/bash
SRCPATH=/usr/local/apache/htdocs/mdwiki; #定义wiki路径
DISTPATH=/backup_wiki/wiki/`date +\%Y%m%d` ; #定义存放路径;
OLDPATH=/backup_wiki/wiki/`date -d "7 days ago" +%Y%m%d`;

if [ -d "$DISTPATH" ]
then
    touch $DISTPATH/backup.log
else
    mkdir $DISTPATH
    touch $DISTPATH/backup.log
fi

#Backup MySql DB
/usr/bin/mysqldump -uroot -pnewpassword -h localhost --opt md_wiki > $DISTPATH/m
dwiki_backup.sql ;

#Backup /usr/local/apache/htdocs/mdwiki directory
/bin/tar czpvf $DISTPATH/mdwiki_backup.tar.gz /usr/local/apache/htdocs/mdwiki  >
$DISTPATH/backup.log 2>&1;

#Remove old backup
if [ -d "$OLDPATH" ]
then
    rm -rf $OLDPATH 2>&1
fi