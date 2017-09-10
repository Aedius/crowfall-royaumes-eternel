base on https://github.com/maxpou/docker-symfony



> docker-compose -f [...] exec php composer install
> docker-compose -f [...] exec php php bin/console doctrine:migrations:migrate

##### for https : 

( you should need to comment the 3 rows with /etc/letsencrypt/live/ to set up nginx)

> docker run -it --rm -v certs:/etc/letsencrypt -v certs-data:/data/letsencrypt deliverous/certbot certonly --webroot --webroot-path=/data/letsencrypt -d royaumes-eternels.net

###### cronjob

> 0 0 */15 * * docker run -t --rm -v certs:/etc/letsencrypt -v certs-data:/data/letsencrypt -v /var/log/letsencrypt:/var/log/letsencrypt deliverous/certbot renew --webroot --webroot-path=/data/letsencrypt && docker kill -s HUP nginx >/dev/null 2>&1