# Blog

## Перший запуск
Виконати послідовно наступні команди у Терміналі (враховується що Docker Compose вже встановлено):
```
$ echo "127.0.0.1    blog.local admin.blog.local api.blog.local" | sudo tee -a /etc/hosts
```
```
$ cd /path/to/docker-compose.yml
``` 
де `/path/to/docker-compose.yml` - шлях до папки з файлом `docker-compose.yml`
``` 
$ chmod -R 777 .
$ docker compose up -d
$ docker exec rtacademy_blog_app_api /bin/bash -c "/usr/local/bin/composer install --optimize-autoloader"
$ docker exec rtacademy_blog_app_api /bin/bash -c "symfony console doctrine:migrations:migrate"
$ docker exec rtacademy_blog_database_mariadb /bin/bash -c "mysql -u blog -pblogpassword blog < /tmp/initial.sql"
```

### API:
Відкрити http://api.blog.local/ для перегляду документації.

Також доступна [колекція](./blog_api/Postman.Collection.json) та [змінні оточення](./blog_api/Postman.Environment.json) Postman.

## Наступні запуски
```
$ cd /path/to/docker-compose.yml
``` 
де `/path/to/docker-compose.yml` - шлях до папки з файлом `docker-compose.yml`
``` 
$ docker compose up -d
```