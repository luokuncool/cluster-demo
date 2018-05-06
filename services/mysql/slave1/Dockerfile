FROM mysql:5.6
ADD ./services/mysql/slave1/init.sql /docker-entrypoint-initdb.d
ADD ./services/mysql/data.sql /docker-entrypoint-initdb.d