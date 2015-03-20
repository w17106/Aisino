#!/bin/bash
 
OLDDATE=`date -d "6 days ago" +%Y%m%d`;

for i in `ls /svn_backup`
do
    if [ "$i" -lt  "$OLDDATE" ]
    then
        echo "i is /svn_backup/$i"
        rm -rf /svn_backup/$i 2>&1
    fi
done
