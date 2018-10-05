FROM nginx:1.15-alpine-perl

COPY ./config /etc/nginx
COPY wait-for /usr/local/bin/
RUN chmod +x /usr/local/bin/wait-for

CMD ["/usr/local/bin/wait-for", "php:9000", "-t", "300", "--", "nginx", "-g", "daemon off;"]