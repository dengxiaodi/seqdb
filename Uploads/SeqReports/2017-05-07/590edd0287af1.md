# seqdb
seqDB
--------
seqDB是一个用来上存储测序数据信息的工具。
将样本，文库，送样，数据关联起来，给每一条数据唯一编号。

seqDB配置
-----

1.将seqdb.sql文件导入到数据库

2.修改数据库连接文件

```
Application/Common/Conf/config.php
Application/User/Conf/config.php
```

docker快速配置环境
-----

1.下载mysql和nginx的镜像
```
docker pull mysql:5.6
docker pull richarvey/nginx-php-fpm:php5
```

2.运行数据库
创建一个用来用来永久存储数据的文件夹，这里举例为/homedir/seqdb/mysql/
`MYSQL_ROOT_PASSWORD`为root密码
`MYSQL_USER`为创建一个mysql用户
`MYSQL_PASSWORD`为用户密码

```
docker run --name seqdb-mysql -p 3306:3306 -v /homedir/seqdb/mysql/:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=root-passwd -e MYSQL_USER=seqdb -e MYSQL_PASSWORD=seqdb-passwd -d mysql:5.6
```

3.运行web服务器
创建一个存放seqdb代码的文件夹,这里举例为/homedir/seqdb/php/
```
docker run --name seqdb-web -p 8989:80 -v /homedir/seqdb/php/:/var/www/html -d richarvey/nginx-php-fpm:php5
```
给Runtime可写权限


数据库备份
-------
结合docker与百度云实现快速安全云备份

主要语法有：


1.讲数据库导出
```
docker exec seqdb-mysql sh -c 'exec mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" seqdb' > seqdb.sql
```

2.linux 下使用百度云

[bypy介绍](http://www.lyhonk.com/linuxhuan-jing-xia-shi-yong-bai-du-yun-wang-pan/)

详细介绍在上面这个介绍里

用到的主要是同步备份功能

```
bypy syncup Local-SQL-dump-dir /sqldump/
```

3.加入计划任务

```
crontab -u root -e
```

每天凌晨数据库变动较小的时候进行备份，我们选在凌晨两点半
```
30      2       *       *       *       /root/seqdb/sqldump.sh
```



--------------------------------------------------

TUDO：
----
1.用户删除

2.数据前后段验证

3.数据导出

4.数据导入【完成】

5.用户权限分配

6.组管理

7.组权限分配



