### Create new app
(for developers only)
```
$local$ docker exec -it rtacademy_blog_app_frontend bash
$ git config --global user.email "roman@rtacademy.net"
$ git config --global user.name "Roman"
# cd /var/www
# symfony new blog --version=6.1

$local$ docker exec -it rtacademy_blog_app_admin bash
$ git config --global user.email "roman@rtacademy.net"
$ git config --global user.name "Roman"
# cd /var/www
# symfony new blog_admin --version=6.1

$local$ docker exec -it rtacademy_blog_app_api bash
$ git config --global user.email "roman@rtacademy.net"
$ git config --global user.name "Roman"
# cd /var/www
# symfony new blog_api --version=6.1
# composer req --dev maker
# composer req symfony/validator doctrine/annotations doctrine/orm
# composer req symfony/security-bundle
# composer req api
# symfony console make:entity Category
# symfony console make:entity User
# symfony console make:entity Post
# symfony console make:entity PostCover
# symfony console make:entity PostComment
# symfony console make:entity ApiUser
# symfony console make:migration
# symfony console doctrine:migrations:migrate
# symfony console cache:clear
```