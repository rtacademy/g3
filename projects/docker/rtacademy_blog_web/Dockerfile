FROM nginx:latest

RUN \
    rm -Rf /etc/nginx/sites-enabled/* /etc/nginx/sites-available/*

COPY ./docker/rtacademy_blog_web/etc/nginx/nginx.conf               /etc/nginx/
COPY ./docker/rtacademy_blog_web/etc/nginx/sites-enabled/*          /etc/nginx/sites-enabled/

EXPOSE 80/tcp

ENTRYPOINT ["nginx", "-g", "daemon off;"]