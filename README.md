## 介绍

一般情况下，因为条件不允许我们很难通过真机来配置mysql的读写分离，nginx负载均衡等等。
但是我们可以通过虚拟机在一台电脑上可以配置出这样的环境，这很方便。然而还有一个更方便的办法，那就使用docker来配置这样一个环境。
这个项目就是用docker配置的这样一个环境。

```
├── Dockerfile //fpm容器声明
├── docker-compose.yml
├── php.ini
└── services
    ├── mysql
    │   ├── data.sql
    │   ├── master
    │   │   ├── Dockerfile
    │   │   ├── init.sql
    │   │   └── my.cnf
    │   ├── slave1
    │   │   ├── Dockerfile
    │   │   ├── init.sql
    │   │   └── my.cnf
    │   └── slave2
    │       ├── Dockerfile
    │       ├── init.sql
    │       └── my.cnf
    └── nginx
        └── web.conf
```

其中包含：

* 三个mysql容器，一个master，两个slave

* 一个nginx容器

```
upstream php-fpm-backend {
      server fpm1:9000;
      server fpm2:9000;
}

server {
    listen       80;
    server_name  cluster-demo.local;

    #charset koi8-r;
    #access_log  /var/log/nginx/host.access.log  main;
    root /usr/share/nginx/html;

    location / {
        index  index.html index.htm index.php;
        if ( -f $request_filename) {
            break;
        }
        if ( !-e $request_filename) {
            rewrite ^(.*)$ /index.php/$1 last;
            break;
        }
    }

    #error_page  404              /404.html;

    # redirect server error pages to the static page /50x.html
    #
    #error_page   500 502 503 504  /50x.html;
    location = /50x.html {
        root   /usr/share/nginx/html;
    }

    # proxy the PHP scripts to Apache listening on 127.0.0.1:80
    #
    #location ~ \.php$ {
    #    proxy_pass   http://127.0.0.1;
    #}

    # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
    #
    location ~ \.php$ {
        fastcgi_pass   php-fpm-backend;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  /usr/share/nginx/html/$fastcgi_script_name;
        include        fastcgi_params;
    }

    # deny access to .htaccess files, if Apache's document root
    # concurs with nginx's one
    #
    #location ~ /\.ht {
    #    deny  all;
    #}
}
```

* 两个fpm容器（fpm1、fpm2）

如果是php脚本请求，nginx会通过负载均衡的方式将请求转发到对应的fpm

```
session.save_handler=redis
session.save_path = "tcp://redis:6379?auth=redis_password"
```

php当中通过控制数据库连接获取的方法来控制读写分离，读数据用salve，写用master

```
function db($master = false)
{
    static $connections;
    $slaveHosts = [
        'mysql-slave1',
        'mysql-slave2'
    ];
    $host       = $master ? 'mysql-master' : $slaveHosts[rand(0, 1)];
    if (isset($connections[$host])) {
        return $connections[$host];
    }

    $db = new mysqli($host, 'root', 'root', 'demo');
    return $db;
}
```

* 以及一个redis容器，主要用于解决负载均衡带来的session问题

## 安装

```bash
git clone https://github.com/luokuncool/cluster-demo.git
cd cluster-demo
docker-compose up
```

修改host文件 `127.0.0.1 cluster-demo.local`

然后访问 http://cluster-demo.local:8889

默认用户名：demo
密码：password

