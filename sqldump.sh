#!/bin/bash
dumpdir="/root/seqdb/sqldump/"
dt=`date "+%Y-%m-%d"`
randum=`cat /dev/urandom | head -n 10 | md5sum | head -c 10`
filename=${dumpdir}"SeqDB-"${dt}"-"${randum}".sql"
docker exec seqdb-mysql sh -c 'exec mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" seqdb' > ${filename}
zip -9 ${filename}.zip ${filename}
rm -f ${filename}
bypy syncup ${dumpdir} /sqldump/
