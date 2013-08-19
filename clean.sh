#!/bin/bash

if [ -z "$1" ]; then
	echo usage: $0 directory [deep]
	exit
fi

SOURCE=$1
CURRENT=`pwd`

dirsfound=`find $SOURCE -name "error_log"`
for i in $dirsfound
do
echo Deleting "$i" ...
rm -Rfv "$i"
done

rm -fv $SOURCE/application/cache/cache/*.*
rm -fv $SOURCE/application/cache/templates_c/*.php

rm -fv $SOURCE/admin/application/cache/cache/*.*
rm -fv $SOURCE/admin/application/cache/templates_c/*.php

rm -fv $SOURCE/lib/cache/cache/*.*
rm -fv $SOURCE/lib/cache/templates_c/*.php

rm -rf $SOURCE/public/_thumbs


cat /dev/null > $SOURCE/application/logs/admin.log
cat /dev/null > $SOURCE/application/logs/site.log
cat /dev/null > $SOURCE/application/logs/sql.log

cat /dev/null > $SOURCE/admin/application/logs/admin.log
cat /dev/null > $SOURCE/admin/application/logs/site.log
cat /dev/null > $SOURCE/admin/application/logs/sql.log



dirsfound=`find $SOURCE -name ".DS_Store"`
for i in $dirsfound
do
echo Deleting $i ...
rm -Rfv $i
done

dirsfound=`find $SOURCE -name "._*"`
for i in $dirsfound
do
echo Deleting "$i" ...
rm -Rfv "$i"
done

if [ $2 ]
then
	echo "Cleaning SVN paths..."
	dirsfound=`find $SOURCE -name ".svn"`
	for i in $dirsfound
	do
	echo Deleting "$i" ...
	rm -Rfv "$i"
	done
fi


