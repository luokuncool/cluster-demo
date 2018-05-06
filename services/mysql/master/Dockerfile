FROM mysql:5.6
ADD ./services/mysql/master/init.sql /docker-entrypoint-initdb.d
ADD ./services/mysql/data.sql /docker-entrypoint-initdb.d