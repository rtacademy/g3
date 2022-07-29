# Blog (Autotests version)

## Перший запуск
Виконати послідовно наступні команди у Терміналі (враховується що Docker Compose вже встановлено):
```
$ cd /path/to/docker-compose.yml
``` 
де `/path/to/docker-compose.yml` - шлях до папки з файлом `docker-compose.yml`
``` 
$ chmod -R 777 .
$ docker compose up -d
$ docker exec rtacademy_g2_database /bin/bash -c "mysql -u rtacademy_g2 -prtacademy_g2 blog_rtacademy_g2 < /import/schema.sql"
$ docker exec rtacademy_g2_database /bin/bash -c "mysql -u rtacademy_g2 -prtacademy_g2 blog_rtacademy_g2 < /import/data.sql"
```

### Frontend:
Відкрити http://localhost:8888/ для перегляду сайту.

Стандартні користувачі:
* adam.bankhurst / password
* matt.kim / password
* joe.skrebels / password
* taylor.lyles / password
* matt.purslow / password
* kat.bailey / password


## Наступні запуски
```
$ cd /path/to/docker-compose.yml
``` 
де `/path/to/docker-compose.yml` - шлях до папки з файлом `docker-compose.yml`
``` 
$ docker compose up -d
```