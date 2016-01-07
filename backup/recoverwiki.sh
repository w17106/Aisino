#!/bin/bash

#Recover MySql DB
/usr/bin/mysql md_wiki < $DISTPATH/mdwiki_backup.sql;

#Recover /usr/local/apache/htdocs/mdwiki directory
/bin/tar zxvf  $DISTPATH/mdwiki_backup.tar.gz /usr/local/apache/htdocs/mdwiki  >$D
ISTPATH/backup.log 2>&1;