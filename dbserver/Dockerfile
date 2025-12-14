
FROM mariadb:10.5
COPY mysqld.cnf /etc/mysql/conf.d/mysqld.cnf
COPY --chown=mysql:mysql sqlconfig/7009db.sql /docker-entrypoint-initdb.d/
USER mysql
#EXPOSE 3306
