CHANGE MASTER TO MASTER_HOST ='mysql-master', MASTER_USER ='slave_account', MASTER_PASSWORD ='123456', MASTER_LOG_FILE ='mysql-bin.000004', MASTER_LOG_POS =120;
START SLAVE;