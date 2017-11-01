base on https://github.com/maxpou/docker-symfony

###alias : 

######for preprod :

>alias dc='docker-compose -f docker-compose-preprod.yml -f docker-compose.yml '

######for prod :

>alias dc='docker-compose -f docker-compose-prod.yml -f docker-compose.yml '

######symfony :

>alias symfony='dc exec php php bin/console '


### install commands :

> dc exec php composer install
> symfony cache:clear --no-warmup
> symfony doctrine:migrations:migrate
> symfony assets:install --symlink

##### for https : 

( you should need to comment the 3 rows with /etc/letsencrypt/live/ to set up nginx)

> docker run -it --rm -v /etc/letsencrypt:/etc/letsencrypt -v /data/letsencrypt:/data/letsencrypt deliverous/certbot certonly --webroot --webroot-path=/data/letsencrypt -d royaumes-eternels.net

###### cronjob

> 0 0 */14 * * docker run -t --rm -v /etc/letsencrypt:/etc/letsencrypt -v /data/letsencrypt:/data/letsencrypt -v /var/log/letsencrypt:/var/log/letsencrypt deliverous/certbot renew --webroot --webroot-path=/data/letsencrypt && docker kill -s HUP nginx >/dev/null 2>&1